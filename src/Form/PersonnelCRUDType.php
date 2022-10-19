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

class PersonnelCRUDType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $notblank = new Constraints\NotBlank();
        $notblank->message = "Ce champ ne peut pas etre vide." ;
        $builder
            ->add('nom', TextType::class, [
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
            ->add('sexe', ChoiceType::class, [
                'label_attr' => ['class' => 'mr-2'],
                'attr' => [
                    'class' => 'col form-control',
                    'placeholder' => 'Sexe',
                ],
                'choices' => $options['data']["sexe"],
                'mapped' => false,
                'expanded' => false,
                'multiple' => false,
                'constraints' => array(
                    $notblank
                ),
            ])
            ->add('situationmatrimoniale', ChoiceType::class, [
                'label' => 'Situation matrimoniale',
                'label_attr' => ['class' => 'mr-2'],
                'attr' => [
                    'class' => 'col form-control',
                    'placeholder' => 'Situation matrimoniale',
                ],
                'choices' => $options['data']["situationmatrimoniale"],
                'mapped' => false,
                'expanded' => false,
                'multiple' => false,
                'constraints' => array(
                    $notblank
                ),
            ])
            ->add('poste', TextType::class, [
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
            ->add('direction', ChoiceType::class, [
                'attr' => [
                    'class' => 'col form-control',
                    'placeholder' => 'Direction',
                ],
                'choices' => $options['data']["direction"],
                'mapped' => false,
                'expanded' => false,
                'multiple' => false,
                'constraints' => array(
                    $notblank
                ),
            ])
            ->add('contrat', ChoiceType::class, [
                'label_attr' => ['class' => 'mr-2'],
                'attr' => [
                    'class' => 'col form-control',
                    'placeholder' => 'Contrat',
                    'onchange' => 'toggledisable()'
                ],
                'choices' => $options['data']["contrat"],
                'mapped' => false,
                'expanded' => false,
                'multiple' => false,
                'constraints' => array(
                    $notblank
                ),
            ])
            ->add('indice', NumberType::class, [
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
            ])
            ->add('ajouter', SubmitType::class, [
                'label' => 'Ajouter.',
                'attr' => ['class' => 'btn btn-primary my-2']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
