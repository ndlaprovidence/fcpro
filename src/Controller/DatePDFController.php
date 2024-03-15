<?php

namespace App\Controller;

use App\Entity\DatePDF;
use App\Form\DatePDF1Type;
use App\Repository\DatePDFRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/date-pdf')]
class DatePDFController extends AbstractController
{
    #[Route('/', name: 'app_date_p_d_f_index', methods: ['GET'])]
    public function index(DatePDFRepository $datePDFRepository): Response
    {
        return $this->render('date_pdf/index.html.twig', [
            'date_p_d_fs' => $datePDFRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_date_p_d_f_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $datePDF = new DatePDF();
        $form = $this->createForm(DatePDF1Type::class, $datePDF);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($datePDF);
            $entityManager->flush();

            return $this->redirectToRoute('app_date_p_d_f_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('date_pdf/new.html.twig', [
            'date_p_d_f' => $datePDF,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_date_p_d_f_show', methods: ['GET'])]
    public function show(DatePDF $datePDF): Response
    {
        return $this->render('date_pdf/show.html.twig', [
            'date_p_d_f' => $datePDF,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_date_p_d_f_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DatePDF $datePDF, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DatePDF1Type::class, $datePDF);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_formation_index');
        }

        return $this->render('date_pdf/edit.html.twig', [
            'date_p_d_f' => $datePDF,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_date_p_d_f_delete', methods: ['POST'])]
    public function delete(Request $request, DatePDF $datePDF, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$datePDF->getId(), $request->request->get('_token'))) {
            $entityManager->remove($datePDF);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_date_p_d_f_index', [], Response::HTTP_SEE_OTHER);
    }
}
