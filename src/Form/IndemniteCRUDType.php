<?php

namespace App\Form;

use App\Entity\IndemnitePersonnel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

use Symfony\Component\Validator\Constraints;

class IndemniteCRUDType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $notblank = new Constraints\NotBlank();
        $notblank->message = "Ce champ ne peut pas etre vide." ;
        $builder
            ->add('personnelId', NumberType::class, [
                'label' => ' ',
                'label_attr' => ['class' => 'mr-2'],
                'attr' => [
                    'class' => 'form-control',
                    'value' => $options['data']["personnel_id"],
                    'hidden' => true,
                ],
                'constraints' => array(
                    $notblank
                ),
            ])
            ->add('IndemniteId', ChoiceType::class, [
                'label_attr' => ['class' => 'mr-2'],
                'attr' => [
                    'class' => 'col form-control',
                ],
                'choices' => $options['data']["indemnite"],
                'mapped' => false,
                'expanded' => false,
                'multiple' => false,
                'constraints' => array(
                    $notblank
                ),
            ])
            ->add('montant', NumberType::class, [
                'label' => 'Montant',
                'label_attr' => ['class' => 'mx-2'],
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'constraints' => array(
                    $notblank
                ),
            ])
            ->add('ajouter', SubmitType::class, [
                'label' => 'Ajouter',
                'attr' => ['class' => 'form-control btn btn-primary my-2'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
