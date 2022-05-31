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
use Symfony\Component\Validator\Constraints\DateInterval;

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
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Intl\Countries;

//\Locale::setDefault('fr');
date_default_timezone_set('Europe/Paris');
/**
 * @Route("/client")
 */
class ClientController extends AbstractController
{
    /**
     * @Route("/index/{month}/{year}/{method}", name="client_index", methods={"GET","POST"})
     */
    public function index( $month = null, $year = null , $method = null ,ClientRepository $clientRepository, AppareilRepository $appareilRepository, UserRepository $userRepository, EtatRepository $etatRepository): Response
    {
        
        if($month != null && $year != null ){
            $date = new \DateTime($year.'-'.$month.'-01' );
            $month = $date->format('m');
            $year =  $date->format('Y');   
            $monthLetter = $date->format('F');
            if($method == 'suivant'){
                $date = new \DateTime($year.'-'.$month.'-01');
                $date->modify('+1 month');
                $month = $date->format('m');
                $year = $date->format('Y');
                $monthLetter = $date->format('F');
            }
            elseif($method == 'precedent'){
                $date = new \DateTime($year.'-'.$month.'-01');
                $date->modify('-1 month');
                $month = $date->format('m');
                $year = $date->format('Y');
                $monthLetter = $date->format('F');
            }
        }
        else{
            $date = new \DateTime();
            $month = $date->format('m');
            $year = $date->format('Y');
            $monthLetter = $date->format('F');
        }
        $translator = new Translator('fr_FR');
        $translator->addLoader('array', new ArrayLoader());
        $translator->addResource('array', [
            'January' => 'Janvier',
            'February' => 'Février',
            'March' => 'Mars',
            'April' => 'Avril',
            'May'=>'Mai',
            'June'=>'Juin',
            'July'=>'Juillet',
            'August'=>'Août',
            'September'=>'Septembre',
            'October'=>'Octobre',
            'November'=>'Novembre', 
            'December'=>'Décembre',
        ], 'fr_FR');
        

        return $this->renderForm('client/index.html.twig', [
            'clients' => $clientRepository->findClientsFromThisDate($month,$year),
            'appareils' => $appareilRepository->findAll(),
            'month' => $month,
            'year'=>$year,
            'users'=>$userRepository->findAll(),
            'etats'=>$etatRepository->findAll(),
            'titleMonth' => $translator->trans($monthLetter)
        ]);
    }
    /**
     * @Route("/show/all", name="client_show_all",methods={"GET","POST"})
     */
    public function show_all(Request $request, ClientRepository $clientRepository, AppareilRepository $appareilRepository)
    {
        $years = [];
        $yearsRequest = [];
        $maxYear = $clientRepository->findMaxYears();
        $minYear = $clientRepository->findMaxYears();
        $clients = []; 

        $janvier = $request->request->get('janvier');
        $fevrier = $request->request->get('fevrier');
        $mars = $request->request->get('mars');
        $avril = $request->request->get('avril');
        $mai = $request->request->get('mai');
        $juin = $request->request->get('juin');
        $juillet = $request->request->get('juillet');
        $aout = $request->request->get('aout');
        $septembre = $request->request->get('septembre');
        $octobre = $request->request->get('octobre');
        $novembre = $request->request->get('novembre');
        $decembre = $request->request->get('decembre'); 
        for ($i=$minYear[0][1]-1; $i < $maxYear[0][1]+1 ; $i++) { 
            array_push($years,$i);
            if($request->request->get($i)){
                array_push($yearsRequest,$request->request->get($i));
            }
        }
        //********************Mois************ */
        if (!$janvier && !$fevrier && !$mars && !$avril && !$mai && !$juin && !$juillet && !$aout && !$septembre && !$octobre && !$novembre && !$decembre){
            
            if (count($yearsRequest)==0 ) {
                $clients =  $clientRepository->findAll();
            }
            else{
                foreach ($yearsRequest as  $yearRequest) {
                    $clients += $clientRepository->findClientsYear($yearRequest);
                }
            }
        }
        else{
            if ( count($yearsRequest)==0) {
                $clients = $clientRepository->findClientsMonth(null,$janvier,$fevrier,$mars,$avril,$mai,$juin,$juillet,$aout,$septembre,$octobre,$novembre,$decembre);
            }
            else {
                foreach ($yearsRequest as  $yearRequest) {
                    $clients += $clientRepository->findClientsMonth($yearRequest,$janvier,$fevrier,$mars,$avril,$mai,$juin,$juillet,$aout,$septembre,$octobre,$novembre,$decembre);
                }
            }
        }

        $etats = ["Livré", "Pris en charge"];
        dump($clientRepository->findClientsEtat('Livré'));
        $months = ['janvier'=>$janvier,'fevrier'=> $fevrier,'mars'=> $mars,'avril'=> $avril,'mai'=> $mai, 'juin'=>$juin, 'juillet'=>$juillet, 'aout'=>$aout, 'septembre'=>$septembre, 'octobre'=>$octobre, 'novembre'=>$novembre, 'decembre'=>$decembre];
        $chekeds = [];
        foreach ($months as $key => $month) {
            $cheked =$months[$key]!=null ? 'checked' : '';
            $chekeds[$key] = $cheked;
        }
        return $this->render('client/show_all.html.twig', [
            'clients' =>  $clients ,
            'appareils' => $appareilRepository->findAll(),
            'checkds' => $chekeds,
            'years' =>$years,
            'yearsCheckds' =>$yearsRequest,
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
        $taches = $appareil->getTaches();
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
            'taches'=> $taches,
            //client.appareil.editeur.etat.statut
        ]);
        
    }
    
    /**
     * @Route("/{id}/mail", name="client_mail",methods={"GET","POST"})
     */
    public function mail(Client $client, MailerInterface $mailer,Request $request ): Response
    {
        $etat = $client->getAppareil()->getEditeur()->getEtat();
        $appareil = $client->getAppareil();
        //Envoie un mail
        if($etat->getStatut() == 'Pris en charge'){
            $path = '../public/images/mail/encharge.png';
        }
        elseif($etat->getStatut() == 'Devis envoyée'){
            $path = '../public/images/mail/devis.png';
        }
        elseif($etat->getStatut() == 'En attente de pièce'){
            $path = '../public/images/mail/piece.png';
        }
        elseif($etat->getStatut() == 'En cours de réparation'){
            $path = '../public/images/mail/reparation.png';
        }
        elseif($etat->getStatut() ==  'Prêt à être récupéré'){
            $path = '../public/images/mail/pret.png';
        }
        elseif($etat->getStatut() == 'Livré'){
            $path = '../public/images/mail/livre.png';
        }
        $data = (new TemplatedEmail())
        ->from((new Address('noreplyazertyfrance@gmail.com','AZERTY Solutions Informatiques')))
        ->to(new Address($client->getMail()))
        ->cc('noreplyazertyfrance@gmail.com','contact@azertyfrance.fr')
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        ->replyTo('contact@azertyfrance.fr')
        //->priority(Email::PRIORITY_HIGH)
        ->embedFromPath('../public/images/mail/asi.png', 'asi')
        ->embedFromPath('../public/images/mail/wathsapp.svg.png', 'whatsapp')
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
      //return $this->json(['etat' => $etat->getStatut() , 'lastEdit'=> $etat->getDate()]);
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
            $entityManager->remove($client->getAppareil());
            $taches = $client->getAppareil()->getTaches();
            foreach($taches as $tache){
                $entityManager->remove($tache);
            }
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
