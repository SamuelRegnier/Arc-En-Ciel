<?php

namespace App\Form;

use App\Entity\Classroom;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ClassroomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'Nom de la classe'
            ],
                'label' => 'Nom'
            ],
            )
            ->add('year', TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Année/Année'
                ],
                'label' => 'Année'
                ])
            ->add('imageFile',VichImageType::class,[
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
                    'label' => 'Créer classe'
                ],
                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Classroom::class,
        ]);
    }
}
