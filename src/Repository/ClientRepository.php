<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Conference;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public const PAGINATOR_PER_PAGE = 2;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }
    //composer require beberlei/DoctrineExtensions
    //SELECT * FROM `client` WHERE MONTH(`date`)=12 AND YEAR(`date`)=2021
    public function findClientsFromThisDate($month,$year)
    {
        return $this->createQueryBuilder('client')
        ->where("MONTH(client.date) = :month")
        ->setParameter('month', $month)
        ->andWhere("YEAR(client.date) = :year")
        ->setParameter('year', $year)
        ->getQuery()
        ->getResult()
        ;
    }

    public function findClientsMonth($year = null, $janvier = null ,$fevrier = null ,$mars = null ,$avril  = null ,$mai  = null ,$juin  = null ,$juillet  = null ,$aout  = null ,$septembre  = null , $octobre  = null,$novembre  = null ,$decembre  = null )
    {
        
        return $this->createQueryBuilder('client')
            ->orWhere("MONTH(client.date) = :janvier")
            ->setParameter(':janvier', $janvier)
            ->orWhere("MONTH(client.date) = :fevrier")
            ->setParameter(':fevrier', $fevrier)
            ->orWhere("MONTH(client.date) = :mars")
            ->setParameter(':mars', $mars)
            ->orWhere("MONTH(client.date) = :avril")
            ->setParameter(':avril', $avril)
            ->orWhere("MONTH(client.date) = :mai")
            ->setParameter(':mai', $mai)
            ->orWhere("MONTH(client.date) = :juin")
            ->setParameter(':juin', $juin)
            ->orWhere("MONTH(client.date) = :juillet")
            ->setParameter(':juillet', $juillet)
            ->orWhere("MONTH(client.date) = :aout")
            ->setParameter(':aout', $aout)
            ->orWhere("MONTH(client.date) = :septembre")
            ->setParameter(':septembre', $septembre)
            ->orWhere("MONTH(client.date) = :octobre")
            ->setParameter(':octobre', $octobre)
            ->orWhere("MONTH(client.date) = :novembre")
            ->setParameter(':novembre', $novembre)
            ->orWhere("MONTH(client.date) = :decembre")
            ->setParameter(':decembre', $decembre)
            ->andWhere("YEAR(client.date) = :year")
            ->setParameter(':year', $year)
            ->getQuery()
            ->getResult()
        ;
        
        
            
    }
    //SELECT MAX(YEAR(`date`)) FROM `client`
    public function findMaxYears()
    {
        return $this->createQueryBuilder('client')
            ->select('client, MAX(YEAR(client.date))')
            ->getQuery()
            ->getResult()
        ;
    }
    public function findMinYears()
    {
        return $this->createQueryBuilder('client')
            ->select('client, MIN(YEAR(client.date))')
            ->getQuery()
            ->getResult()
        ;
    }
    public function findClientsYear($year)
    {
        return $this->createQueryBuilder('client')
            ->orWhere("YEAR(client.date) = :year")
            ->setParameter(':year', $year)
            ->getQuery()
            ->getResult()
        ;
    }
    /*public function getCommentPaginator(Client $conference, int $offset): Paginator
    {
        $query = $this->createQueryBuilder('c')
            ->andWhere('c.conference = :conference')
            ->setParameter('conference', $conference)
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery()
        ;
            return new Paginator($query);
    }*/

    // /**
    //  * @return Client[] Returns an array of Client objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Client
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    
}
