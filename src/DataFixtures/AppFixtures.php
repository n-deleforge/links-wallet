<?php

namespace App\DataFixtures;

use App\Entity\LinkModel;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail("hello@nicolas-deleforge.fr");
        $password = $this->hasher->hashPassword($user, 'password');
        $user->setPassword($password);
        $user->setName("Nicolas");
        $user->setIsVerified(1);
        $manager->persist($user);

        $link_1 = new LinkModel();
        $link_1->setName("LinkedIn");
        $link_1->setUrl("https://www.linkedin.com/in/");
        $link_1->setIcon("fa-brands fa-linkedin-in");
        $manager->persist($link_1);

        $manager->flush();
    }
}
