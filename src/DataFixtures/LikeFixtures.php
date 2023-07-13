<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\DataFixtures\PostFixtures;
use App\DataFixtures\UserFixtures;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LikeFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private PostRepository $postRepository,
        private UserRepository $userRepository
    ) {
    }

    public function load(ObjectManager $manager): void
    {
       
        $posts = $this->postRepository->findAll();
        $users = $this->userRepository->findAll();

        foreach ($posts as $post) {
            for ($i = 0; $i < mt_rand(0, 15); $i++) {
                $post->addLikes(
                    $users[mt_rand(0, count($users) - 1)]
                );
            }
        }
 

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            PostFixtures::class
        ];
    }
}
