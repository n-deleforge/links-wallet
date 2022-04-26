<?php

namespace App\Controller;

use Badcow\LoremIpsum\Generator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {

        $generator = new Generator;
        $paragraph = $generator->getParagraphs(2);

        return $this->render('default/index.html.twig', [
            "lorem" => $paragraph
        ]);
    }
}
