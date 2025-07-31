<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Form\DevisType;
use App\Repository\ArticleRepository;
use App\Repository\RealisationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $em, Security $security, RealisationRepository $realisationRepository, ArticleRepository $articleRepository): Response
    {
        $devis = new Devis();
        $devis->setCreatedAt(new \DateTime());
        $devis->setUser($security->getUser());

        $form = $this->createForm(DevisType::class, $devis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($devis);
            $em->flush();

            $this->addFlash('success', 'Votre demande de devis a bien été envoyée.');
            return $this->redirectToRoute('app_home');
        }

        // Récupération des 47 premières réalisations
        $realisations = $realisationRepository->findBy([], null, 47);
        $articles = $articleRepository->findBy([], ['createdAt' => 'DESC'], 3);
        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'realisations' => $realisations,
            'articles' => $articles,
        ]);
    }
}
