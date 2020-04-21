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
     * SELECT * FROM `comment`
     * INNER JOIN user ON comment.user_id = user.id
     * WHERE user.username = 'bob'
     */
    public function getByUsername($username)
    {
        return $this->createQueryBuilder('c')
            ->join('c.user', 'u')
            ->where('u.username = :username')
            ->setParameter(':username', $username)
            ->getQuery()
            ->getResult();
    }
}
