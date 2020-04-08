<?php

namespace App\Repository\UserAdmin;

use App\Entity\UserAdmin\AdminGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AdminGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdminGroup[]    findAll()
 * @method AdminGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdminGroup::class);
    }

    // /**
    //  * @return AdminGroup[] Returns an array of Test objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdminGroup
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
