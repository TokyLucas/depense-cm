<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setAction('/connexion')
            ->add('identifiant', TextType::class, [
                'label' => 'Identifiant',
                'label_attr' => ['class' => 'mr-2'],
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('motdepasse', PasswordType::class, [
                'label' => 'Mot de passe',
                'label_attr' => ['class' => 'mr-2'],
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('seconnecter', SubmitType::class, [
                'label' => 'Se connecter.',
                'attr' => ['class' => 'btn btn-primary my-2 form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
