<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AppLoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new User();

        // Pré-remplir l'email si passé dans l'URL
        $emailFromRequest = $request->query->get('email');
        if ($emailFromRequest) {
            $user->setEmail($emailFromRequest);
        }

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            // Lier les devis non liés qui ont le même email
            $devisRepo = $entityManager->getRepository(\App\Entity\Devis::class);
            $devisSansUser = $devisRepo->findBy([
                'email' => $user->getEmail(),
                'user' => null,
            ]);

            dd($devisSansUser);
            foreach ($devisSansUser as $devis) {
                $devis->setUser($user);
            }

            $entityManager->flush();
            // -------------------------
            // login automatique après inscription
            return $security->login($user, AppLoginAuthenticator::class, 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
