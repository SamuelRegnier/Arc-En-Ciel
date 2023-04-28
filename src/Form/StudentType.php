<?php

namespace App\Form;

use App\Entity\Student;
use App\Entity\Classroom;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class StudentType extends AbstractType
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
            ->add('birthday', TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Date de naissance'
                ],
                'label' => 'Date de naissance'
                ])
            ->add('pai', TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'PAI',
                ],
                'label' => 'PAI',
                'required' => false
                ])
            ->add('descriptionPai', TextareaType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Description PAI'
                ],
                    'label' => 'Description PAI',
                    'required' => false
                ],
                )
            ->add('allergy', TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Allergies'
                ],
                'label' => 'Allergies',
                'required' => false
                ])
            ->add('descriptionAllergy', TextareaType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Description allergies'
                ],
                    'label' => 'Description allergies',
                    'required' => false
                ],
                )
            ->add('outdoorGlasses', TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Lunettes extérieures'
                ],
                'label' => 'Lunettes extérieures'
                ])
            ->add('imageFile',VichImageType::class,[
                'attr' => [
                    'class' => 'formFile',
                ],
                    'label' => 'Photo',
                ],
                )
            ->add('classroom',EntityType::class,[
                'class' => Classroom::class,
                'choice_label' => function($classroom, $key, $index) {
                    return $classroom->getName() . ' ' . $classroom->getYear();
                },
                'label' => 'Classe : ',
                ],
                )
            ->add('users',EntityType::class,[
                'class' => User::class,
                'choice_label' => function($users, $key, $index) {
                    return $users->getlastName() . ' ' . $users->getfirstName();
                },
                'label' => 'Parents : ',
                ],
                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
