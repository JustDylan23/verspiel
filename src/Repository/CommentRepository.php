<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function add(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByCommentSection(int $commentSection, ?int $replyTo = null): QueryBuilder
    {
        $qb = $this
            ->createQueryBuilder('c')
            ->join('c.author', 'a')
            ->addSelect('a')
            ->leftJoin('c.replies', 'r')
            ->addSelect('count(r.id) as replyCount')
            ->andWhere('c.commentSection = :commentSection')
            ->groupBy('c.id')
            ->setParameter('commentSection', $commentSection)
        ;
        if ($replyTo) {
            $qb
                ->andWhere('c.replyTo = :replyTo')
                ->setParameter('replyTo', $replyTo)
            ;
        } else {
            $qb->andWhere('c.replyTo IS NULL');
        }

        return $qb;
    }
}
