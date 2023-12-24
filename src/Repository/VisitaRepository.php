<?php

namespace App\Repository;

use App\Entity\Visita;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Visita>
 *
 * @method Visita|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visita|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visita[]    findAll()
 * @method Visita[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visita::class);
    }

    /**
     * @return Visita[] Returns an array of Visita objects
     */
    public function findByViews(): array
    {
        return $this->createQueryBuilder('v')
            ->join('v.contenido', 'c')
            ->select('c.id', 'count(v.id) as viewsCount')
            ->groupBy('c.id')
            ->orderBy('viewsCount', 'DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Visita[] Returns an array of Visita objects
     */
    public function findByMostPopular(): array
    {
        return $this->createQueryBuilder('v')
            ->join('v.contenido', 'c')
            ->select("c.id")
            ->groupBy('c.id, v.fecha')
            ->groupBy('v.contenido, v.fecha')
            ->orderBy('COUNT(v.id)', 'DESC')
            ->addOrderBy('v.fecha', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?Visita
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
