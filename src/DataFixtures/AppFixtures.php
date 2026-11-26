<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('sportif@m2l.fr');
        $user->setPassword($this->hasher->hashPassword($user, 'password123'));
        $user->setNom('Dupont');
        $user->setPrenom('Jean');
        $user->setDateNaissance(new \DateTime('2000-01-01'));
        $user->setRoles(['ROLE_USER' , 'ROLE_ANIMATEUR']);

        $manager->persist($user);
        $manager->flush();

        $user = new User();
        $user->setEmail('anael.bleurvacq777@gmail.com');
        $user->setPassword($this->hasher->hashPassword($user, 'anael'));
        $user->setNom('BLEURVACQ');
        $user->setPrenom('Anael');
        $user->setDateNaissance(new \DateTime('2007-05-03'));
        $user->setRoles(['ROLE_USER' , 'ROLE_ADMIN' , 'ROLE_ANIMATEUR' , 'ROLE_RESPONSABLE' , 'ROLE_SUPER_PRESIDENT']);

        $manager->persist($user);
        $manager->flush();

        $user = new User();
        $user->setEmail('Test@test.com');
        $user->setPassword($this->hasher->hashPassword($user, 'test'));
        $user->setNom('TEST');
        $user->setPrenom('Test');
        $user->setDateNaissance(new \DateTime('2000-01-01'));
        $user->setRoles(['ROLE_USER' , 'ROLE_ANIMATEUR']);

        $manager->persist($user);
        $manager->flush();
    }
}