<?php

namespace App\Form;

use App\Entity\Personnel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PersonnelCRUDType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label_attr' => ['class' => 'mr-2'],
                'attr' => [
                    'class' => 'col form-control',
                    'placeholder' => 'Nom',
                ],
                'required' => false
            ])
            ->add('prenom', TextType::class, [
                'label_attr' => ['class' => 'mr-2'],
                'attr' => [
                    'class' => 'col form-control',
                    'placeholder' => 'Prenom',
                ],
                'required' => false
            ])
            ->add('CIN', TextType::class, [
                'label_attr' => ['class' => 'mr-2'],
                'attr' => [
                    'class' => 'col form-control',
                    'placeholder' => 'CIN',
                ],
                'required' => false
            ])
            ->add('Matricule', TextType::class, [
                'label_attr' => ['class' => 'mr-2'],
                'attr' => [
                    'class' => 'col form-control',
                    'placeholder' => 'Matricule',
                ],
                'required' => false
            ])
            ->add('datedenaissance', DateType::class, [
                'label_attr' => ['class' => 'mr-2'],
                'attr' => [
                    'class' => 'col form-control',
                    'placeholder' => 'Date de naissance',
                ],
                'required' => false
            ])
            ->add('nbenfant', NumberType::class, [
                'label_attr' => ['class' => 'mr-2'],
                'attr' => [
                    'class' => 'col form-control',
                    'placeholder' => 'Enfant',
                ],
                'required' => false
            ])
            ->add('rechercher', SubmitType::class, [
                'label' => 'Ajouter.',
                'attr' => ['class' => 'btn btn-primary my-2']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Personnel::class,
        ]);
    }
}
