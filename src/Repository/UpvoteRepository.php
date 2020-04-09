<?php

namespace App\Repository;

use App\Entity\Upvote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Upvote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Upvote|null findOneBy(array $criteria, array $orderBy = null)
 * @method Upvote[]    findAll()
 * @method Upvote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UpvoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Upvote::class);
    }

    // /**
    //  * @return Upvote[] Returns an array of Upvote objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function wasAlreadyUpvoted(int $postId, int $userId) {
        $result = $this
            ->createQueryBuilder('u')
            ->andWhere('u.post = :postId')
            ->andWhere('u.user = :userId')
            ->setParameter('postId', $postId)
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
        return count($result) === 1;
    }

    public function getUpvote(int $postId, int $userId) {
        $result = $this
            ->createQueryBuilder('u')
            ->andWhere('u.post = :postId')
            ->andWhere('u.user = :userId')
            ->setParameter('postId', $postId)
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
        return $result == null ? null : $result[0];
    }

    public function getUpvotes(int $postId) {
        $result = $this->createQueryBuilder('u')
            ->andWhere('u.post = :postId')
            ->groupBy('u.direction')
            ->select('u.direction, count(u.id)')
            ->setParameter('postId', $postId)
            ->getQuery()
            ->getResult();

        $upvotes = 0;
        $downvotes = 0;
        for($i = 0; $i < count($result); $i++) {
            if($result[$i]["direction"] == false) {
                $downvotes = intval($result[$i]["1"]);
            } else {
                $upvotes = intval($result[$i]["1"]);
            }
        }

        return ["upvote" => $upvotes, "downvote" => $downvotes];
    }

    /*
    public function findOneBySomeField($value): ?Upvote
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
