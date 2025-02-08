<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('addresses', EntityType::class, [
                'class' => Address::class,
                'required' => true,
                'expanded' => true,
                'label_html' => true,
                'label' => 'Choisissez une adresse de livraison',
                'choices' => $options['addresses'],
            ])
            ->add('carriers', EntityType::class, [
                'class' => Carrier::class,
                'required' => true,
                'expanded' => true,
                'label_html' => true,
                'label' => 'Mode de livraison',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider ma commande',
                'attr' => [
                    'class' => 'btn btn-success',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'addresses' => null,
        ]);
    }
}
