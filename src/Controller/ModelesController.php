<?php


// src/Controller/ModelesController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModelesController extends AbstractController
{
    #[Route('/modeles', name: 'app_modeles')]
    public function index(): Response
    {
        $modeles = [
            [
                'nom' => 'Bungalow 56 m²',
                'image' => 'img\modeles\bungalows.png',
                'type' => 'Maison ossature bois',
                'surface' => '56 m²',
                'prix_ht' => '95 587,40 €',
                'charpente' => 'Pin Sylvestre',
                'pdf' => '/pdfs/bungalow_56.pdf',
            ],
            [
                'nom' => 'Bungalow 35 m²',
                'image' => 'img\modeles\bungalows2.png',
                'type' => 'Maison ossature bois',
                'surface' => '35 m²',
                'prix_ht' => '66 685,69 €',
                'charpente' => 'Pin Sylvestre',
                'pdf' => '/pdfs/bungalow_35.pdf',
            ],
            // Tu peux ajouter d'autres modèles ici
        ];

        return $this->render('modeles/index.html.twig', [
            'modeles' => $modeles,
        ]);
    }
}
