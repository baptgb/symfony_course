<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function getCommentsCountGroupByPost()
    {
        return $this->createQueryBuilder('c')
            ->select('p.id AS post_id, COUNT(c.id) AS nb_comments')
            ->join('c.post', 'p')
            ->groupBy('p.id')
            ->getQuery()->getArrayResult();
    }

    /**
     * SELECT COUNT(*) FROM `comment` AS c
    INNER JOIN post AS p ON p.id = c.post_id
    WHERE p.user_id = 1
     */
    public function countCommentsByPostUser(User $user)
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->innerJoin('c.post', 'p')
            ->where('p.user = :user')
            ->setParameter(':user', $user)
            ->getQuery()->getSingleScalarResult();
    }
}
