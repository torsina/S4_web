<?php

namespace App\DataFixtures;

use App\Entity\Attachment;
use App\Entity\AttachmentUsage;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\Upvote;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $usages = ["user_profile_default", "user_profile", "post"];
        $attachmentUsages = [];
        foreach($usages as $usage) {
            $attachmentUsages[$usage] = new AttachmentUsage();
            $attachmentUsages[$usage]->setUsedAs($usage);
            $manager->persist($attachmentUsages[$usage]);
        }


        $imageName = "author-img.png";
        $imageSource = __DIR__."\..\..\assets\images";

        $userDefaultAttachment = $this->uploadAttachment($imageSource, $imageName, $attachmentUsages["user_profile_default"]);
        $postAttachment = $this->uploadAttachment($imageSource."\post-image", "post-image-1300x500-03.jpg", $attachmentUsages["post"]);


        $user = new User();
        $user->setUsername("root");
        $user->setPassword($this->encoder->encodePassword($user, "root"));
        $user->setRoles(["ROLE_ADMIN", "ROLE_USER"]);
        $user->setProfilePicture($userDefaultAttachment);
        $user->setBanner("\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.");
        $manager->persist($user);

        $tags = [];
        for($i = 0; $i < 100; $i++) {
            $tag = new Tag();
            $tag->setName($i);
            $manager->persist($tag);
            $tags[] = $tag;
        }

        $comments = [];
        for($i = 0; $i < 8; $i++) {
            $firstDepth = new Comment();
            $firstDepth->setCreatedTime(new \DateTimeImmutable())
                ->setOwner($user)
                ->setContent("Lorem ".$i." ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.");
            $comments[] = $firstDepth;
            if($i % 2 == 0) {
                $secondDepth = new Comment();
                $secondDepth->setCreatedTime(new \DateTimeImmutable())
                    ->setOwner($user)
                    ->setContent("Lorem SECOND DEPTH".$i." ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.")
                    ->setCommentOwner($firstDepth);
                $comments[] = $secondDepth;
                $secondDepth2 = new Comment();
                $secondDepth2->setCreatedTime(new \DateTimeImmutable())
                    ->setOwner($user)
                    ->setContent("Lorem SECOND DEPTH".$i." ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.")
                    ->setCommentOwner($firstDepth);
                $comments[] = $secondDepth2;
                if($i % 4 == 0) {
                    $thirdDepth = new Comment();
                    $thirdDepth->setCreatedTime(new \DateTimeImmutable())
                        ->setOwner($user)
                        ->setContent("Lorem THIRD DEPTH".$i." ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.")
                        ->setCommentOwner($secondDepth);

                    $fourthDepth = new Comment();
                    $fourthDepth->setCreatedTime(new \DateTimeImmutable())
                        ->setOwner($user)
                        ->setContent("Lorem FOURTH DEPTH".$i." ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.")
                        ->setCommentOwner($thirdDepth);
                    $comments[] = $thirdDepth;
                    $comments[] = $fourthDepth;
                }
            }
        }

        $posts = [];
        for($i = 0; $i < 10; $i++) {
            $post = new Post();
            $post->setUser($user)
                ->setImage($postAttachment)
                ->setCreatedTime(new \DateTimeImmutable())
                ->setEditedTime(new \DateTimeImmutable())
                ->setContent("Sed  ".$i." ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?")
                ->setDescription("Lorem ".$i." ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.")
                ->setTitle("Lorem ".$i." Ipsum");
            $posts[] = $post;
            for ($j = $i; $j < 10; $j++) {
                $post->addTag($tags[$j]);

            }
            $manager->persist($post);
            sleep(1);
        }
        foreach($comments as $comment) {
            $comment->setPost($posts[0]);
            $manager->persist($comment);
        }

        for($k = 0; $k < 10; $k++) {
            $tempUser = new User();
            $tempUser->setUsername($k);
            $tempUser->setPassword($this->encoder->encodePassword($tempUser, $k));
            $tempUser->setRoles(["ROLE_USER"]);
            $tempUser->setProfilePicture($userDefaultAttachment);
            $tempUser->setBanner("\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.");
            $manager->persist($tempUser);

            $tempUpvote = new Upvote();
            $tempUpvote->setPost($posts[0]);
            $tempUpvote->setDirection(true);
            $tempUpvote->setUser($tempUser);
            $manager->persist($tempUpvote);
        }

        $manager->flush();


    }

    public function uploadAttachment(string $srcFolder, string $filename, AttachmentUsage $usedAs): Attachment
    {
        $imageDest = __DIR__."\..\..\public\images\attachments";

        copy($srcFolder."\\".$filename, $imageDest."\\".$filename);
        $file = new UploadedFile($imageDest."\\".$filename, $filename, 'application/png', null, true);
        $attachment = new Attachment();
        $attachment->setup($file);
        $attachment->setUsedAs($usedAs);
        $this->manager->persist($attachment);
        return $attachment;
    }
}
