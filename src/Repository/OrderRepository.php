<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function findOne($id)
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.deliveryPoint', 'd')
            ->innerJoin('o.orderProducts', 'op')
            ->innerJoin('op.product', 'p')
            ->innerJoin('o.user', 'u')
            ->addSelect('op')
            ->addSelect('p')
            ->addSelect('d')
            ->addSelect('u')
            ->where('o.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;

    }

    public function findAll()
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.orderProducts', 'op')
            ->innerJoin('op.product', 'p')
            ->innerJoin('o.deliveryPoint', 'd')
            ->innerJoin('o.user', 'u')
            ->addSelect('op')
            ->addSelect('p')
            ->addSelect('d')
            ->addSelect('u')
            ->orderBy('o.deliveryTime', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function countAllOrderOnDeliveryPoint()
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.deliveryPoint', 'd')
            ->select('d.name')
            ->addSelect('d.id')
            ->addSelect('d.location')
            ->addselect('COUNT(o.id) AS orderCount')
            ->where('o.status < 3')
            ->groupBy('o.deliveryPoint')
            ->orderBy('COUNT(o.id)')
            ->getQuery()
            ->getResult();
        ;
    }

    public function findAllActive($deliveryPoint = null)
    {
        $qb = $this->createQueryBuilder('o')
            ->innerJoin('o.deliveryPoint', 'd')
            ->addSelect('d')
        ;

        if ($deliveryPoint) {
            $qb->where('o.deliveryPoint = :deliveryPoint')
                ->setParameter('deliveryPoint', $deliveryPoint);
        }
            
        return $qb->andWhere('o.status < 3')
            ->orderBy('o.deliveryTime', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Order[] Returns an array of Order objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
