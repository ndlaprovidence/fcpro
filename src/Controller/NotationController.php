<?php

namespace App\Controller;

use App\Entity\Notation;
use App\Form\Notation1Type;
use App\Repository\NotationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/notation')]
class NotationController extends AbstractController
{
    #[Route('/', name: 'app_notation_index', methods: ['GET'])]
    public function index(NotationRepository $notationRepository): Response
    {
        return $this->render('notation/index.html.twig', [
            'notations' => $notationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_notation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, NotationRepository $notationRepository): Response
    {
        $notation = new Notation();
        $form = $this->createForm(Notation1Type::class, $notation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notationRepository->save($notation, true);

            return $this->redirectToRoute('app_notation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('notation/new.html.twig', [
            'notation' => $notation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_notation_show', methods: ['GET'])]
    public function show(Notation $notation): Response
    {
        return $this->render('notation/show.html.twig', [
            'notation' => $notation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_notation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Notation $notation, NotationRepository $notationRepository): Response
    {
        $form = $this->createForm(Notation1Type::class, $notation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notationRepository->save($notation, true);

            return $this->redirectToRoute('app_notation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('notation/edit.html.twig', [
            'notation' => $notation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_notation_delete', methods: ['POST'])]
    public function delete(Request $request, Notation $notation, NotationRepository $notationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$notation->getId(), $request->request->get('_token'))) {
            $notationRepository->remove($notation, true);
        }

        return $this->redirectToRoute('app_notation_index', [], Response::HTTP_SEE_OTHER);
    }
}
