<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UpdatePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Mot De Passe actuel'
                ],
                'mapped' => false,
                'label' => 'Mot de passe actuel'
                ])
            ->add('newPassword',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nouveau Mot De Passe'
                ],
                'mapped' => false,
                'label' => 'Nouveau Mot de passe'
                ])
            ->add('confirmationPassword',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Confirmation nouveau Mot De Passe'
                ],
                'mapped' => false,
                'label' => 'Confirmation nouveau Mot De Passe : ',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
