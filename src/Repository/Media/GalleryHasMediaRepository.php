<?php

namespace App\Repository\Media;

use App\Entity\Media\GalleryHasMedia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method GalleryHasMedia|null find($id, $lockMode = null, $lockVersion = null)
 * @method GalleryHasMedia|null findOneBy(array $criteria, array $orderBy = null)
 * @method GalleryHasMedia[]    findAll()
 * @method GalleryHasMedia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GalleryHasMediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GalleryHasMedia::class);
    }

    // /**
    //  * @return GalleryHasMedia[] Returns an array of Test objects
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
    public function findOneBySomeField($value): ?GalleryHasMedia
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
