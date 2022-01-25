<?php

namespace App\Repository;

use App\Entity\Prioritaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Prioritaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prioritaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prioritaire[]    findAll()
 * @method Prioritaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrioritaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prioritaire::class);
    }

    // /**
    //  * @return Prioritaire[] Returns an array of Prioritaire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Prioritaire
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
