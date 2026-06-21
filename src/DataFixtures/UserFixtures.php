<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const string ADMIN_REFERENCE = 'user-admin';

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $admin         = new User('admin@tc.local');
        $admin->roles  = ['ROLE_ADMIN'];
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin'));

        $manager->persist($admin);
        $manager->flush();

        $this->addReference(self::ADMIN_REFERENCE, $admin);
    }
}
