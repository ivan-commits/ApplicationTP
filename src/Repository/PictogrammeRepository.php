<?php

namespace App\Repository;

use App\Entity\Pictogramme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @method Pictogramme|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pictogramme|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pictogramme[]    findAll()
 * @method Pictogramme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PictogrammeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pictogramme::class);
    }


    /**
     * @return Query
     */
    public function findAllQuery() : Query
    {
        return $this->createQueryBuilder('p')
            ->getQuery();
    }

    /*
    public function findOneBySomeField($value): ?Pictogramme
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
