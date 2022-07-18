<?php

namespace App\Controller;

use App\Entity\Appareil;
use App\Form\FormAppareilType;
use App\Repository\AppareilRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
/**
 * @Route("/appareil")
 */
class AppareilController extends AbstractController
{
    /**
     * @Route("/", name="appareil_index", methods={"GET"})
     */
    public function index(AppareilRepository $appareilRepository): Response
    {
        return $this->render('appareil/index.html.twig', [
            'appareils' => $appareilRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="appareil_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $appareil = new Appareil();
        $form = $this->createForm(Appareil1Type::class, $appareil);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($appareil);
            $entityManager->flush();

            return $this->redirectToRoute('appareil_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appareil/new.html.twig', [
            'appareil' => $appareil,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="appareil_show", methods={"GET"})
     */
    public function show(Appareil $appareil, Client $client): Response
    {
        return $this->render('appareil/show.html.twig', [
            'appareil' => $appareil,
            'client' => $client,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="appareil_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Appareil $appareil): Response
    {
  
        $appareil->setClient($appareil->getClient());
        $form = $this->createForm(FormAppareilType::class, $appareil);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appareil/edit.html.twig', [
            'appareil' => $appareil,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="appareil_delete", methods={"POST"})
     */
    public function delete(Request $request, Appareil $appareil): Response
    {
        if ($this->isCsrfTokenValid('delete'.$appareil->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($appareil);
            $entityManager->flush();
        }

        return $this->redirectToRoute('appareil_index', [], Response::HTTP_SEE_OTHER);
    }
}
