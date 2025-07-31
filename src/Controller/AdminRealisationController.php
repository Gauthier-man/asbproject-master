<?php

// src/Controller/AdminRealisationController.php
namespace App\Controller;

use App\Entity\Realisation;
use App\Form\RealisationType;
use App\Repository\RealisationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/realisations')]
class AdminRealisationController extends AbstractController
{
    #[Route('/', name: 'admin_realisation_index')]
    public function index(RealisationRepository $repo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin_realisation/index.html.twig', [
            'realisations' => $repo->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'admin_realisation_new')]
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $realisation = new Realisation();
        $form = $this->createForm(RealisationType::class, $realisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('realisations_directory'),
                        $newFilename
                    );
                    $realisation->setImagePath($newFilename);
                } catch (FileException $e) {
                    // Handle exception
                }
            }

            $em->persist($realisation);
            $em->flush();

            $this->addFlash('success', 'Réalisation ajoutée.');
            return $this->redirectToRoute('admin_realisation_index');
        }

        return $this->render('admin_realisation/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_realisation_edit')]
    public function edit(Request $request, Realisation $realisation, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(RealisationType::class, $realisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $filename = $slugger->slug(pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move($this->getParameter('realisations_directory'), $filename);
                $realisation->setImagePath($filename);
            }

            $em->flush();
            $this->addFlash('success', 'Réalisation modifiée.');

            return $this->redirectToRoute('admin_realisation_index');
        }

        return $this->render('admin_realisation/edit.html.twig', [
            'form' => $form->createView(),
            'realisation' => $realisation,
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_realisation_delete')]
    public function delete(Realisation $realisation, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em->remove($realisation);
        $em->flush();

        $this->addFlash('success', 'Réalisation supprimée.');
        return $this->redirectToRoute('admin_realisation_index');
    }
}
