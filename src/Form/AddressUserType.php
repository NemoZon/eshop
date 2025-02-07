<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class AddressUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Entrez votre prénom',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le prénom est requis',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le prénom doit contenir au moins {{ limit }} caractères',
                        'max' => 50,
                    ]),
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Entrez votre nom',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom est requis',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères',
                        'max' => 50,
                    ]),
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'Entrez votre adresse',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'L\'adresse est requise',
                    ]),
                ],
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code Postal',
                'attr' => [
                    'placeholder' => 'Entrez votre code postal',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le code postal est requis',
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Le code postal doit contenir au moins {{ limit }} caractères',
                        'max' => 10,
                    ]),
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Entrez votre ville',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La ville est requise',
                    ]),
                ],
            ])
            ->add('country', CountryType::class, [
                'label' => 'Pays',
                'attr' => [
                    'placeholder' => 'Entrez votre pays',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le pays est requis',
                    ]),
                ],
            ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone',
                'attr' => [
                    'placeholder' => 'Entrez votre numéro de téléphone',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le numéro de téléphone est requis',
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Le numéro de téléphone doit contenir au moins {{ limit }} caractères',
                        'max' => 15,
                    ]),
                ],
            ])
            ->add('Valider', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
