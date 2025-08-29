<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('haha@example.com');
        $admin->setIsAdmin(true);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        $user = new User();
        $user->setEmail('user@example.com');
        $user->setIsAdmin(false);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user123'));
        $manager->persist($user);

        $user2 = new User();
        $user2->setEmail('john@example.com');
        $user2->setIsAdmin(false);
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'john123'));
        $manager->persist($user2);


        $manager->flush();
    }
}
