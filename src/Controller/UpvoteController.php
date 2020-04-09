<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Upvote;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class UpvoteController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
    }

    /**
     * @Route("/api/upvote/{id}/{direction}", name="setUpvote", methods={"POST"})
     * @param Post $post
     * @param bool $direction
     * @return JsonResponse
     */
    public function setUpvote(Post $post, bool $direction)
    {
        $user = $this->security->getUser();
        $wasAlreadyUpvoted = $this
            ->getDoctrine()
            ->getRepository(Upvote::class)
            ->wasAlreadyUpvoted($post->getId(), $user->getId());

        if(!$wasAlreadyUpvoted) {
            $upvote = new Upvote();
            $upvote
                ->setUser($user)
                ->setDirection($direction)
                ->setPost($post);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($upvote);
            $entityManager->flush();
        } else {
            $upvote = $this
                ->getDoctrine()
                ->getRepository(Upvote::class)
                ->getUpvote($post->getId(), $user->getId());
            $upvote->setDirection($direction);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($upvote);
            $entityManager->flush();
        }
        return $this->json(["status" => "OK"]);
    }

    /**
     * @Route("/api/upvote/own/{id}", name="getOwnUpvote", methods={"GET"})
     * @param Post $post
     * @return JsonResponse
     */
    public function getUpvote(Post $post) {
        $user = $this->security->getUser();
        $upvote = $this
            ->getDoctrine()
            ->getRepository(Upvote::class)
            ->getUpvote($post->getId(), $user->getId());
        if($upvote != null) {
            return $this->json(["direction" => $upvote->getDirection()]);
        } else {
            return $this->json(["direction" => null]);
        }
    }

    /**
     * @Route("/api/upvote/count/{id}", name="countUpvotes", methods={"GET"})
     * @param Post $post
     * @return JsonResponse
     */
    public function countUpvotes(Post $post) {
        $upvote = $this
            ->getDoctrine()
            ->getRepository(Upvote::class)
            ->getUpvotes($post->getId());
        return $this->json($upvote);
    }

}
