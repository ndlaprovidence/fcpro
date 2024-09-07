<?php

namespace App\Controller;


use App\Entity\MyPdf;
use App\Entity\Rating;
use DateTimeImmutable;
use App\Entity\DatePDF;
use App\Form\RatingType;
use App\Entity\Formation;
use App\Form\Formation1Type;
use App\Repository\DatePDFRepository;
use App\Services\ImageUploaderHelper;
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
        $texthead = '<style>.head {font-size: 7px;}</style><span class="head"><i>Date création : 01-09-2022 / MAJ N°'. $datePDF->getNumMaj() .' : ' . $dateModif->format('d-m-Y') . '</i> </span>';
        $pdf->writeHTMLCell(0, 20, 5, 0, $texthead, 0, 0, 0, true, '', true);
        
        // $pdf->SetFont('helvetica', 'B', 20);
        // $pdf->SetFillColor(160,222,255);
        // $pdf->SetTextColor(0, 63,144);
        // $pdf->Image('images/fcpro.jpg', 8, 10, 39, 35, 'JPG', '/page/1', '', true, 150, '', false, false, 0, false, false, false);
        // $pdf->MultiCell(148, 20, "PROGRAMME DE FORMATION", 0, 'C', 1, 1, '48', '', true, 0, false, true, 20, 'M');
        // // $pdf->writeHTMLCell(65, 230, "", "", $dateCreation->format('Y-m-d'), 0, 0, 1, true, '', true);
        // // $pdf->writeHTMLCell(65, 230, "", "", $dateModif->format('Y-m-d'), 0, 0, 1, true, '', true);

        // $pdf->SetFont('helvetica', 'B', 17);
        // $pdf->SetFillColor(225,225,230);
        // $pdf->SetTextColor(0,0,0);
        // $pdf->MultiCell(148, 10, $formation->getName(), 0, 'C', 1, 1, '48', '', true);
        
        // $pdf->setCellPaddings(3,3,3,3);

        // COLONNE GAUCHE
//         $textg = '
//         <style> .blue { color: rgb(0, 63,144); } .link { color: rgb(100,0,0); }</style>
//         <br>
//         <p class="blue">
// <b>Tarifs :</b></p>
// '. $formation->getPrice() .' € net.
//         <p class="blue">
// <b>Modalités :</b>
//         </p>
// '. $formation->getFormat() .'
//         <p class="blue">
// <b>Accessibilité aux personnes handicapées :</b>
// </p><p>
// <b>Accès au lieu de formation</b> :<br>
// Les locaux sont accessibles aux
// personnes en situation de handicap,
// merci de nous contacter.<br>
// <br>
// <b>Accès à la prestation</b> :<br>
// Une adaptation de la formation est
// possible pour les personnes en
// situation de handicap, merci de nous
// contacter.
//         </p>
//         <p class="blue">
// <b>Contact :</b>
//         </p><p>
// <b>Alexia HEBERT, responsable de FCPRO</b><br>
// Service de Formation Professionnelle<br>
// Continue de l’OGEC Notre Dame de la Providence<br>
// <br>
// 9, rue chanoine Bérenger BP 340, 50300 AVRANCHES.<br>
// Tel 02 33 58 02 22<br>
// mail : <span class="link">fcpro@ndlpavranches.fr</span><br>
// <br>
// N° activité 25500040250<br>
// OF certifié QUALIOPI pour les actions de formations<br>
// <br>
// Site Web : <span class="link">https://ndlpavranches.fr/fc-pro/</span>
//         </p>';

//         $pdf->SetFont('helvetica', '', 10);
//         $pdf->SetFillColor(225,225,230);
//         $pdf->writeHTMLCell(65, 230, "", "", $textg, 0, 0, 1, true, '', true);

        //--------
        $pdf->setY(45);
        $pdf->setX(75);

        $textd = '
        <style>hr { color: rgb(0, 63,144); }</style>
        <p><b>Objectifs de la formation</b>
        <hr>'. $formation->getObjectif() .'
        <b>Prérequis necessaires / public cible</b>
        <hr>'. $formation->getPrerequis() .'
        <b>Modalités d\'accès et d\'inscription</b>
        <hr><br><div></div>

        <u>Dates</u> : ' . 
            ($formation->getStartDateTime() ? $formation->getStartDateTime()->format('d/m/Y') : 'Date inconnue') . 
            ' à ' . 
            ($formation->getEndDateTime() ? $formation->getEndDateTime()->format('d/m/Y') : 'Date inconnue') . 
            '<br>

        <u>Lieu</u> : ' . $formation->getPlace() . '
        <br><br>
        Nombre de stagiaires minimal : ' . $formation->getCapacityMin() . ' – Nombre de stagiaires maximal : '. $formation->getCapacity() .'<br>
        <i>Si le minimum requis de participants n’est pas atteint la session de formation
        ne pourra avoir lieu.</i>
        <br>

        '. $formation->getModalites() .'<br>
        <b>Moyens pédagogiques et techniques</b>
        <hr>'. $formation->getMoyenPedagogique() .'<br>';

        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetFillColor(255,255,255);

        $pdf->writeHTMLCell(120, 50, "", "", $textd, 0, 1, 1, true, '', true);
        
        
        // Modalité d'évaluation
        $textd = '<style>hr { color: rgb(0, 63,144); }</style><b>Modalité d\'évaluation</b>
        <hr>'. $formation->getEvaluation() .'
        ';        
        $heightTextd = $pdf->getStringHeight(120, $textd);

        if ($heightTextd > 40) {
            $pdf->addPage();
            $currentY = 45;
        } else {
            $currentY = $pdf->getY();
        }
        
        $pdf->setX(75);        
        $pdf->writeHTMLCell(120, 20, 75, $currentY, $textd, 0, 0, 1, true, '', true);

        // Générer le PDF pour l'afficher dans la page
        //return $pdf->Output('fcpro-formation-' . $formation->getId() . '.pdf','I');

        $response = new Response($pdf->Output('fcpro-formation-' . $formation->getId() . '.pdf','I'));
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
        return $this->render('formation/catalog.html.twig', [
            'formations' => $formationRepository->findAll(),
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

        $form = $this->createForm(Formation1Type::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $errorMessage = $imageUploaderHelper->uploadImage($form, $formation);
            if (!empty($errorMessage)) {
                $this->addFlash ('danger', $translator->trans('An error has occured: ') . $errorMessage);
            }
            $formationRepository->save($formation, true);

            return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('formation/new.html.twig', [
            'formation' => $formation,
            'form' => $form,
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

        $form = $this->createForm(Formation1Type::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $errorMessage = $imageUploaderHelper->uploadImage($form, $formation);
            if (!empty($errorMessage)) {
                $this->addFlash ('danger', $translator->trans('An error has occured: ') . $errorMessage);
            }
            $formationRepository->save($formation, true);

            return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('formation/edit.html.twig', [
            'formation' => $formation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_formation_delete', methods: ['POST'])]
    public function delete(Request $request, Formation $formation, FormationRepository $formationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$formation->getId(), $request->request->get('_token'))) 
        {
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
