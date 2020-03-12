<?php
namespace App\Controller;

use App\Entity\Attachment;
use App\Entity\AttachmentUsage;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUserController extends EasyAdminController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * AdminUserController constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function persistUserEntity(User $user)
    {
        $encodedPassword = $this->encodePassword($user);
        if($encodedPassword)$user->setPassword($encodedPassword);
        if(!$user->getProfilePicture()) $user->setProfilePicture($this
            ->getDoctrine()
            ->getRepository(Attachment::class)
            ->getDefaultUserProfilePicture());

        parent::persistEntity($user);
    }

    protected function updateUserEntity(User $user)
    {
        $encodedPassword = $this->encodePassword($user);
        if($encodedPassword)$user->setPassword($encodedPassword);

        parent::updateEntity($user);
    }

    public function encodePassword(User $user)
    {
        if (!$user instanceof User || !$user->getPlainPassword()) {
            return null;
        }

        // now it's work if plainPassword string was set
        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $user->getPlainPassword())
        );
    }
}