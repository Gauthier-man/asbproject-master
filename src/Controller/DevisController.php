<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Devis;
use App\Form\DevisType;

class DevisController extends AbstractController
{
    #[Route('/devis', name: 'app_devis', methods: ['GET', 'POST'])]
    public function demanderDevis(Request $request, EntityManagerInterface $em, Security $security): Response
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
            return $this->redirectToRoute('app_account'); // redirige vers l’espace perso
        }

        return $this->render('devis/demande.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
