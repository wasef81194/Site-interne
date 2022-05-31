<?php

namespace App\Controller;

use App\Entity\Appel;
use App\Form\AppelType;
use App\Repository\AppelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//mail
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

/**
 * @Route("/appel")
 */
class AppelController extends AbstractController
{
    /**
     * @Route("/interne", name="app_appel_index", methods={"GET"})
     */
    public function index(AppelRepository $appelRepository): Response
    {
        return $this->render('appel/index.html.twig', [
            'appels' => $appelRepository->findAll(),
        ]);
    }

    /**
     * @Route("/public/new", name="app_appel_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MailerInterface $mailer, AppelRepository $appelRepository): Response
    {
        $appel = new Appel();
        $form = $this->createForm(AppelType::class, $appel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Envoie un mail
            $data = (new TemplatedEmail())
                ->from((new Address('noreplyazertyfrance@gmail.com','AZERTY Solutions Informatiques')))
                ->to(new Address($appel->getMail()))
                ->bcc(new Address('contact@azertyfrance.fr'))
                ->cc('noreplyazertyfrance@gmail.com','contact@azertyfrance.fr')
                ->embedFromPath('../public/images/mail/wathsapp.svg.png', 'whatsapp')
                ->replyTo('contact@azertyfrance.fr')
                ->subject('Demande à être rappelé')
                ->htmlTemplate('emails/appel/mail_rappel.html.twig')
                ->context([
                    'nom' => $appel->getNom(),
                    'prenom' => $appel->getPrenom(),
                    'mail' => $appel->getMail(),
                    'tel' => $appel->getTel(),
                    'objet' => $appel->getObjet(),
                    'message' => $appel->getMessage(),
                    'date' => $appel->getDate()
                ])
            ;
            $mailer->send($data);

            $appel->setDate(new \DateTime());
            $appelRepository->add($appel);
            $this->addFlash('sucess', 'Nous avons bien reçu votre demande d\'appel et nous vous recontacterons le plus rapidement possible. Surveillé votre téléphone il se peut qu\'on vous appel avec un numéro masqué.');
           // return $this->redirectToRoute('app_appel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appel/new.html.twig', [
            'appel' => $appel,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/interne/{id}", name="app_appel_show", methods={"GET"})
     */
    public function show(Appel $appel): Response
    {
        return $this->render('appel/show.html.twig', [
            'appel' => $appel,
        ]);
    }

    /**
     * @Route("/interne/{id}/edit", name="app_appel_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Appel $appel, AppelRepository $appelRepository): Response
    {
        $form = $this->createForm(AppelType::class, $appel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $appelRepository->add($appel);
            return $this->redirectToRoute('app_appel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appel/edit.html.twig', [
            'appel' => $appel,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/interne/{id}", name="app_appel_delete", methods={"POST"})
     */
    public function delete(Request $request, Appel $appel, AppelRepository $appelRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$appel->getId(), $request->request->get('_token'))) {
            $appelRepository->remove($appel);
        }

        return $this->redirectToRoute('app_appel_index', [], Response::HTTP_SEE_OTHER);
    }

    
    /**
     * @Route("/interne/completed/{id}", name="app_appel_checked", methods={"GET","POST"})
     */
    public function completed(Request $request, Appel $appel, AppelRepository $appelRepository): Response
    {
        if($appel->getDo()){
            $appel->setDo(0);
        }
        else{
            $appel->setDo(1);
        }
        $appelRepository->add($appel);
        return $this->redirectToRoute('app_appel_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/interne/noreply/{id}", name="app_appel_no_reply", methods={"POST"})
     */
    public function noreply(Request $request,MailerInterface $mailer, Appel $appel,  AppelRepository $appelRepository): Response
    {
        if ($this->isCsrfTokenValid('noreply'.$appel->getId(), $request->request->get('_token'))) {
            $data = (new TemplatedEmail())
                ->from((new Address('noreplyazertyfrance@gmail.com','AZERTY Solutions Informatiques')))
                ->to(new Address($appel->getMail()))
                ->bcc(new Address('contact@azertyfrance.fr'))
                ->cc('noreplyazertyfrance@gmail.com','contact@azertyfrance.fr')
                ->embedFromPath('../public/images/mail/wathsapp.svg.png', 'whatsapp')
                ->replyTo('contact@azertyfrance.fr')
                ->subject("Vous n'avez pas décroché")
                ->htmlTemplate('emails/appel/mail_no_reply.html.twig')
                ->context([
                    'nom' => $appel->getNom(),
                    'prenom' => $appel->getPrenom(),
                    'mail' => $appel->getMail(),
                    'tel' => $appel->getTel(),
                    'objet' => $appel->getObjet(),
                    'message' => $appel->getMessage(),
                    'date' => new \DateTime()
                ])
            ;
            $mailer->send($data);
            $appel->setDo(1);
            $appelRepository->add($appel);
        }

        return $this->redirectToRoute('app_appel_index', [], Response::HTTP_SEE_OTHER);
    }


}
