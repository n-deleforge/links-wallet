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
     * @Route(
     *     "/{_locale}/",
     *     name="app_home",
     *     requirements={
     *         "_locale": "en|fr",
     *     }
     * )
     */
    public function home(): Response
    {
        return $this->render('default/home.html.twig');
    }

    /**
     * @Route(
     *     "/{_locale}/princing",
     *     name="app_pricing",
     *     requirements={
     *         "_locale": "en|fr",
     *     }
     * )
     */
    public function pricing(): Response
    {
        return $this->render('default/pricing.html.twig');
    }

    /**
     * @Route(
     *     "/{_locale}/help",
     *     name="app_help",
     *     requirements={
     *         "_locale": "en|fr",
     *     }
     * )
     */ 
    public function help(): Response
    {
        return $this->render('default/help.html.twig');
    }

    /**
     * @Route(
     *     "/{_locale}/@{username}",
     *     name="app_view",
     *     requirements={
     *         "_locale": "en|fr",
     *     }
     * )
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
