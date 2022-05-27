<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Model;
use App\Entity\User;
use APp\Entity\Tag;
use App\Factory\ArticleFactory;
use App\Factory\UserFactory;
use App\Factory\TagFactory;
use App\Repository\ArticleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            TagFactory::class,
        ];
    }
    
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager)
    {
        // Models
        $models = [
            ["LinkedIn", "https://www.linkedin.com/in/", "fa-brands fa-linkedin-in"],
            ["Twitter", "https://www.twitter.com/", "fa-brands fa-twitter"],
            ["Twitch", "https://www.twitch.tv/", "fa-brands fa-twitch"],
            ["Discord", "https://discord.gg/", "fa-brands fa-discord"],
            ["Instagram", "https://www.instagram.com/", "fa-brands fa-instagram"],
            ["TikTok", "https://www.tiktok.com/@", "fa-brands fa-tiktok"],
            ["Youtube", "https://www.youtube.com/c/", "fa-brands fa-youtube"]
        ];

        foreach ($models as $model) {
            $newModel = new Model();
            $newModel->setName($model[0]);
            $newModel->setUrl($model[1]);
            $newModel->setIcon($model[2]);

            $manager->persist($newModel);
        }

        // Admin user
        $user = new User();
        $password = $this->hasher->hashPassword($user, 'password');
        $user
            ->setEmail("hello@nicolas-deleforge.fr")
            ->setPassword($password)
            ->setName("Nicolas")
            ->setRoles(["ROLE_ADMIN"])
            ->setIsVerified(1)
            ->setIsVisible(0);

        $manager->persist($user);

        // Users
        UserFactory::createMany(15);

        // Tags
        TagFactory::createMany(30);

        // Articles
        ArticleFactory::createMany(12);
        
        $manager->flush();
    }
}
