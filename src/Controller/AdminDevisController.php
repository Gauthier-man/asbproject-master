<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Repository\DevisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
