<?php

namespace App\Controller;

use App\Entity\Editeur;
use App\Entity\Client;
use App\Form\FormEditeur;
use App\Repository\EditeurRepository;
use App\Repository\EtatRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/editeur")
 */
class EditeurController extends AbstractController
{
    /**
     * @Route("/", name="editeur_index", methods={"GET"})
     */
    public function index(EditeurRepository $editeurRepository): Response
    {
        return $this->render('editeur/index.html.twig', [
            'editeurs' => $editeurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="editeur_new", methods={"GET","POST"})
     */
    public function new(Request $request, Client $client,EtatRepository $etatRepository , UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('editeurChange'.$client->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
    
            $user = $request->request->get('user');
            $etat =$request->request->get('etat');
            $mail = $request->request->get('mail');
            $appareil = $client->getAppareil();
            $editeur = new Editeur();
            $editeur->setDate(new \DateTime());
            $editeur->setUser($userRepository->find($user));
            $editeur->setEtat($etatRepository->find($etat));
            $appareil->setEditeur($editeur);

            $entityManager->persist($appareil);
            $entityManager->persist($editeur);
            $entityManager->flush(); 
            if($mail!=null){
                return $this->redirectToRoute('client_mail', ['id'=>$client->getId()], Response::HTTP_SEE_OTHER);
            }
            else{
                return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}", name="editeur_show", methods={"GET"})
     */
    public function show(Editeur $editeur): Response
    {
        return $this->render('editeur/show.html.twig', [
            'editeur' => $editeur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="editeur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Editeur $editeur): Response
    {
        $form = $this->createForm(FormEditeur::class, $editeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('editeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('editeur/edit.html.twig', [
            'editeur' => $editeur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="editeur_delete", methods={"POST"})
     */
    public function delete(Request $request, Editeur $editeur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$editeur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($editeur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('editeur_index', [], Response::HTTP_SEE_OTHER);
    }
}
