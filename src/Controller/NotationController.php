<?php

namespace App\Controller;

use App\Entity\Notation;
use App\Form\NotationType;
use App\Repository\NotationRepository;
use App\Repository\FormationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/notation')]
class NotationController extends AbstractController
{
    #[Route('/', name: 'app_notation_index', methods: ['GET'])]
    public function index(NotationRepository $notationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        return $this->render('notation/index.html.twig', [
            'notations' => $notationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_notation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, NotationRepository $notationRepository, FormationRepository $formationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        $user = $this->getUser();
        $currentUserEmail = $user->getEmail();

        // Récupérer les formations disponibles pour l'utilisateur courant
        $formations = $formationRepository->createQueryBuilder('f')
            ->where('f.validation = :validation')
            ->andWhere('f.id NOT IN (
                SELECT nf.id FROM App\Entity\Notation n
                JOIN n.formation nf
                WHERE n.user = :userEmail
            )')
            ->setParameter('validation', 1)
            ->setParameter('userEmail', $currentUserEmail)
            ->getQuery()
            ->getResult();

        // Créer le formulaire uniquement si des formations sont disponibles
        if (count($formations) > 0) {

            // Créez une nouvelle note à partir des données du formulaire
            $notation = new Notation();
            $form = $this->createForm(NotationType::class, $notation);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $formationId = $notation->getFormation()->getid();
                // dump($formationId);
                
                // if ($formationId > 0) {
        
                    // Recherchez une note existante pour cette formation et cet utilisateur
                    // $existingNotation = $notationRepository->findOneBy(['formation' => $formationId, 'user' => $user->getEmail()]);

                    // if ($existingNotation) {
                    //     $notationRepository->remove($existingNotation, true);
                    // }
                    // $notationRepository->save($notation, true);

                    dump($notation);
                    // if (!$existingNotation) {
                        $notationRepository->save($notation, true);
                    // }

                    // return $this->redirectToRoute('app_page_show', ['id' => 1]);
                // }
            }

            return $this->render('notation/new.html.twig', [
                'form' => $form->createView(),
            ]);

        }

        // Affichez un message si aucune formation n'est disponible
        return $this->render('notation/no_formations.html.twig');

    }


    #[Route('/{id}', name: 'app_notation_show', methods: ['GET'])]
    public function show(Notation $notation): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        return $this->render('notation/show.html.twig', [
            'notation' => $notation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_notation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Notation $notation, NotationRepository $notationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $form = $this->createForm(NotationType::class, $notation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notationRepository->save($notation, true);

            return $this->redirectToRoute('app_notation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('notation/edit.html.twig', [
            'notation' => $notation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_notation_delete', methods: ['POST'])]
    public function delete(Request $request, Notation $notation, NotationRepository $notationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        
        if ($this->isCsrfTokenValid('delete'.$notation->getId(), $request->request->get('_token'))) {
            $notationRepository->remove($notation, true);
        }

        return $this->redirectToRoute('app_notation_index', [], Response::HTTP_SEE_OTHER);
    }
}
