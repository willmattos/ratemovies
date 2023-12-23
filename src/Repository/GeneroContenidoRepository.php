<?php

namespace App\Repository;

use App\Entity\GeneroContenido;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GeneroContenido>
 *
 * @method GeneroContenido|null find($id, $lockMode = null, $lockVersion = null)
 * @method GeneroContenido|null findOneBy(array $criteria, array $orderBy = null)
 * @method GeneroContenido[]    findAll()
 * @method GeneroContenido[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GeneroContenidoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GeneroContenido::class);
    }

//    /**
//     * @return GeneroContenido[] Returns an array of GeneroContenido objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GeneroContenido
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
