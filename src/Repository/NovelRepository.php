<?php

namespace App\Repository;

use App\Entity\Novel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Novel>
 *
 * @method Novel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Novel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Novel[]    findAll()
 * @method Novel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NovelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Novel::class);
    }

    public function add(Novel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Novel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getLatestNovels(): array
    {
        return $this
            ->createQueryBuilder('n')
            ->leftJoin('n.chapters', 'c')
            ->andWhere('n.featured = :featured')
            ->setParameter('featured', true)
            ->orderBy('MAX(c.createdAt)', 'DESC')
            ->setMaxResults(5)
            ->groupBy('n.id')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getNovelsQB(): QueryBuilder
    {
        return $this
            ->createQueryBuilder('n')
            ->orderBy('n.createdAt', 'DESC')
            ->leftJoin('n.chapters', 'c')
            ->addSelect('c')
        ;
    }

    public function getNovel(int $id): ?Novel
    {
        return $this
            ->createQueryBuilder('n')
            ->leftJoin('n.chapters', 'c')
            ->addSelect('c')
            ->andWhere('n.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
