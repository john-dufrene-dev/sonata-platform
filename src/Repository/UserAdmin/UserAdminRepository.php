<?php

namespace App\Repository\UserAdmin;

use App\Entity\UserAdmin\UserAdmin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method UserAdmin|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAdmin|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAdmin[]    findAll()
 * @method UserAdmin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserAdminRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAdmin::class);
    }

    // /**
    //  * @return UserAdmin[] Returns an array of Test objects
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
    public function findOneBySomeField($value): ?UserAdmin
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
