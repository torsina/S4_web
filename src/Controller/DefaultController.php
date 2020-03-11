<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
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
     * @Route("/", name="default")
     */
    public function index()
    {
        $latestPosts = $this
            ->getDoctrine()
            ->getRepository(Post::class)
            ->findBy([
            ], ['createdTime' => 'DESC'], 4);
        return $this->render('sparrow/index.html.twig', [
            'latestPosts' => $latestPosts
        ]);
    }

    /**
     * @Route("/post/create", name="createPost")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function createPost(Request $request)
    {
        $post = new Post();
        $post
            ->setCreatedTime(new \DateTime())
            ->setEditedTime(new \DateTime())
            ->setUser($this->security->getUser());
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('readPost', ["id" => $task->getId()]);
        }

        return $this->render('default/createPost.html.twig', [
            'createForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/post/{id}", name="readPost")
     * @param Post $post
     * @return Response
     */
    public function readPost(Post $post) {

        $post->addViews(1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($post);
        $entityManager->flush();
        return $this->render('sparrow/post.html.twig', [
            'post' => $post,
        ]);
    }
}
