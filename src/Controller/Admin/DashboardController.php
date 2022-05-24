<?php

namespace App\Controller\Admin;

use App\Controller\BlogController;
use App\Entity\Article;
use App\Entity\Model;
use App\Entity\Tag;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatableMessage;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Readme');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute(new TranslatableMessage('main.back'), 'fa solid fa-arrow-left', 'app_home');

        yield MenuItem::section(new TranslatableMessage('admin.navbar.userTitle'));
        yield MenuItem::linkToCrud(new TranslatableMessage('admin.navbar.user'), '', User::class);

        yield MenuItem::section(new TranslatableMessage('admin.navbar.modelTitle'));
        yield MenuItem::linkToCrud(new TranslatableMessage('admin.navbar.model'), '', Model::class);

        yield MenuItem::section(new TranslatableMessage('admin.navbar.blogTitle'));
        yield MenuItem::linkToCrud(new TranslatableMessage('admin.navbar.article'), '', Article::class);
        yield MenuItem::linkToCrud(new TranslatableMessage('admin.navbar.tag'), '', Tag::class);
    }
}
