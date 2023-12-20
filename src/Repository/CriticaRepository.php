<?php

namespace App\Repository;

use App\Entity\Critica;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Critica>
 *
 * @method Critica|null find($id, $lockMode = null, $lockVersion = null)
 * @method Critica|null findOneBy(array $criteria, array $orderBy = null)
 * @method Critica[]    findAll()
 * @method Critica[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CriticaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Critica::class);
    }

//    /**
//     * @return Critica[] Returns an array of Critica objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Critica
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
