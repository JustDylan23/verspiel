<?php

namespace App\Repository;

use App\Entity\Chapter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Chapter>
 *
 * @method Chapter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chapter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chapter[]    findAll()
 * @method Chapter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChapterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chapter::class);
    }

    public function add(Chapter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Chapter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLatest(): array
    {
        return $this
            ->createQueryBuilder('c')
            ->join('c.novel', 'n')
            ->addSelect('n')
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getChaptersQB(): QueryBuilder
    {
        return $this
            ->createQueryBuilder('c')
            ->join('c.novel', 'n')
            ->addSelect('n')
            ->orderBy('c.createdAt', 'DESC')
        ;
    }

    public function getChapter(int $id): ?Chapter
    {
        return $this
            ->createQueryBuilder('c')
            ->join('c.novel', 'n')
            ->addSelect('n')
            ->andWhere('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getNextChapter(Chapter $chapter): ?Chapter
    {
        return $this
            ->createQueryBuilder('c')
            ->andWhere('c.number > :number')
            ->setParameter('number', $chapter->getNumber())
            ->andWhere('c.novel = :novel')
            ->setParameter('novel', $chapter->getNovel())
            ->setMaxResults('1')
            ->orderBy('c.number', 'ASC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getPreviousChapter(Chapter $chapter): ?Chapter
    {
        return $this
            ->createQueryBuilder('c')
            ->andWhere('c.number < :number')
            ->setParameter('number', $chapter->getNumber())
            ->andWhere('c.novel = :novel')
            ->setParameter('novel', $chapter->getNovel())
            ->setMaxResults('1')
            ->orderBy('c.number', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getChaptersByNovel(int $id): array
    {
        return $this
            ->createQueryBuilder('c')
            ->andWhere('c.novel = :novel')
            ->setParameter('novel', $id)
            ->orderBy('c.number', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
