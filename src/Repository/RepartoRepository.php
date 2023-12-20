<?php

namespace App\Repository;

use App\Entity\Reparto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reparto>
 *
 * @method Reparto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reparto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reparto[]    findAll()
 * @method Reparto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepartoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reparto::class);
    }

//    /**
//     * @return Reparto[] Returns an array of Reparto objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reparto
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
