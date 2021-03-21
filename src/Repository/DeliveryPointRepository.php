<?php

namespace App\Repository;

use App\Entity\DeliveryPoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DeliveryPoint|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeliveryPoint|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeliveryPoint[]    findAll()
 * @method DeliveryPoint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeliveryPointRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeliveryPoint::class);
    }

    // /**
    //  * @return DeliveryPoint[] Returns an array of DeliveryPoint objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DeliveryPoint
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
