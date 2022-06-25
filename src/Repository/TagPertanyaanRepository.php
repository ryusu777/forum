<?php

namespace App\Repository;

use App\Entity\TagPertanyaan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TagPertanyaan>
 *
 * @method TagPertanyaan|null find($id, $lockMode = null, $lockVersion = null)
 * @method TagPertanyaan|null findOneBy(array $criteria, array $orderBy = null)
 * @method TagPertanyaan[]    findAll()
 * @method TagPertanyaan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagPertanyaanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TagPertanyaan::class);
    }

    public function add(TagPertanyaan $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TagPertanyaan $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return TagPertanyaan[] Returns an array of TagPertanyaan objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TagPertanyaan
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
