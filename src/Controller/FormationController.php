<?php

namespace App\Controller;


use App\Entity\MyPdf;
use App\Entity\Rating;
use DateTimeImmutable;
use App\Entity\DatePDF;
use App\Form\RatingType;
use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\DatePDFRepository;
use App\Services\ImageUploaderHelper;
use App\Repository\NotationRepository;
use App\Repository\FormationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/formation')]
class FormationController extends AbstractController
{
    #[Route('/pdf/{id}', name: 'app_formation_pdf', methods: ['GET'])]
    public function pdf(Formation $formation, DatePDFRepository $datePDFRepository): Response
    {
        $datePDF = $datePDFRepository->findOneBy([]);
        $dateCreation = $datePDF->getDateCreation();
        $dateModif = $datePDF->getDateModif();

        $pdf = new MyPdf();
        $pdf->setFormation($formation);
        $pdf->SetAuthor('SIO TEAM ! 💻');
        $pdf->SetTitle($formation->getName());
        $pdf->SetFont('times', '', 14);
        $pdf->setCellPaddings(1, 1, 1, 1);
        $pdf->setCellMargins(1, 1, 1, 1);
        // $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        // $pdf->SetHeaderMargin(45);

        $pdf->AddPage();

        // text-align: right; 
        $texthead = '<style>.head {font-size: 7px;}</style><span class="head"><i>Date création : 01-09-2022 / MAJ N°' . $datePDF->getNumMaj() . ' : ' . $dateModif->format('d-m-Y') . '</i> </span>';
        $pdf->writeHTMLCell(0, 20, 5, 0, $texthead, 0, 0, 0, true, '', true);

        $pdf->setY(45);
        $pdf->setX(75);

        $textD = '
        <style>hr { color: rgb(0, 63,144); }</style>
        <p><b>Objectifs de la formation</b>
        <hr/>' . $formation->getObjectif() . '
        <b>Prérequis necessaires / public cible</b>
        <hr/>' . $formation->getPrerequis() . '
        <b>Modalités d\'accès et d\'inscription</b>
        <hr/><br/><div></div>

        <u>Dates</u> : ' .
            ($formation->getStartDateTime() ? $formation->getStartDateTime()->format('d/m/Y') : 'Date inconnue') .
            ' à ' .
            ($formation->getEndDateTime() ? $formation->getEndDateTime()->format('d/m/Y') : 'Date inconnue') .
            '<br/>

        <u>Lieu</u> : ' . $formation->getPlace() . '
        <br/><br/>
        Nombre de stagiaires minimal : ' . $formation->getCapacityMin() . ' – Nombre de stagiaires maximal : ' . $formation->getCapacity() . '<br/>
        <i>Si le minimum requis de participants n’est pas atteint la session de formation
        ne pourra avoir lieu.</i>
        <br/>

        ' . $formation->getModalites() . '<br/>

        <b>Moyens pédagogiques et techniques</b>
        <hr/>' . $formation->getMoyenPedagogique() . '<br/>';

        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetFillColor(255, 255, 255);

        $pdf->writeHTMLCell(120, 50, "", "", $textD, 0, 1, 1, true, '', true);

        $heightTextD = $pdf->getStringHeight(120, $textD);

        // Modalités d'évaluation
        $textE = '<style>hr { color: rgb(0, 63,144); }</style><b>Modalités d\'évaluation</b>
        <hr/>' . $formation->getEvaluation() . '
        ';

        $heightTextE = $pdf->getStringHeight(120, $textE);
        //////////////////////////////////
        // DEBUG : Pour trouver la hauteur max avant le saut de page
        // $textE = $textE . " - HAUTEUR TEXTE Début du PDF = '" . $heightTextD . "'";
        // $textE = $textE . " - HAUTEUR TEXTE Evaluation = '" . $heightTextE . "'";
        //////////////////////////////////
        if ($heightTextD > 230) {
            $pdf->addPage();
            $currentY = 45;
        } else {
            $currentY = $pdf->getY();
        }

        $pdf->setX(75);
        $pdf->writeHTMLCell(120, 20, 75, $currentY, $textE, 0, 0, 1, true, '', true);

        // Générer le PDF pour l'afficher dans la page
        //return $pdf->Output('fcpro-formation-' . $formation->getId() . '.pdf','I');

        $response = new Response($pdf->Output('fcpro-formation-' . $formation->getId() . '.pdf', 'I'));
        $response->headers->set('Content-Type', 'application/pdf');
        return $response;
    }

    #[Route('/{id}/duplicate', name: 'app_formation_duplicate', methods: ['GET', 'POST'])]
    public function duplicate(Request $request, FormationRepository $formationRepository, TranslatorInterface $translator, Formation $formation): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $formation2 = new Formation();
        $formation2->setCreatedAt($formation->getCreatedAt());
        $formation2->setCreatedBy($formation->getCreatedBy());
        $formation2->setContent($formation->getContent());
        $formation2->setDescription($formation->getDescription());

        $formation2->setCapacity($formation->getCapacity());
        $formation2->setStartDateTime($formation->getStartDateTime());
        $formation2->setEndDateTime($formation->getEndDateTime());
        $formation2->setImageFileName($formation->getImageFileName());
        $formation2->setName($formation->getName());
        $formation2->setPrice($formation->getPrice());
        $formation2->setPlace($formation->getPlace());
        $formation2->setObjectif($formation->getObjectif());
        $formation2->setPrerequis($formation->getPrerequis());
        $formation2->setMoyenPedagogique($formation->getMoyenPedagogique());
        $formation2->setEvaluation($formation->getEvaluation());
        $formation2->setModalites($formation->getModalites());
        $formation2->setFormat($formation->getFormat());

        $formationRepository->save($formation2, true);
        $this->addFlash('success', $translator->trans('The formation is copied'));

        return $this->redirectToRoute('app_formation_index');
    }

    /* #[Route('/futur', name: 'app_formation_futur', methods: ['GET'])]
    public function futur(FormationRepository $formationRepository): Response
    {
        return $this->render('formation/futur.html.twig', [
            'formations' => $formationRepository->findAllInTheFuture(),
        ]);
    }
    */

    #[Route('/futur', name: 'app_formation_futur', methods: ['GET'])]
    public function formationsAvecDate(FormationRepository $formationRepository): Response
    {
        $formations = $formationRepository->findFormationsWithStartDate();

        return $this->render('formation/futur.html.twig', [
            'formations' => $formations,
        ]);
    }

    #[Route('/catalog', name: 'app_formation_catalog', methods: ['GET'])]
    public function catalog(FormationRepository $formationRepository): Response
    {

        $formations = $formationRepository->findAll();
        // dump($formations);
        return $this->render('formation/catalog.html.twig', [
            'formations' => $formations,
        ]);
    }

    #[Route('/', name: 'app_formation_index', methods: ['GET'])]
    public function index(FormationRepository $formationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Récupérez les formations triées par date de début
        $formations = $formationRepository->findBy([], ['startDateTime' => 'ASC']);

        return $this->render('formation/index.html.twig', [
            'formations' => $formations,
        ]);
    }

    #[Route('/new', name: 'app_formation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ImageUploaderHelper $imageUploaderHelper, FormationRepository $formationRepository, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $formation = new Formation();
        $formation->setCreatedAt(new DateTimeImmutable());
        $formation->setCreatedBy($this->getUser());

        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $errorMessage = $imageUploaderHelper->uploadImage($form, $formation);
            if (!empty($errorMessage)) {
                $this->addFlash('danger', $translator->trans('An error has occured: ') . $errorMessage);
            }
            $formationRepository->save($formation, true);

            return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('formation/new.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_formation_show', methods: ['GET'])]
    public function show(Formation $formation): Response
    {
        return $this->render('formation/show.html.twig', [
            'formation' => $formation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_formation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ImageUploaderHelper $imageUploaderHelper, FormationRepository $formationRepository, Formation $formation, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $errorMessage = $imageUploaderHelper->uploadImage($form, $formation);
            if (!empty($errorMessage)) {
                $this->addFlash('danger', $translator->trans('An error has occured: ') . $errorMessage);
            }
            $formationRepository->save($formation, true);

            return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('formation/edit.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_formation_delete', methods: ['POST'])]
    public function delete(Request $request, Formation $formation, FormationRepository $formationRepository, NotationRepository $notationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        if ($this->isCsrfTokenValid('delete' . $formation->getId(), $request->request->get('_token'))) {
            // Supprimez les notations associées
            $notationRepository->removeByFormation($formation);

            // Supprimez la formation
            $formationRepository->remove($formation, true);
        }

        return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/rate', name: 'app_formation_rate', methods: ['GET', 'POST'])]
    public function rate(Request $request, Formation $formation): Response
    {
        $rating = new Rating();
        $rating->setFormation($formation);

        $form = $this->createForm(RatingType::class, $rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rating);
            $entityManager->flush();

            $this->addFlash('success', 'Votre notation a été enregistrée avec succès.');

            return $this->redirectToRoute('app_formation_show', ['id' => $formation->getId()]);
        }

        return $this->render('formation/rate.html.twig', [
            'form' => $form->createView(),
            'formation' => $formation,
        ]);
    }
}
