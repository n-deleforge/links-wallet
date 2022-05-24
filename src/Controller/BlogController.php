<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog/{page<\d+>}", name="app_blog")
     */
    public function blog(ManagerRegistry $doctrine, int $page = 1): Response
    {
        $repository = $doctrine->getRepository(Article::class);
        $articles = $repository->findAll();

        $adapter = new ArrayAdapter($articles);
        $pager = new Pagerfanta($adapter);
        $pager
            ->setMaxPerPage(4)
            ->setCurrentPage($page);

        return $this->render('blog/index.html.twig', [
            'pager' => $pager,
        ]);
    }

    /**
     * @Route("/blog/{slug}", name="app_blog_article")
     */
    public function article(ManagerRegistry $doctrine, $slug)
    {
        $repository = $doctrine->getRepository(Article::class);
        $article = $repository->findOneBy(['slug' => $slug]);

        return $this->render('blog/read.html.twig', [
            'article' => $article,
        ]);
    }
}
