<?php

namespace App\Controller;

use App\Entity\Link;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ReadmeController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/readme", name="app_readme")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $user = $this->security->getUser();
        $repository = $doctrine->getRepository(Link::class);
        $links = $repository->findAllForOneUser($user);

        return $this->render('readme/index.html.twig', [
            'links' => $links
        ]);
    }

    /**
     * @Route("/readme/view/{username}", name="app_view_readme")
     */
    public function view($username): Response
    {
        return $this->render('readme/view.html.twig', [
            'username' => $username
        ]);
    }
}
