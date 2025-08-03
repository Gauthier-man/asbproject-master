<?php

namespace App\Controller;

use App\Repository\DevisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    #[Route('/mon-espace', name: 'app_account')]
    public function espacePersonnel(DevisRepository $devisRepository, Security $security): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $security->getUser();

        $devisList = $devisRepository->findBy(['user' => $user], ['createdAt' => 'DESC']);



        return $this->render('account/index.html.twig', [
            'user' => $user,
            'devis' => $devisList,
        ]);
    }
}
