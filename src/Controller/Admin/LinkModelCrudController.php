<?php

namespace App\Controller\Admin;

use App\Entity\LinkModel;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LinkModelCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return LinkModel::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('icon'),
            TextField::new('url')
        ];
    }
}
