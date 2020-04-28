<?php

namespace App\Repository\Redirect;

use Doctrine\ORM\Query;
use App\Entity\Redirect\Redirect;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Redirect|null find($id, $lockMode = null, $lockVersion = null)
 * @method Redirect|null findOneBy(array $criteria, array $orderBy = null)
 * @method Redirect[]    findAll()
 * @method Redirect[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RedirectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Redirect::class);
    }

    public function findRedirect(string $source): ?array
    {
        return $this->createQueryBuilder('r')
            ->select('r.destination, r.httpCode')
            ->where('r.source = :source')
            ->andWhere('r.publish = :publish')
            ->setParameter('source', $source)
            ->setParameter('publish', true)
            ->getQuery()
            ->getOneOrNullResult(Query::HYDRATE_SCALAR);
    }

    // /**
    //  * @return Redirect[] Returns an array of Redirect objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Redirect
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
