<?php

namespace App\Controller\Admin;

use App\Entity\LinkModel;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LinkModelCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return LinkModel::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
