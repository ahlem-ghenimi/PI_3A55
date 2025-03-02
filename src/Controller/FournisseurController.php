<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Form\FournisseurType;
use App\Service\FournisseurEmailService;
use App\Repository\FournisseurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface; // Import MailerInterface

class FournisseurController extends AbstractController
{
    //frontOffice routes 
    #[Route('/front/fournisseur', name: 'app_fournisseur_front_index', methods: ['GET'])]
    public function frontIndex(FournisseurRepository $fournisseurRepository): Response
    {
        return $this->render('Gestion produits/front/fournisseur/index.html.twig', [
            'fournisseurs' => $fournisseurRepository->findAll(),
        ]);
    }

    #[Route('/front/fournisseur/new', name: 'app_fournisseur_front_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $fournisseur = new Fournisseur($mailer); // Pass the MailerInterface
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($fournisseur);
            $entityManager->flush();

            return $this->redirectToRoute('app_fournisseur_front_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Gestion produits/front/fournisseur/new.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form,
        ]);
    }

    #[Route('front/fournisseur/{id}/edit', name: 'app_fournisseur_front_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fournisseur $fournisseur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fournisseur_front_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Gestion produits/front/fournisseur/edit.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form,
        ]);
    }

    #[Route('front/fournisseur/{id}', name: 'app_fournisseur_front_delete', methods: ['POST'])]
    public function delete(Request $request, Fournisseur $fournisseur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fournisseur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($fournisseur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fournisseur_front_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('front/fournisseur/{id}/send-email', name: 'fournisseur_send_email')]
    public function sendEmail(Fournisseur $fournisseur, FournisseurEmailService $emailService): Response
    {
        try {
            $emailService->sendEmail($fournisseur, 'Subject', 'This is a test email.');
            return new Response('Email sent successfully!');
        } catch (\Exception $e) {
            return new Response('Failed to send email: ' . $e->getMessage(), 500);
        }
    }

    #[Route('front/fournisseur/{id}/pdf', name: 'app_fournisseur_front_pdf', methods: ['GET'])]
    public function pdf(Fournisseur $fournisseur): Response
    {
        $fournisseur->generatePdf();
        return new Response();
    }

    //BackOffice routes
    #[Route('/back/fournisseur', name: 'app_fournisseur__index', methods: ['GET'])]
    public function indexback(FournisseurRepository $fournisseurRepository): Response
    {
        return $this->render('Gestion produits/back/fournisseur/index.html.twig', [
            'fournisseurs' => $fournisseurRepository->findAll(),
        ]);
    }

    #[Route('/back/fournisseur/new', name: 'app_fournisseur_back_new', methods: ['GET', 'POST'])]
    public function newback(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $fournisseur = new Fournisseur($mailer); // Pass the MailerInterface
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($fournisseur);
            $entityManager->flush();

            return $this->redirectToRoute('app_fournisseur_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Gestion produits/back/fournisseur/new.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form,
        ]);
    }

    #[Route('back/fournisseur/{id}/edit', name: 'app_fournisseur_back_edit', methods: ['GET', 'POST'])]
    public function editback(Request $request, Fournisseur $fournisseur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fournisseur_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Gestion produits/back/fournisseur/edit.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form,
        ]);
    }

    #[Route('back/fournisseur/{id}', name: 'app_fournisseur_back_delete', methods: ['POST'])]
    public function deleteback(Request $request, Fournisseur $fournisseur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fournisseur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($fournisseur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fournisseur_back_index', [], Response::HTTP_SEE_OTHER);
    }
}
