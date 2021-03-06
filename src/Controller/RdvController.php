<?php

namespace App\Controller;

use App\Entity\Rdv;
use App\Form\RdvType;
use App\Repository\RdvRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

/**
 * @Route("/rdv")
 */
class RdvController extends AbstractController
{
    /**
     * @Route("/interne", name="app_rdv_index", methods={"GET"})
     */
    public function index(Request $request,RdvRepository $rdvRepository): Response
    {
        return $this->render('rdv/index.html.twig', [
            'rdvs' => $rdvRepository->findAll(),
        ]);
    }

    /**
     * @Route("/public/new", name="app_rdv_new", methods={"GET", "POST"})
     */
    public function new(Request $request, RdvRepository $rdvRepository,MailerInterface $mailer): Response
    {
        $rdv = new Rdv();
        $form = $this->createForm(RdvType::class, $rdv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = (new TemplatedEmail())
                ->from((new Address('noreplyazertyfrance@gmail.com','AZERTY Solutions Informatiques')))
                ->to(new Address($rdv->getMail()))
                ->bcc(new Address('contact@azertyfrance.fr'))
                ->cc('noreplyazertyfrance@gmail.com','contact@azertyfrance.fr')
                ->embedFromPath('../public/images/mail/wathsapp.svg.png', 'whatsapp')
                ->replyTo('contact@azertyfrance.fr')
                //->cc(new Address('contact@azertyfrance.fr'))
                ->subject('Intervention à domicile')
                ->htmlTemplate('emails/rdv/mail_rdv_reception.html.twig')
                ->context([
                    'id' => $rdv->getId(),
                    'nom' => $rdv->getNom(),
                    'prenom' => $rdv->getPrenom(),
                    'mail' => $rdv->getMail(),
                    'tel' => $rdv->getTel(),
                    'adresse' => $rdv->getAdresse(),
                    'cp' => $rdv->getCp(),
                    'date' => $rdv->getDate(),
                    'message' => $rdv->getMessage(),
            ])
            ;
            $mailer->send($data);
            $rdvRepository->add($rdv);
            $this->addFlash('sucessRdv', 'Nous avons bien reçu votre demande de rendez-vous à domicile et nous vous recontacterons le plus rapidement possible pour confirmez cette demande. Surveiller votre boite mail et votre téléphone !');
        }

        return $this->renderForm('rdv/client/new.html.twig', [
            'rdv' => $rdv,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/public/{id}", name="app_rdv_show", methods={"GET"})
     */
    public function show(Rdv $rdv): Response
    {
        return $this->render('rdv/client/show.html.twig', [
            'rdv' => $rdv,
        ]);
    }

    /**
     * @Route("/interne/{id}/edit", name="app_rdv_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Rdv $rdv, RdvRepository $rdvRepository): Response
    {
        $form = $this->createForm(RdvType::class, $rdv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rdvRepository->add($rdv);
            return $this->redirectToRoute('app_rdv_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rdv/edit.html.twig', [
            'rdv' => $rdv,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/interne/{id}", name="app_rdv_delete", methods={"POST"})
     */
    public function delete(Request $request, Rdv $rdv, RdvRepository $rdvRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rdv->getId(), $request->request->get('_token'))) {
            $rdvRepository->remove($rdv);
        }

        return $this->redirectToRoute('app_rdv_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/interne/confirmer/{id}", name="app_rdv_confirmer", methods={"POST"})
     */
    public function confirmation(Request $request, Rdv $rdv, RdvRepository $rdvRepository): Response
    {
        $rdv->setConfirmer(1);
        $rdvRepository->add($rdv);
        return $this->redirectToRoute('app_rdv_mail', ['id'=>$rdv->getId()], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/interne/{id}/edit/date", name="app_rdv_edit_date", methods={"POST"})
     */
    public function editDate(Request $request, Rdv $rdv, RdvRepository $rdvRepository): Response
    {
        $rdv->setConfirmer(2);
        $date = new \DateTime($request->request->get('datetime'));
        $rdv->setDate($date);
        $rdvRepository->add($rdv);
        return $this->redirectToRoute('app_rdv_mail', ['id'=>$rdv->getId()], Response::HTTP_SEE_OTHER);
    }

     /**
     * @Route("/public/confirmer/{id}/mail", name="app_rdv_mail", methods={"GET"})
     */
    public function mail(Request $request, Rdv $rdv,MailerInterface $mailer, RdvRepository $rdvRepository): Response
    {
        if($rdv->getConfirmer()==2){
            $chemein = 'emails/rdv/redemande_rdv.html.twig';
        }
        elseif($rdv->getConfirmer()==4){
            $chemein = 'emails/rdv/redemande_client_rdv.html.twig';
        }
        elseif($rdv->getConfirmer()==5){
            $chemein = 'emails/rdv/annulation_client_rdv.html.twig';
        }
        else{
            $chemein = 'emails/rdv/confirmation_rdv.html.twig';
        }
        //Envoie un mail
        $data = (new TemplatedEmail())
        ->from((new Address('noreplyazertyfrance@gmail.com','AZERTY Solutions Informatiques')))
        ->to(new Address($rdv->getMail()))
        ->bcc(new Address('contact@azertyfrance.fr'))
        ->cc('noreplyazertyfrance@gmail.com','contact@azertyfrance.fr')
        ->embedFromPath('../public/images/mail/wathsapp.svg.png', 'whatsapp')
        ->replyTo('contact@azertyfrance.fr')
        //->cc(new Address('contact@azertyfrance.fr'))
        ->subject('Intervention à domicile')
        ->htmlTemplate($chemein)
        ->context([
            'id' => $rdv->getId(),
            'nom' => $rdv->getNom(),
            'prenom' => $rdv->getPrenom(),
            'mail' => $rdv->getMail(),
            'tel' => $rdv->getTel(),
            'adresse' => $rdv->getAdresse(),
            'cp' => $rdv->getCp(),
            'date' => $rdv->getDate(),
            'message' => $rdv->getMessage(),
        ])
        ;
        $mailer->send($data);
        if($rdv->getConfirmer()==3 || $rdv->getConfirmer()==4 || $rdv->getConfirmer()==5){
            return $this->redirectToRoute('app_rdv_show', ['id'=>$rdv->getId()], Response::HTTP_SEE_OTHER);
        }
        else{
            return $this->redirectToRoute('app_rdv_index', [], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/interne/completed/{id}", name="app_rdv_checked", methods={"POST"})
     */
    public function completed(Request $request, Rdv $rdv, RdvRepository $rdvRepository): Response
    {
        if($rdv->getDo()){
            $rdv->setDo(0);
        }
        else{
            $rdv->setDo(1);
        }
        $rdvRepository->add($rdv);
        return $this->redirectToRoute('app_rdv_index', [], Response::HTTP_SEE_OTHER);
    }
    
    /**
     * @Route("/public/confirmer/{id}", name="app_rdv_confirmer_client", methods={"POST"})
     */
    public function confirmationClient(Request $request, Rdv $rdv, RdvRepository $rdvRepository): Response
    {
        $rdv->setConfirmer(3);
        $rdvRepository->add($rdv);
        return $this->redirectToRoute('app_rdv_mail', ['id'=>$rdv->getId()], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/public/{id}/edit/date", name="app_rdv_edit_date_client", methods={"POST"})
     */
    public function suggestionDateClient(Request $request, Rdv $rdv, RdvRepository $rdvRepository): Response
    {
        $rdv->setConfirmer(4);
        $date = new \DateTime($request->request->get('datetime'));
        $rdv->setDate($date);
        $rdvRepository->add($rdv);
        return $this->redirectToRoute('app_rdv_mail', ['id'=>$rdv->getId()], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/public/annuler/{id}", name="app_rdv_annuler_client", methods={"POST"})
     */
    public function annulationClient(Request $request, Rdv $rdv, RdvRepository $rdvRepository): Response
    {
        $rdv->setConfirmer(5);
        $rdv->setDo(1);
        $rdvRepository->add($rdv);
        return $this->redirectToRoute('app_rdv_mail', ['id'=>$rdv->getId()], Response::HTTP_SEE_OTHER);
    }
}
