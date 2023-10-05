<?php

namespace App\Controller;

use App\Entity\Proposition;
use App\Form\PropositionType;
use App\Repository\PropositionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/proposition')]
class PropositionController extends AbstractController
{
    #[Route('/', name: 'app_proposition_index', methods: ['GET'])]
    public function index(PropositionRepository $propositionRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('proposition/index.html.twig', [
            'propositions' => $propositionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_proposition_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PropositionRepository $propositionRepository): Response
    {

        $this->denyAccessUnlessGranted('ROLE_USER');

        $proposition = new Proposition();
        $form = $this->createForm(PropositionType::class, $proposition);
        $proposition->setEmail($this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $propositionRepository->save($proposition, true);

            return $this->redirectToRoute('app_page_show', ['id' => 1]);
        }

        return $this->renderForm('proposition/new.html.twig', [
            'proposition' => $proposition,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_proposition_show', methods: ['GET'])]
    public function show(Proposition $proposition): Response
    {
        return $this->render('proposition/show.html.twig', [
            'proposition' => $proposition,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_proposition_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Proposition $proposition, PropositionRepository $propositionRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $form = $this->createForm(PropositionType::class, $proposition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $propositionRepository->save($proposition, true);

            return $this->redirectToRoute('app_proposition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('proposition/edit.html.twig', [
            'proposition' => $proposition,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_proposition_delete', methods: ['POST'])]
    public function delete(Request $request, Proposition $proposition, PropositionRepository $propositionRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        
        if ($this->isCsrfTokenValid('delete'.$proposition->getId(), $request->request->get('_token'))) {
            $propositionRepository->remove($proposition, true);
        }

        return $this->redirectToRoute('app_proposition_index', [], Response::HTTP_SEE_OTHER);
    }
}
