<?php

// src/Controller/RealisationController.php
namespace App\Controller;

use App\Entity\Realisation;
use App\Repository\RealisationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RealisationController extends AbstractController
{
    #[Route('/realisations', name: 'app_realisations')]
    public function index(RealisationRepository $repo): Response
    {
        $realisations = $repo->findAll();


        return $this->render('realisations/index.html.twig', [
            'realisations' => $realisations,
        ]);
    }

    #[Route('/add-test-realisation', name: 'add_test_realisation')]
    public function addTest(EntityManagerInterface $em): Response
    {
        $realisation = new Realisation();
        $realisation->setCategory('charpente');
        $realisation->setImagePath("Charpente/Centre aquatique/DSCN0296.JPG");
        $realisation->setCreatedAt(new \DateTimeImmutable());

        $em->persist($realisation);
        $em->flush();

        return new Response('Réalisation test ajoutée !');
    }
}
