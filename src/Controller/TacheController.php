<?php

namespace App\Controller;

use App\Entity\Tache;
use App\Form\TacheType;
use App\Repository\TacheRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Appareil;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\EtatRepository;
/**
 * @Route("/tache")
 */
class TacheController extends AbstractController
{
    /**
     * @Route("/", name="tache_index", methods={"GET"})
     */
    public function index(TacheRepository $tacheRepository,  UserRepository $userRepository,  EtatRepository $etatRepository): Response
    {
        return $this->render('tache/index.html.twig', [
            'taches' => $tacheRepository->findAll(),
            'users'=>$userRepository->findAll(),
            'etats'=>$etatRepository->findAll()
        ]);
    }

    /**
     * @Route("/appareil/{id}/new", name="tache_new", methods={"GET","POST"})
     */
    public function new(Request $request,Appareil $appareil): Response
    {
        $tache = new Tache();
        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);
        $client = $appareil->getClient();

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $tache->setDate(new \DateTime());
            $tache->setAppareil($appareil);
            $entityManager->persist($tache);
            $entityManager->flush();
            $client = $tache->getAppareil()->getClient();
            return $this->redirectToRoute('client_show', ['id'=>$client->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tache/new.html.twig', [
            'tache' => $tache,
            'form' => $form,
            'client'=>$client
        ]);
    }

    /**
     * @Route("/{id}", name="tache_show", methods={"GET"})
     */
    public function show(Tache $tache): Response
    {
        return $this->render('tache/show.html.twig', [
            'tache' => $tache,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tache_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tache $tache): Response
    {
        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tache_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tache/edit.html.twig', [
            'tache' => $tache,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="tache_delete", methods={"POST"})
     */
    public function delete(Request $request, Tache $tache): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tache->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tache);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tache_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/do/{id}", name="tache_do", methods={"POST","GET"})
     */
    public function executed(Request $request, Tache $tache, TacheRepository $tacheRepository): Response
    {
        if($tache->getDo()){
            $tache->setDo(0);
        }
        else{
            $tache->setDo(1);
        }
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('tache_index', [], Response::HTTP_SEE_OTHER);
    }
}
