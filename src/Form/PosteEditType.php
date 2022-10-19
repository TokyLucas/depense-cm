<?php

namespace App\Form;

use App\Entity\Poste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Validator\Constraints;

class PosteEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $notblank = new Constraints\NotBlank();
        $notblank->message = "Ce champ ne peut pas etre vide." ;
        $builder
        ->add('designation', TextType::class, [
            'label' => 'Poste',
            'label_attr' => ['class' => 'mr-2'],
            'attr' => [
                'class' => 'col form-control',
                'placeholder' => 'Poste'
            ],
            'required' => false,
            'constraints' => array(
                $notblank
            ),
        ])
        ->add('direction_id', ChoiceType::class, [
            'label' => 'Direction',
            'attr' => [
                'class' => 'col form-control',
                'placeholder' => 'Direction',
            ],
            'preferred_choices' => [$options['data']->getDirectionId()],
            'choices' => $options['choices']["direction"],
            'mapped' => false,
            'expanded' => false,
            'multiple' => false,
            'constraints' => array(
                $notblank
            ),
        ])
        ->add('contrat_id', ChoiceType::class, [
            'label' => 'Contrat',
            'label_attr' => ['class' => 'mr-2'],
            'attr' => [
                'class' => 'col form-control',
                'placeholder' => 'Contrat',
            ],
            'preferred_choices' => [$options['data']->getContratId()],
            'choices' => $options['choices']["contrat"],
            'mapped' => false,
            'expanded' => false,
            'multiple' => false,
            'constraints' => array(
                $notblank
            ),
        ])
        ->add('indice', TextType::class, [
            'label_attr' => ['class' => 'mr-2'],
            'attr' => [
                'class' => 'col form-control',
                'placeholder' => 'Indice',
            ],
            'required' => false,
            'constraints' => array(
                $notblank
            ),
        ])
        ->add('categorie', NumberType::class, [
            'label_attr' => ['class' => 'mr-2'],
            'attr' => [
                'class' => 'col form-control',
                'placeholder' => 'Categorie',
            ],
            'required' => false,
            'constraints' => array(
                $notblank
            ),
        ])
        ->add('grade', TextType::class, [
            'label_attr' => ['class' => 'mr-2'],
            'attr' => [
                'class' => 'col form-control',
                'placeholder' => 'Grade',
                'value' => 'I'
            ],
            'required' => false,
            'constraints' => array(
                $notblank
            ),
        ])
        ->add('matricule', NumberType::class, [
            'label_attr' => ['class' => 'mr-2'],
            'attr' => [
                'class' => 'col form-control',
                'placeholder' => 'Matricule',
                'value' => 0
            ],
            'required' => false,
        ])
        ->add('service_id', ChoiceType::class, [
            'label' => 'Service',
            'label_attr' => ['class' => 'mr-2'],
            'attr' => [
                'class' => 'col form-control',
                'placeholder' => 'Contrat',
            ],
            'preferred_choices' => [$options['data']->getServiceId()],
            'choices' => $options['choices']["service"],
            'mapped' => false,
            'expanded' => false,
            'multiple' => false,
            'constraints' => array(
                $notblank
            ),
        ])
        ->add('datefin', DateType::class, [
            'label_attr' => ['class' => 'mr-2'],
            'attr' => [
                'class' => 'col js-datepicker form-control',
                'placeholder' => 'Date de fin',
                'value' => 0
            ],
            'widget' => 'single_text' ,
            'years' => range(1900,2100),
            'required' => false,
            'mapped' => false
        ])
        ->add('nbjourdetravailtemporaire', NumberType::class, [
            'label' => 'Nombre de jour de travail',
            'label_attr' => ['class' => 'mr-2'],
            'attr' => [
                'class' => 'col form-control',
                'placeholder' => 'Nombre de jour de travail',
                'value' => 0
            ],
            'required' => false,
            'mapped' => false
        ])
        ->add('heureparjour', NumberType::class, [
            'label' => 'Heure par jour',
            'label_attr' => ['class' => 'mr-2'],
            'attr' => [
                'class' => 'col form-control',
                'placeholder' => 'Heure par jour',
                'value' => 0
            ],
            'required' => false,
            'mapped' => false
        ])
        ->add('modifier', SubmitType::class, [
            'attr' => [
                'class' => 'col btn btn-primary my-2',
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Poste::class,
            'choices' => []
        ]);
        $resolver->setAllowedTypes('choices', 'array');
    }
}
