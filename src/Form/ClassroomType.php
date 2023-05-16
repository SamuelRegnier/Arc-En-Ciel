<?php

namespace App\Form;

use App\Entity\Classroom;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\UserRepository;

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
                'label' => 'Photo',
                'download_uri' => false,
                'delete_label' => false,
                'image_uri' => true,
                'required' => false,
                //'delete' => false,
                ],
                ) 
            ->add('user',EntityType::class,[
                'class' => User::class,
                'choice_label' => 
                        function($user, $key, $index) {
                            if ($user->getClassroom() == null && $user->isIsActive() == true){
                                return $user->getlastName() . ' ' . $user->getfirstName();
                            }
                        },
                'label' => 'Enseignant : ',
                'placeholder' => 'Selectionner l\'enseignant',
                'required' => false,
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
