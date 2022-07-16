<?php

namespace App\Repository;

use App\Entity\Jawaban;
use App\Entity\Pertanyaan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pertanyaan>
 *
 * @method Pertanyaan|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pertanyaan|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pertanyaan[]    findAll()
 * @method Pertanyaan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PertanyaanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pertanyaan::class);
    }

    public function add(Pertanyaan $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Pertanyaan $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Pertanyaan[] Returns an array of Pertanyaan objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Pertanyaan
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findBySearch($value)
    {
        $queryBuilder = $this->createQueryBuilder('p')
                        ->where('p.judulTanya LIKE :value')
                        ->orWhere('p.tanya LIKE :value')
                        ->setParameters([
                            'value' => '%' . $value['cari'] . '%'
                        ])
                        ->getQuery()
                        ->getResult();

    return $queryBuilder;
    }
}
