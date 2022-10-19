<?php

namespace App\Form;

use App\Entity\Congee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Validator\Constraints;

class CongeeCRUDType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $notblank = new Constraints\NotBlank();
        $notblank->message = "Ce champ ne peut pas etre vide." ;
        $builder
            ->add('motif', TextareaType::class, [
                'label' => 'motif',
                'label_attr' => ['class' => 'mr-2'],
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'constraints' => array(
                    $notblank
                ),
            ])
            ->add('duree', NumberType::class, [
                'label' => 'duree',
                'label_attr' => ['class' => 'mr-2'],
                'attr' => [
                    'class' => 'form-control',
                    'onchange' => 'updateFin()'
                ],
                'required' => false,
                'constraints' => array(
                    $notblank
                ),
            ])
            ->add('datedebut', DateType::class, [
                'label_attr' => ['class' => 'mr-2'],
                'attr' => [
                    'class' => 'col js-datepicker form-control',
                    'placeholder' => 'datedebut',
                    'onchange' => 'updateFin()'
                ],
                'widget' => 'single_text' ,
                'years' => range(1900,2100),
                'required' => false,
                'constraints' => array(
                    $notblank
                ),
            ])
            ->add('datefin', DateType::class, [
                'label_attr' => ['class' => 'mr-2'],
                'attr' => [
                    'class' => 'col js-datepicker form-control',
                    'placeholder' => 'datefin',
                    'onchange' => 'updateDebut()'
                ],
                'widget' => 'single_text' ,
                'years' => range(1900,2100),
                'required' => false,
                'constraints' => array(
                    $notblank
                ),
            ])
            ->add('personnel_id', NumberType::class, [
                'label' => ' ',
                'attr' => [
                    'hidden' => true,
                    'value' => $options['data']['personnel']
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter',
                'attr' => [
                    'class' => 'col form-control btn btn-primary'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
