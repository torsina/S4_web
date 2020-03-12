<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function getNextByCreatedDate(Post $post): Post
    {
        $result = $this->createQueryBuilder('p')
            ->andWhere('p.createdTime > :datetime')
            ->setParameter('datetime', $post->getCreatedTime())
            ->orderBy('p.createdTime', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
        if(count($result) == 0) {
            return $post;
        } else {
            return $result[0];
        }
    }

    public function getPreviousByCreatedDate(Post $post): Post
    {
        $result = $this->createQueryBuilder('p')
            ->andWhere('p.createdTime < :datetime')
            ->setParameter('datetime', $post->getCreatedTime())
            ->orderBy('p.createdTime', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
        if(count($result) == 0) {
            return $post;
        } else {
            return $result[0];
        }
    }

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
