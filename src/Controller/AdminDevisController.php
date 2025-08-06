<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Form\AdminDevisType;
use App\Repository\DevisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminDevisController extends AbstractController
{
    #[Route('/devis', name: 'admin_devis_list')]
    public function index(DevisRepository $devisRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $devisList = $devisRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin_devis/index.html.twig', [
            'devis' => $devisList,
        ]);
    }

    #[Route('/devis/{id}', name: 'admin_devis_show')]
    public function show(Devis $devis): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin_devis/show.html.twig', [
            'devis' => $devis,
        ]);
    }

    #[Route('/devis/{id}/delete', name: 'admin_devis_delete')]
    public function delete(Devis $devis, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em->remove($devis);
        $em->flush();

        $this->addFlash('success', 'Devis supprimé avec succès.');

        return $this->redirectToRoute('admin_devis_list');
    }


    #[Route('/devis/{id}/edit', name: 'admin_devis_edit')]
    public function edit(Devis $devis, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(AdminDevisType::class, $devis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // IMPORTANT : $devis est déjà géré par Doctrine, donc il suffit de flush
            $em->flush();

            $this->addFlash('success', 'Devis mis à jour avec succès.');

            return $this->redirectToRoute('admin_devis_show', ['id' => $devis->getId()]);
        }

        return $this->render('admin_devis/edit.html.twig', [
            'devis' => $devis,
            'form' => $form->createView(),
        ]);
    }
}
