<?php

namespace App\Repository\UserAdmin;

use App\Entity\UserAdmin\GroupAdmin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method GroupAdmin|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupAdmin|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupAdmin[]    findAll()
 * @method GroupAdmin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupAdminRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupAdmin::class);
    }

    // /**
    //  * @return GroupAdmin[] Returns an array of Test objects
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
    public function findOneBySomeField($value): ?GroupAdmin
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
