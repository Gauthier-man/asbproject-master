<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Devis;
use App\Entity\User;
use App\Form\DevisType;

class DevisController extends AbstractController
{
    // src/Controller/DevisController.php

    #[Route('/devis', name: 'app_devis')]
    public function index(Request $request, EntityManagerInterface $em)
    {
        $devis = new Devis();

        $user = $this->getUser();
        if ($user instanceof User) {
            $devis->setEmail($user->getEmail());
            $devis->setUser($user);
        }

        $form = $this->createForm(DevisType::class, $devis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($devis);
            $em->flush();

            // Redirection avec email
            return $this->redirectToRoute('app_devis_thanks', [
                'email' => $devis->getEmail()
            ]);
        }

        return $this->render('account_devis/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // src/Controller/DevisController.php

    #[Route('/devis/merci', name: 'app_devis_thanks')]
    public function merci(Request $request)
    {
        $email = $request->query->get('email');
        $user = $this->getUser();

        return $this->render('devis/merci.html.twig', [
            'email' => $email,
            'user' => $user,
        ]);
    }
}
