<?php

namespace App\Controller;

use App\Entity\Devis;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\DevisRepository;
use App\Form\AdminDevisType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/mon-espace/devis')]
class AccountDevisController extends AbstractController
{
    #[Route('', name: 'account_devis_index')]
    public function index(DevisRepository $devisRepository, Security $security): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $security->getUser();
        $devis = $devisRepository->findBy(['user' => $user], ['createdAt' => 'DESC']);

        return $this->render('account_devis/index.html.twig', [
            'devis' => $devis,
            'user' => $user,
        ]);
    }


    #[Route('/devis/{id}/edit', name: 'admin_devis_edit')]
    public function edit(Devis $devis, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(AdminDevisType::class, $devis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Devis mis à jour avec succès.');

            return $this->redirectToRoute('admin_devis_show', ['id' => $devis->getId()]);
        }

        return $this->render('admin_devis/edit.html.twig', [
            'devis' => $devis,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/mon-espace/devis/{id}', name: 'account_devis_show')]
    public function show(Devis $devis): Response
    {
        // Optionnel : vérifier que le devis appartient bien à l'utilisateur connecté pour la sécurité
        $user = $this->getUser();
        if ($devis->getUser() !== $user) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('account_devis/show.html.twig', [
            'devis' => $devis,
        ]);
    }
}
