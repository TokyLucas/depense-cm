<?php

namespace App\Form;

use App\Entity\Bareme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BaremeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->setAction('/bareme/import')
        ->add('file', FileType::class, [
            'label' => 'Bareme',
            'label_attr' => ['class' => 'mr-2'],
            // unmapped means that this field is not associated to any entity property
            'mapped' => false,
            'attr' => ['class' => ''],
            // make it optional so you don't have to re-upload the PDF file
            // every time you edit the Product details
            'required' => false,

            // unmapped fields can't define their validation using annotations
            // in the associated entity, so you can use the PHP constraint classes
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'application/xlsx',
                        'application/xls',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid Excel document',
                ])
            ],
        ])
        ->add('Importer', SubmitType::class, [
            'attr' => ['class' => 'btn btn-primary']
        ])
        // ...
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bareme::class,
        ]);
    }
}
