<?php

namespace App\Controller\Admin;

use App\Entity\LinkModel;
use App\Entity\LinkUser;
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
        yield MenuItem::linkToRoute(new TranslatableMessage('main.back'), 'fa-solid fa-arrow-left-to-line', 'app_home');
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section(new TranslatableMessage('admin.userTitle'));
        yield MenuItem::linkToCrud(new TranslatableMessage('admin.user'), 'fa solid fa-users', User::class);

        yield MenuItem::section(new TranslatableMessage('admin.modelTitle'));
        yield MenuItem::linkToCrud(new TranslatableMessage('admin.model'), 'fa solid fa-sitemap', LinkModel::class);
        
    }
}
