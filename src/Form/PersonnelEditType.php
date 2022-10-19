<?php

namespace App\Form;

use App\Entity\Personnel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Validator\Constraints;

class PersonnelEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $notblank = new Constraints\NotBlank();
        $notblank->message = "Ce champ ne peut pas etre vide." ;
        $builder->add('nom', TextType::class, [
            'label_attr' => ['class' => 'mr-2'],
            'attr' => [
                'class' => 'col form-control',
                'placeholder' => 'Nom',
            ],
            'required' => false,
            'constraints' => array(
                $notblank
            ),
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
            'label' => 'CIN',
            'label_attr' => ['class' => 'mr-2'],
            'attr' => [
                'class' => 'col form-control',
                'placeholder' => 'CIN',
            ],
            'required' => false,
            'constraints' => array(
                $notblank
            ),
        ])
        ->add('datedenaissance', DateType::class, [
            'label' => 'Date de naissance',
            'label_attr' => ['class' => 'mr-2'],
            'attr' => [
                'class' => 'col js-datepicker form-control',
                'placeholder' => 'Date de naissance',
            ],
            'widget' => 'single_text' ,
            'years' => range(1900,2100),
            'required' => false,
            'constraints' => array(
                $notblank
            ),
        ])
        ->add('lieudenaissance', TextType::class, [
            'label' => 'Lieu de naissance',
            'label_attr' => ['class' => 'mr-2'],
            'attr' => [
                'class' => 'col form-control',
                'placeholder' => 'Lieu de naissance',
            ],
            'required' => false,
            'constraints' => array(
                $notblank
            ),
        ])
        ->add('nbenfant', NumberType::class, [
            'label' => 'Nombre d\'enfant',
            'label_attr' => ['class' => 'mr-2'],
            'attr' => [
                'class' => 'col form-control',
                'placeholder' => 'Enfant',
            ],
            'required' => false,
            'constraints' => array(
                $notblank
            ),
        ])
        ->add('sexe_id', ChoiceType::class, [
            'label' => 'Sexe',
            'label_attr' => ['class' => 'mr-2'],
            'attr' => [
                'class' => 'col form-control',
                'placeholder' => 'Sexe',
            ],
            'choices' => $options['choices']["sexe"],
            'mapped' => false,
            'expanded' => false,
            'multiple' => false,
        ])
        ->add('situationmatrimoniale_id', ChoiceType::class, [
            'label' => 'Situation matrimoniale',
            'label_attr' => ['class' => 'mr-2'],
            'attr' => [
                'class' => 'col form-control',
                'placeholder' => 'Situation matrimoniale',
            ],
            'choices' => $options['choices']["situationmatrimoniale"],
            'mapped' => false,
            'expanded' => false,
            'multiple' => false,
        ])
        ->add('modifier', SubmitType::class, [
            'attr' => [
                'class' => 'col btn btn-primary my-2',
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Personnel::class,
            'choices' => []
        ]);
        $resolver->setAllowedTypes('choices', 'array');
    }
}
