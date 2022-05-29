<?php

namespace App\Repository;

use App\Entity\CommentSection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommentSection>
 *
 * @method CommentSection|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentSection|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentSection[]    findAll()
 * @method CommentSection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentSectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentSection::class);
    }

    public function add(CommentSection $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CommentSection $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
