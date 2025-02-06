<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits');
    }


    public function configureFields(string $pageName): iterable
    {
        $required = true;
        if ($pageName === 'edit') {
            $required = false;
        }
        return [
            TextField::new('name', 'Nom')->setHelp('Nom du produit'),
            SlugField::new('slug', 'URL')->setTargetFieldName('name'),
            TextEditorField::new('description', 'Description'),
            ImageField::new('illustration', 'Image')
                ->setHelp('Image 600x600')
                ->setUploadDir('public/uploads')
                ->setBasePath('/uploads')
                ->setRequired($required)
                ->setUploadedFileNamePattern('[year]-[month]-[contenthash].[extension]'),
            NumberField::new('price', 'Prix'),
            ChoiceField::new('tva')
                ->setChoices([
                    '5,5%' => '5.5',
                    '10%' => '10',
                    '20%' => '20',
                ]),
            AssociationField::new('category', 'Categorie associée')


        ];
    }

}
