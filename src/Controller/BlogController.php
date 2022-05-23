<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="app_blog")
     */
    public function home(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Article::class);
        $articles = $repository->findAll();

        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/blog/{slug}", name="app_blog_article")
     */
    public function readArticle(ManagerRegistry $doctrine, $slug)
    {
        $repository = $doctrine->getRepository(Article::class);
        $article = $repository->findOneBy(['slug' => $slug]);

        return $this->render('blog/read.html.twig', [
            'article' => $article,
        ]);
    }
}
