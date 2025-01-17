<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse mail',
                'attr' => ['placeholder' => 'Entrez votre adresse mail'],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => ['placeholder' => 'Entrez votre mot de passe'],
                    'hash_property_path' => 'password',
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe',
                    'attr' => ['placeholder' => 'Confirmez votre mot de passe'],
                ],
                'mapped' => false,
            ])
            ->add('firstname', TextType::class, [
                'label' => 'First Name',
                'attr' => ['placeholder' => 'Enter your first name']
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Last Name',
                'attr' => ['placeholder' => 'Enter your last name']
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' => [
                new UniqueEntity([
                    'entityClass' => User::class,
                    'fields' => 'email',
                ])
            ],
            'data_class' => User::class,
        ]);
    }
}
