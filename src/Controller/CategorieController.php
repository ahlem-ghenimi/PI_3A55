<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Form\SearchCategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Dompdf\Dompdf;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategorieController extends AbstractController
{
    // Front office routes
    #[Route('/front/categorie', name: 'app_categorie_front_index', methods: ['GET'])]
    public function frontIndex(CategorieRepository $categorieRepository, Request $request): Response
    {
        // Create search form
        $searchForm = $this->createForm(SearchCategorieType::class);
        $searchForm->handleRequest($request);

        // Debugging: Log the entire request query parameters
        dump($request->query->all()); // Log all query parameters

        // Get search term
        $searchTerm = $request->query->get('search');
        dump('Search Term: ' . $searchTerm); // Log the search term

        // Fetch categories based on search
        $categories = $searchTerm ? $categorieRepository->searchByTerm($searchTerm) : $categorieRepository->findAll();

        // Debugging: Log the fetched categories
        dump('Fetched Categories: ' . json_encode($categories)); // Log the fetched categories

        return $this->render('Gestion produits/front/categorie/index.html.twig', [
            'categories' => $categories,
            'searchForm' => $searchForm->createView(),
        ]);
    }

    #[Route('/front/categorie/new', name: 'app_categorie_front_new', methods: ['GET', 'POST'])]
    public function frontNew(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_front_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Gestion produits/front/categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/front/categorie/{nom_categorie}', name: 'app_categorie_front_show', methods: ['GET'])]
    public function frontShow(Categorie $categorie): Response
    {
        return $this->render('Gestion produits/front/categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    #[Route('/front/categorie/{nom_categorie}/edit', name: 'app_categorie_front_edit', methods: ['GET', 'POST'])]
    public function frontEdit(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_front_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Gestion produits/front/categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/categorie/{nom_categorie}', name: 'app_categorie_front_delete', methods: ['POST'])]
    public function frontdelete(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getNomCategorie(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categorie_front_index', [], Response::HTTP_SEE_OTHER);
    }

    // Back office routes
    #[Route('/back/categorie', name: 'app_categorie_back_index', methods: ['GET'])]
    public function backIndex(CategorieRepository $categorieRepository, Request $request): Response
    {
        // Create search form
        $searchForm = $this->createForm(SearchCategorieType::class);
        $searchForm->handleRequest($request);

        // Get search query if submitted
        $searchTerm = $request->query->get('search');
        $categories = $searchTerm ? $categorieRepository->searchByTerm($searchTerm) : $categorieRepository->findAll();

        return $this->render('Gestion produits/back/categorie/index.html.twig', [
            'categories' => $categories,
            'searchForm' => $searchForm->createView(),
        ]);
    }

    #[Route('/back/categorie/new', name: 'app_categorie_back_new', methods: ['GET', 'POST'])]
    public function backNew(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Gestion produits/back/categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/back/categorie/{nom_categorie}/edit', name: 'app_categorie_back_edit', methods: ['GET', 'POST'])]
    public function backEdit(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Gestion produits/back/categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/front/categorie/{nom_categorie}/download-pdf', name: 'app_categorie_front_download_pdf', methods: ['GET'])]
    public function downloadCategoriePdf(Categorie $categorie): Response
    {
        // Generate PDF logic here
        $pdfContent = $this->renderView('Gestion produits/front/categorie/pdf.html.twig', [
            'categorie' => $categorie,
        ]);

        $pdf = new Dompdf();
        $pdf->loadHtml($pdfContent);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return new Response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="categorie_' . $categorie->getNomCategorie() . '.pdf"',
        ]);
    }

    #[Route('back/categorie/{nom_categorie}/delete', name: 'app_categorie_back_delete', methods: ['POST'])]
    public function delete(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' .$categorie->getNomCategorie(), $request->request->get('_token'))) {
            $entityManager->remove($categorie);
            $entityManager->flush();
            $this->addFlash('successahlem', 'categorie supprimée avec succès !');
        }

        return $this->redirectToRoute('app_categorie_back_index');
    }
}
