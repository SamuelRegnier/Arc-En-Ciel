<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom de famille'
                ],
                    'label' => 'Nom'
                ],
                )
            ->add('firstName', TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Prénom'
                ],
                'label' => 'Prénom'
                ])
            ->add('email', TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Email'
                ],
                    'label' => 'Email'
                ])
            ->add('phone', TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Téléphone'
                ],
                    'label' => 'Téléphone'
                ]
                )
            ->add('adress', TextareaType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Adresse'
                ],
                    'label' => 'Adresse'
                ],
                )
            ->add('isActive', ChoiceType::class,[
                'expanded' => true,
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                    'label' => 'Actif'
                ],
                )
            ->add('password',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Mot De Passe'
                ],
                    'label' => 'Mot de passe'
                ],
                [
                'mapped' => false,
                'label' => 'Mot De Passe : ',
                'attr' => ['autocomplete' => 'new-password']
                ])
            ->add('picture',FileType::class,[
                'attr' => [
                    'class' => 'formFile',
                ],
                    'label' => 'Photo'
                ],
                )
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'badge bg-primary'
                    ],
                    'label' => 'Créer profil'
                ],
                )
        ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
