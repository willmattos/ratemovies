<?php

namespace App\Repository;

use App\Entity\Valora;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Valora>
 *
 * @method Valora|null find($id, $lockMode = null, $lockVersion = null)
 * @method Valora|null findOneBy(array $criteria, array $orderBy = null)
 * @method Valora[]    findAll()
 * @method Valora[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ValoraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Valora::class);
    }

//    /**
//     * @return Valora[] Returns an array of Valora objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Valora
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
