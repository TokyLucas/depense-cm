<?php

namespace App\Form;

use App\Entity\Personnel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PersonnelSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setAction('/personnel')
            ->setMethod('POST')
            ->add('nom', TextType::class, [
                'label_attr' => ['class' => 'mr-2'],
                'attr' => [
                    'class' => 'col mx-2 form-control',
                    'placeholder' => 'Nom',
                ],
                'required' => false
            ])
            ->add('prenom', TextType::class, [
                'label_attr' => ['class' => 'mr-2'],
                'attr' => [
                    'class' => 'col mx-2 form-control',
                    'placeholder' => 'Prenom',
                ],
                'required' => false
            ])
            ->add('direction', ChoiceType::class, [
                'attr' => [
                    'class' => 'col mx-2 form-control',
                    'placeholder' => 'Direction',
                ],
                'choices' => $options['data']["direction"],
                'mapped' => false,
                'expanded' => false,
                'multiple' => false
            ])
            ->add('contrat', ChoiceType::class, [
                'label_attr' => ['class' => 'mr-2'],
                'attr' => [
                    'class' => 'col mx-2 form-control',
                    'placeholder' => 'Contrat',
                ],
                'choices' => $options['data']["contrat"],
                'mapped' => false,
                'expanded' => false,
                'multiple' => false
            ])
            ->add('indice', NumberType::class, [
                'label_attr' => ['class' => 'mr-2'],
                'attr' => [
                    'class' => 'col mx-2 form-control',
                    'placeholder' => 'Indice',
                ],
                'required' => false
            ])
            ->add('rechercher', SubmitType::class, [
                'label' => 'Rechercher.',
                'attr' => ['class' => 'btn btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
