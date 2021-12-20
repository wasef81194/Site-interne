<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Editeur;

use App\Form\FormClientType;
use App\Repository\ClientRepository;
use App\Entity\Appareil;

use App\Form\FormAppareilType;
use App\Repository\AppareilRepository;
use App\Form\FormEditeur;
use App\Form\FormDepot;
use Symfony\Component\Validator\Constraints\DateTime;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\EtatRepository;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

//mail
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
date_default_timezone_set('Europe/Paris');
/**
 * @Route("/client")
 */
class ClientController extends AbstractController
{
    /**
     * @Route("/", name="client_index", methods={"GET"})
     */
    public function index(ClientRepository $clientRepository, AppareilRepository $appareilRepository): Response
    {
        return $this->render('client/index.html.twig', [
            'clients' => $clientRepository->findAll(),
            'appareils' => $appareilRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="client_new", methods={"GET","POST"})
     */
    public function new(Request $request,EtatRepository $etatRepository, UserRepository $userRepository): Response
    {
        $client = new Client();
        $appareil = new Appareil();
        $form = $this->createForm(FormDepot::class, ['client' => $client, 'appareil' => $appareil]);
         //$form = $this->createForm(FormClientType::class,$client);
        $editeur = new Editeur();
        $form -> handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
          //  $client->setDate(new \DateTime() );
            $appareil->setClient($client);
            $client->setDate(new \DateTime());
            $appareil->setMarque(strtoupper($appareil->getMarque()));
            $appareil->setModele(strtoupper($appareil->getModele()));
            $appareil->setNs(strtoupper($appareil->getNs()));
            $appareil->setChargeur(strtoupper($appareil->getChargeur()));
            $appareil->setPrblm(strtoupper($appareil->getPrblm()));
            $editeur->setEtat($etatRepository->findEtatWhereIsNull('')); 
            $editeur->setUser($userRepository->findUserWhereIsNull(''));
            $editeur->setDate(new \DateTime());
            $appareil->setEditeur($editeur);    
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($appareil);
            $entityManager->persist($client);
            $entityManager->persist($editeur);
            $entityManager->flush(); 
            return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/show", name="client_show",methods={"GET","POST"})
     */
    public function show(Client $client, Request $request ): Response
    {
        $appareil = $client->getAppareil();
        $editeur = new Editeur();
        $etat = $client->getAppareil()->getEditeur()->getEtat();
        $user = $client->getAppareil()->getEditeur()->getUser();
        $formEdit = $this->createForm(FormEditeur::class, $editeur);
        $formEdit->handleRequest($request);

        if ($formEdit->isSubmitted() && $formEdit->isValid()) {
            $formEdit->getData();
            $editeur->setDate(new \DateTime() );
            $appareil->setEditeur($editeur);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($editeur);
            $entityManager->persist($etat);
            $entityManager->persist($user);
            $entityManager->flush(); 
            if($formEdit->get("mail")->getData()!=null){
                return $this->redirectToRoute('client_mail', ['id'=>$client->getId()], Response::HTTP_SEE_OTHER);
            }
            else{
                return $this->redirectToRoute('client_show', ['id'=>$client->getId()], Response::HTTP_SEE_OTHER);
            }
            
        }

        return $this->renderForm('client/show.html.twig', [
            'client' => $client,
            'appareil' => $appareil,
            'etat' => $etat,
            'user' => $user,
            'editeur' =>$appareil->getEditeur(),
            'form' => $formEdit,
            //client.appareil.editeur.etat.statut
        ]);
    }
    /**
     * @Route("/{id}/mail", name="client_mail",methods={"GET","POST"})
     */
    public function mail(Client $client, MailerInterface $mailer): Response
    {
        $etat = $client->getAppareil()->getEditeur()->getEtat();
        $appareil = $client->getAppareil();
        //Envoie un mail
        if($etat->getStatut() == 'Pris en charge'){
            $path = '..\public\images\mail\encharge.png';
        }
        elseif($etat->getStatut() == 'Devis envoyée'){
            $path = '..\public\images\mail\devis.png';
        }
        elseif($etat->getStatut() == 'En attente de pièce'){
            $path = '..\public\images\mail\piece.png';
        }
        elseif($etat->getStatut() == 'En cours de réparation'){
            $path = '..\public\images\mail\reparation.png';
        }
        elseif($etat->getStatut() ==  'Prêt à être récupéré'){
            $path = '..\public\images\mail\pret.png';
        }
        elseif($etat->getStatut() == 'Livré'){
            $path = '..\public\images\mail\livre.png';
        }
        $data = (new TemplatedEmail())
        ->from((new Address('noreplyazerty@gmail.com','AZERTY Solutions Informatiques')))
        ->to(new Address($client->getMail()))
        //->bcc(new Address('contact@azertyfrance.fr'))
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        ->replyTo('contact@azertyfrance.fr')
        //->priority(Email::PRIORITY_HIGH)
        ->embedFromPath('..\public\images\mail\asi.png', 'asi')
        ->embedFromPath($path, 'etat')
        ->subject('Etat de votre appareil')
        ->htmlTemplate('emails/mailEtat.html.twig')
        ->context([
            'personne' => $client->getPersonne(),
            'nom' => $client->getNom(),
            'prenom' => $client->getPrenom(),
            'mail' => $client->getMail(),
            'tel' => $client->getTel(),
            'rue' => $client->getRue(),
            'cp' => $client->getCp(),
            'ville' => $client->getVille(),
            'marque' => $appareil->getMarque(),
            'modele' => $appareil->getModele(),
            'ns' => $appareil->getNs(),
            'etat'=>$etat->getStatut(),
        ])
    ;
    $mailer->send($data);
    return $this->redirectToRoute('client_show', ['id'=>$client->getId()], Response::HTTP_SEE_OTHER);
    }
 
    /**
     * @Route("/{id}/edit", name="client_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Client $client): Response
    {
        $appareil = $client->getAppareil();
        $form = $this->createForm(FormDepot::class,['client'=>$client,'appareil'=>$appareil]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('client_show', ['id'=>$client->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    } 
    /**
     * @Route("/{id}", name="client_delete", methods={"POST"})
     */
    public function delete(Request $request, Client $client): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/new", name="client_clone", methods={"GET","POST"})
     */
    public function clone(Request $request, Client $client,EtatRepository $etatRepository, UserRepository $userRepository): Response
    {
        $clientA = $client;
        $appareilA = $client->getAppareil();

        $client = new Client();
        $appareil = new Appareil();
        $editeur = new Editeur();
        $editeur->setEtat($etatRepository->findEtatWhereIsNull('')); 
        $editeur->setUser($userRepository->findUserWhereIsNull(''));
        $editeur->setDate(new \DateTime() );
        $client->setDate(new \DateTime);
        $client->setPersonne($clientA->getPersonne());
        $client->setNom($clientA->getNom());
        $client->setPrenom($clientA->getPrenom());
        $client->setMail($clientA->getMail());
        $client->setTel($clientA->getTel());
        $client->setRue($clientA->getRue());
        $client->setMail($clientA->getMail());
        $client->setVille($clientA->getVille());
        $client->setCp($clientA->getCp());
        $appareil->setClient($client);
        $appareil->setMarque(strtoupper($appareilA->getMarque()));
        $appareil->setModele(strtoupper($appareilA->getModele()));
        $appareil->setNs(strtoupper($appareilA->getNs()));
        $appareil->setChargeur(strtoupper($appareilA->getChargeur()));
        $appareil->setPrblm(strtoupper($appareilA->getPrblm()));
        $appareil->setEditeur($editeur); 

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($appareil);
        $entityManager->persist($client);
        
        $entityManager->persist($editeur);
        $entityManager->flush(); 
        return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
      /*  if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);*/
    }
}
