<?php

namespace App\Repository;

use App\Entity\Beach;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Beach|null find($id, $lockMode = null, $lockVersion = null)
 * @method Beach|null findOneBy(array $criteria, array $orderBy = null)
 * @method Beach[]    findAll()
 * @method Beach[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BeachRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Beach::class);
    }

    // /**
    //  * @return Beach[] Returns an array of Beach objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Beach
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
