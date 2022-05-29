<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    const USER_NORMAL = 'user_1';
    const USER_ADMIN = 'user_2';

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('test_user@mail.com');
        $user->setUsername('test_user');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'test_user'));
        $manager->persist($user);
        $this->setReference(UserFixtures::USER_NORMAL, $user);

        $user = new User();
        $user->setEmail('test_admin@mail.com');
        $user->setUsername('test_admin');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'test_admin'));
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $manager->persist($user);
        $this->setReference(UserFixtures::USER_ADMIN, $user);

        $manager->flush();
    }
}
