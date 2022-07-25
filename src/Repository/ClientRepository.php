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

    public function findClientsMonth( $year = null , $janvier = null ,$fevrier = null ,$mars = null ,$avril  = null ,$mai  = null ,$juin  = null ,$juillet  = null ,$aout  = null ,$septembre  = null , $octobre  = null,$novembre  = null ,$decembre  = null )
    {

        return $this->createQueryBuilder('client')
            ->Join("client.appareil", "appareil")
            ->Join("appareil.editeur", "editeur")
            ->Join("editeur.etat", "etat")
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
    public function findClientsEtat($etat){
        return $this->createQueryBuilder('client')
            ->Join("client.appareil", "appareil")
            ->Join("appareil.editeur", "editeur")
            ->Join("editeur.etat", "etat")
            ->andWhere("etat.id = :id")
            ->setParameter(':id', $etat)
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
    /*SELECT * FROM client WHERE nom LIKE '%al%' OR prenom LIKE '%al%';*/
    public function findClients($recherche)
    {
        $recherche ='%'.$recherche.'%';
        return $this->createQueryBuilder('client')
            ->Join("client.appareil", "appareil")
            ->Where("client.nom  LIKE :recherche")
            ->OrWhere("client.prenom LIKE :recherche")
            ->OrWhere("client.mail LIKE :recherche")
            ->OrWhere("client.tel LIKE :recherche")
            ->OrWhere("client.rue LIKE :recherche")
            ->OrWhere("client.ville LIKE :recherche")
            ->OrWhere("client.cp LIKE :recherche")

            ->OrWhere("appareil.marque LIKE :recherche")
            ->OrWhere("appareil.modele LIKE :recherche")
            ->OrWhere("appareil.ns LIKE :recherche")
            ->OrWhere("appareil.mdp LIKE :recherche")
            ->OrWhere("appareil.prblm LIKE :recherche")
            ->setParameter(':recherche', $recherche)
            ->getQuery()
            ->getResult()
        ;
    }

    //curl -X POST "https://axonaut.com/api/v2/employees" -H  "accept: application/json" -H  "userApiKey: a4df1357607aac071de4a6b49e458398" -H  "Content-Type: application/json" -d "{  \"id\": 8671255,   \"gender\": 2,   \"firstname\": \"alexandra\",   \"lastname\": \"Wasef\",   \"email\": \"test@gmail.com\",    \"phone_number\": \"0620981468\",    \"cellphone_number\": \"0134370331\",    \"job\": null,    \"is_billing_contact\": false,    \"company_id\": 977441,    \"custom_fields\": []}"
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
