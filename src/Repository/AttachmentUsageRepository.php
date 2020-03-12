<?php

namespace App\Repository;

use App\Entity\AttachmentUsage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AttachmentUsage|null find($id, $lockMode = null, $lockVersion = null)
 * @method AttachmentUsage|null findOneBy(array $criteria, array $orderBy = null)
 * @method AttachmentUsage[]    findAll()
 * @method AttachmentUsage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttachmentUsageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AttachmentUsage::class);
    }

    // /**
    //  * @return AttachmentUsage[] Returns an array of AttachmentUsage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function getDefaultUserProfilePicture() {
        return $this->findOneBy(['usedAs' => 'user_profile_default']);
    }

    public function getUserProfilePicture() {
        return $this->findOneBy(['usedAs' => 'user_profile']);
    }

    public function getPost() {
        return $this->findOneBy(['usedAs' => 'post']);
    }

    /*
    public function findOneBySomeField($value): ?AttachmentUsage
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
