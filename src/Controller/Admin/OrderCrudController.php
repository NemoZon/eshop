<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Commande')
            ->setEntityLabelInPlural('Commandes');
    }

    public function configureActions(\EasyCorp\Bundle\EasyAdminBundle\Config\Actions $actions): \EasyCorp\Bundle\EasyAdminBundle\Config\Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW )
            ->remove(Crud::PAGE_INDEX, Action::DELETE);
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateField::new('createdAt', 'Date de la commande'),
            NumberField::new('status', 'Statut')->setTemplatePath('admin/field/status.html.twig'),
            AssociationField::new('user', 'Client'),
            TextField::new('carrierName', 'Transporteur'),
            NumberField::new('totalWt', 'Prix total TTC'),
        ];
    }
}
