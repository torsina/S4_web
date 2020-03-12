<?php
namespace App\Controller;

use App\Entity\AttachmentUsage;
use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;

class AdminPostController extends EasyAdminController
{
    protected function persistPostEntity(Post $post)
    {
        if(!$post->getImage()->getUsedAs()) {
            $post->getImage()->setUsedAs($this
                ->getDoctrine()
                ->getRepository(AttachmentUsage::class)
                ->getPost());
        }


        parent::persistEntity($post);
    }

}