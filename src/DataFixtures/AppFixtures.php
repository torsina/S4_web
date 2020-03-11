<?php

namespace App\DataFixtures;

use App\Entity\Attachment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $imageName = "author-img.png";
        $defaultImageSource = __DIR__."\..\..\assets\images";
        $defaultImageDest = __DIR__."\..\..\public\images\attachments";
        copy($defaultImageSource."\\".$imageName, $defaultImageDest."\\".$imageName);
        $file = new UploadedFile($defaultImageDest."\\".$imageName, $imageName, 'application/png', null, true);
        $attachment = new Attachment($file);
        // unique identifier for default user profile image
        $attachment->setUsedAs("user_profile_default");
        $manager->persist($attachment);
        $manager->flush();


        $user = new User();
        $user->setUsername("root");
        $user->setPassword($this->encoder->encodePassword($user, "root"));
        $user->setRoles(["ROLE_ADMIN", "ROLE_USER"]);
        $user->setProfilePicture($attachment);
        $user->setBanner("\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.");
        $manager->persist($user);

        $manager->flush();
    }
}
