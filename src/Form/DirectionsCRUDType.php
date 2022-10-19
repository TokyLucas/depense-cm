<?php

namespace App\Form;

use App\Entity\Directions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Validator\Constraints;

class DirectionsCRUDType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $notblank = new Constraints\NotBlank();
        $notblank->message = "Ce champ ne peut pas etre vide." ;
        $builder
            ->add('designation', TextType::class, [
                'label' => 'Designation',
                'label_attr' => ['class' => 'mr-2'],
                'attr' => ['class' => 'form-control'],
                'constraints' => array(
                    $notblank
                ),
            ])
            ->add('ajouter', SubmitType::class, [
                'label' => 'Ajouter',
                'attr' => ['class' => 'btn btn-primary my-2']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Directions::class,
        ]);
    }
}
