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
    public function index(): Response
    {
        $generator = new Generator;
        $lorem = $generator->getParagraphs(2);

        return $this->render('default/index.html.twig', [
            "lorem" => $lorem
        ]);
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
