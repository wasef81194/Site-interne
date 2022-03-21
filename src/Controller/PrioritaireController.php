<?php

namespace App\Controller;

use App\Entity\Prioritaire;
use App\Entity\Appareil;
use App\Form\PrioritaireType;
use App\Repository\PrioritaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/prioritaire")
 */
class PrioritaireController extends AbstractController
{
    /**
     * @Route("/", name="prioritaire_index", methods={"GET"})
     */
    public function index(PrioritaireRepository $prioritaireRepository): Response
    {
        return $this->render('prioritaire/index.html.twig', [
            'prioritaires' => $prioritaireRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}/new", name="prioritaire_new", methods={"GET","POST"})
     */
    public function new(Appareil $appareil,Request $request,PrioritaireRepository $prioritaireRepository): Response
    {
        if($prioritaireRepository->findOneBy(['appareil' => $appareil])){
            
            $this->addFlash('error', 'Ce client est déjà dans la liste des prioritaires');               
           
        }
        else{
            $prioritaire = new Prioritaire();
            $prioritaire->setAppareil($appareil);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($prioritaire);
            $entityManager->flush();
            $this->addFlash('sucess', 'Ce client à été ajouté dans la liste des prioritaires');               

        }
        return $this->redirectToRoute('client_show', ['id'=>$appareil->getClient()->getId()], Response::HTTP_SEE_OTHER);
        
    }

    /**
     * @Route("/{id}", name="prioritaire_show", methods={"GET"})
     */
    public function show(Prioritaire $prioritaire): Response
    {
        return $this->render('prioritaire/show.html.twig', [
            'prioritaire' => $prioritaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="prioritaire_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Prioritaire $prioritaire): Response
    {
        $form = $this->createForm(PrioritaireType::class, $prioritaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('prioritaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('prioritaire/edit.html.twig', [
            'prioritaire' => $prioritaire,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="prioritaire_delete", methods={"POST"})
     */
    public function delete(Request $request, Prioritaire $prioritaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prioritaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $prioritaire->setAppareil(null);
            $entityManager->remove($prioritaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('prioritaire_index', [], Response::HTTP_SEE_OTHER);
    }
}
