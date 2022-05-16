<?php

namespace App\DataFixtures;

use App\Entity\Model;
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
        // admin user
        $user = new User();
        $user->setEmail("hello@nicolas-deleforge.fr");
        $password = $this->hasher->hashPassword($user, 'password');
        $user->setPassword($password);
        $user->setName("Nicolas");
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setIsVerified(1);
        $user->setIsVisible(0);
        $manager->persist($user);

        // models
        $models = [
            ["LinkedIn", "https://www.linkedin.com/in/", "fa-brands fa-linkedin-in"],
            ["Twitter", "https://www.twitter.com/", "fa-brands fa-twitter"],
            ["Twitch", "https://www.twitch.tv/", "fa-brands fa-twitch"],
            ["Discord", "https://discord.gg/", "fa-brands fa-discord"],
            ["Instagram", "https://www.instagram.com/", "fa-brands fa-instagram"],
            ["TikTok", "https://www.tiktok.com/@", "fa-brands fa-tiktok"],
            ["Youtube", "https://www.youtube.com/c/", "fa-brands fa-youtube"]
        ];

        foreach($models as $model) {
            $newModel = new Model();
            $newModel->setName($model[0]);
            $newModel->setUrl($model[1]);
            $newModel->setIcon($model[2]);

            $manager->persist($newModel);
        }

        $manager->flush();
    }
}
