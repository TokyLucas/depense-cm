<?php

namespace App\Form;

use App\Entity\BaremePersonnelTemporaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Validator\Constraints;

class BaremePersoTempType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $notblank = new Constraints\NotBlank();
        $notblank->message = "Ce champ ne peut pas etre vide." ;
        $builder
        ->add('file', FileType::class, [
            'label' => 'Bareme EMO',
            'label_attr' => ['class' => 'mr-2'],
            // unmapped means that this field is not associated to any entity property
            'mapped' => false,
            'attr' => [
                'class' => 'custom-file',
                'accept' => ".xlsx, .xls, .csv"
            ],
            // make it optional so you don't have to re-upload the PDF file
            // every time you edit the Product details
            'required' => false,

            // unmapped fields can't define their validation using annotations
            // in the associated entity, so you can use the PHP constraint classes
            'constraints' => [
                $notblank
            ],
        ])
        ->add('Importer', SubmitType::class, [
            'attr' => ['class' => 'btn btn-primary']
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
