<?php

namespace App\Controller;

use App\Entity\Appel;
use App\Form\AppelType;
use App\Repository\AppelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/appel")
 */
class AppelController extends AbstractController
{
    /**
     * @Route("/", name="app_appel_index", methods={"GET"})
     */
    public function index(AppelRepository $appelRepository): Response
    {
        return $this->render('appel/index.html.twig', [
            'appels' => $appelRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_appel_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AppelRepository $appelRepository): Response
    {
        $appel = new Appel();
        $form = $this->createForm(AppelType::class, $appel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $appel->setDate(new \DateTime() );
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
     * @Route("/{id}", name="app_appel_show", methods={"GET"})
     */
    public function show(Appel $appel): Response
    {
        return $this->render('appel/show.html.twig', [
            'appel' => $appel,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_appel_edit", methods={"GET", "POST"})
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
     * @Route("/{id}", name="app_appel_delete", methods={"POST"})
     */
    public function delete(Request $request, Appel $appel, AppelRepository $appelRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$appel->getId(), $request->request->get('_token'))) {
            $appelRepository->remove($appel);
        }

        return $this->redirectToRoute('app_appel_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/completed/{id}", name="app_appel_checked", methods={"POST"})
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
}
