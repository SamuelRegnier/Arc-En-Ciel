<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateProfilType extends AbstractType
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
                ]
                )
            ->add('imageFile',VichImageType::class,[
                'attr' => [
                    'class' => 'formFile',
                ],
                'label' => 'Photo',
                'download_uri' => false,
                'delete_label' => false,
                'image_uri' => true,
                'required' => false,
                ],
                )
            ->add('roles', ChoiceType::class,[
                'attr' => [
                    'class' => 'form-select',
                ],
                'expanded' => false,
                'multiple' => true,
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Enseignant' => 'ROLE_ENSEIGNANT',
                    'Bénévole' => 'ROLE_BENEVOLE',
                    'Parent' => 'ROLE_PARENT',
                ],
                'label' => 'Role'
                ]
                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
