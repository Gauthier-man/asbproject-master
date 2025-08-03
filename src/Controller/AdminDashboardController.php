<?php

// src/Controller/AdminDashboardController.php
namespace App\Controller;

use App\Repository\DevisRepository;
use App\Repository\ArticleRepository;
use App\Repository\RealisationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AdminDashboardController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(
        DevisRepository $devisRepository,
        ArticleRepository $articleRepository,
        RealisationRepository $realisationRepository
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/dashboard.html.twig', [
            'devisList' => $devisRepository->findBy([], ['createdAt' => 'DESC'], 3),
            'articles' => $articleRepository->findBy([], ['createdAt' => 'DESC'], 3),
            'realisations' => $realisationRepository->findBy([], ['createdAt' => 'DESC'], 3),
        ]);
    }
}
