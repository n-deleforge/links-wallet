<?php

namespace App\Controller;

use App\Entity\User;
use Badcow\LoremIpsum\Generator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatableMessage;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function home(): Response
    {
        return $this->render('default/home.html.twig');
    }

    /**
     * @Route("/pricing", name="app_pricing")
     */
    public function pricing(): Response
    {
        $offers = [
            "free" => [
                "title" => new TranslatableMessage('pricing.free.title'),
                "price" => new TranslatableMessage('pricing.free.price'),
                "text" => new TranslatableMessage('pricing.free.text'),
                "button" => new TranslatableMessage('pricing.free.button'),
            ],
            "starter" => [
                "title" => new TranslatableMessage('pricing.starter.title'),
                "price" => new TranslatableMessage('pricing.starter.price'),
                "text" => new TranslatableMessage('pricing.starter.text'),
                "button" => new TranslatableMessage('pricing.starter.button'),
            ],
            "premium" => [
                "title" => new TranslatableMessage('pricing.premium.title'),
                "price" => new TranslatableMessage('pricing.premium.price'),
                "text" => new TranslatableMessage('pricing.premium.text'),
                "button" => new TranslatableMessage('pricing.premium.button'),
            ]
        ];

        $details = [
            "unlimitedLinks" => [
                "title" => new TranslatableMessage('pricing.details.unlimitedLinks.title'),
                "free" => new TranslatableMessage('pricing.details.unlimitedLinks.free'),
                "starter" => new TranslatableMessage('pricing.details.unlimitedLinks.starter'),
                "premium" => new TranslatableMessage('pricing.details.unlimitedLinks.premium'),
            ],
            "personalization" => [
                "title" => new TranslatableMessage('pricing.details.personalization.title'),
                "free" => new TranslatableMessage('pricing.details.personalization.free'),
                "starter" => new TranslatableMessage('pricing.details.personalization.starter'),
                "premium" => new TranslatableMessage('pricing.details.personalization.premium'),
            ],
            "advancedPersonalization" => [
                "title" => new TranslatableMessage('pricing.details.advancedPersonalization.title'),
                "free" => new TranslatableMessage('pricing.details.advancedPersonalization.free'),
                "starter" => new TranslatableMessage('pricing.details.advancedPersonalization.starter'),
                "premium" => new TranslatableMessage('pricing.details.advancedPersonalization.premium'),
            ],
        ];

        return $this->render('default/pricing.html.twig');
    }

    /**
     * @Route("/help", name="app_help")
     */
    public function help(): Response
    {
        return $this->render('default/help.html.twig');
    }
    
    /**
     * @Route("/@{username}", name="app_view")
     */
    public function view(ManagerRegistry $doctrine, $username): Response
    {
        $repository = $doctrine->getRepository(User::class);
        $user = $repository->findByUsername($username);

        if (!$user) {
            $this->addFlash('warning', new TranslatableMessage("view.doNotExist"));
            return $this->redirectToRoute('app_home');
        }

        return $this->render('default/view.html.twig', [
            'user' => $user
        ]);
    }
}
