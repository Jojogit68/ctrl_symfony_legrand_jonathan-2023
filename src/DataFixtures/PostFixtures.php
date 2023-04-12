<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Faker\Factory as Faker;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr_FR');
        $users = $this->userRepository->findAll();
        $usersLength = count($users)-1;
        for ($i=0; $i < 100; $i++) {
            // permet d'avoir un utilisateur random
            // possible à faire avec Faker mais plus lourd en ressource
            $randomKey = rand(0, $usersLength);
            $user = $users[$randomKey];
            $post = new Post();
            $post
                ->setTitle($faker->realText(10))
                ->setImage($faker->imageUrl())
                ->setUser($user)
            ;
            $manager->persist($post);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}