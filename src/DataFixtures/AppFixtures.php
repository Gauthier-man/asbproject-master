<?php

// src/DataFixtures/AdminUserFixture.php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@gmail.com');
        $admin->setPrenom('Admin');
        $admin->setNom('Super');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, 'admin')
        );

        $manager->persist($admin);



        // Création de plusieurs utilisateurs simples
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setEmail("user{$i}@gmail.com");
            $user->setPrenom("Prenom{$i}");
            $user->setNom("Nom{$i}");
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, 'userpassword' . $i)
            );
            $manager->persist($user);
        }


        $manager->flush();
    }
}

