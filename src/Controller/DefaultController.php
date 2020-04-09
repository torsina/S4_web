<?php

namespace App\Controller;

use App\Entity\Attachment;
use App\Entity\AttachmentUsage;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\Upvote;
use App\Form\CommentType;
use App\Form\PostType;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Security\Core\Security;

class DefaultController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {

        $user = $this->security->getUser();
        $latestPosts = $this
            ->getDoctrine()
            ->getRepository(Post::class)
            ->findBy([
            ], ['createdTime' => 'DESC'], 4);
        return $this->render('sparrow/index.html.twig', [
            'latestPosts' => $latestPosts,
            'user' => $user
        ]);

        //return $this->render('vue.twig');
    }

    /**
     * @Route("/post/create", name="createPost", methods={"GET"})
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function createPost(Request $request)
    {
        $user = $this->security->getUser();
        $post = new Post();
        $post
            ->setCreatedTime(new \DateTime())
            ->setEditedTime(new \DateTime())
            ->setUser($user);
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();

            if(!$task->getImage()->getUsedAs()) {
                $task->getImage()->setUsedAs($this
                    ->getDoctrine()
                    ->getRepository(AttachmentUsage::class)
                    ->getPost());
            }


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            //return $this->redirectToRoute('readPost', ["id" => $task->getId()]);
        }

        return $this->render('sparrow/createPost.html.twig', [
            'createForm' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/post/create", name="uplaodPost", methods={"POST"})
     * @param Request $request
     * @throws Exception
     */
    public function uploadPost(Request $request) {
        $user = $this->security->getUser();
        $post = new Post();
        $post
            ->setCreatedTime(new \DateTime())
            ->setEditedTime(new \DateTime())
            ->setUser($user);

        $title = $request->request->get("title");
        $description = $request->request->get("description");
        $content = $request->request->get("content");
        $rawTags = $request->request->get('tags');

        $image = $request->files->get("image");

        $attachment = new Attachment();
        //$attachment->setImageFile($image);
        $attachment->setup($image);
        $attachment->setUsedAs($this->getDoctrine()->getRepository(AttachmentUsage::class)->getPost());
        $entityManager = $this->getDoctrine()->getManager();

        $post = new Post();
        $post
            ->setUser($user)
            ->setImage($attachment)
            ->setCreatedTime(new \DateTimeImmutable())
            ->setEditedTime(new \DateTimeImmutable())
            ->setTitle($title)
            ->setDescription($description)
            ->setContent($content);
        $tagNames =
            array_unique(
                array_filter(
                    array_map('trim', explode(",", $rawTags))));
        $tags = $this->getDoctrine()->getRepository(Tag::class)->findBy(["name" => $tagNames]);
        $newNames = array_diff($tagNames, $tags);


        foreach ($newNames as $name) {
            $tag = new Tag();
            $tag->setName($name);
            $entityManager->persist($tag);
            $tags[] = $tag;
        }
        foreach($tags as $tag) {
            $post->addTag($tag);
        }
        $entityManager->persist($attachment);
        $entityManager->persist($post);
        $entityManager->flush();
        return $this->json(["id" => $post->getId()]);
    }

    /**
     * @Route("/post/{id}", name="readPost")
     * @param Post $post
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function readPost(Post $post, Request $request) {
        $user = $this->security->getUser();


        $post->addViews(1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($post);
        $entityManager->flush();

        $postRepository = $this
            ->getDoctrine()
            ->getRepository(Post::class);

        $nextPost = $postRepository->getNextByCreatedDate($post);
        $previousPost = $postRepository->getPreviousByCreatedDate($post);

        $upvotes = $this->getDoctrine()->getRepository(Upvote::class)->getUpvotes($post->getId());
        return $this->render('sparrow/post.html.twig', [
            'post' => $post,
            'nextPost' => $nextPost,
            'previousPost' => $previousPost,
            'upvotes' => $upvotes,
            'user' => $user
        ]);
    }

    /**
     * @Route("/post/page/{page}", name="postPage", requirements={"page"="\d+"})
     * @param Request $request
     * @param int $page
     * @return Response
     */
    public function browsePosts(Request $request, int $page)
    {
        $user = $this->security->getUser();

        // get entity manager
        $em = $this->getDoctrine()->getManager();

        // get the user repository
        $developers = $em->getRepository(Post::class);

        // build the query for the doctrine paginator
        $query = $developers->createQueryBuilder('post')
            ->orderBy('post.createdTime', 'DESC')
            ->getQuery();

        //set page size
        $pageSize = '10';

        // load doctrine Paginator
        $paginator = new Paginator($query);

        // you can get total items
        $totalItems = count($paginator);

        // get total pages
        $pagesCount = ceil($totalItems / $pageSize);

        // now get one page's items:
        $result = $paginator
            ->getQuery()
            ->setFirstResult($pageSize * ($page-1)) // set the offset
            ->setMaxResults($pageSize)->getResult(); // set the limit
        return $this->render('sparrow/browsePost.html.twig', [
            'posts' => $result,
            'pageCount' => $pagesCount,
            'pageNumber' => $page,
            'user' => $user
        ]);
    }

}
