<?php

namespace App\DataFixtures;

use App\Entity\User;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->passwordHasher = $userPasswordHasher;
    }
    
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr_FR');

        $user = new User();
        $user
            ->setEmail('admin@email.com')
            ->setNickname('Administrateur')
            ->setRoles(['ROLE_ADMIN'])
        ;
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'admin');
        $user->setPassword($hashedPassword);

        $manager->persist($user);

        for ($i=0; $i < 20; $i++) {
            $user = new User();
            $user
                ->setEmail($faker->email())
                ->setNickname($faker->firstName())
                ->setRoles(['ROLE_USER'])

            ;
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
            $user->setPassword($hashedPassword);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
