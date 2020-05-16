<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',null,[
                'label'=>false,
                'attr'=>['placeholder'=>'Email']
                ])
            ->add('firstName',null,[
                'label'=>false,
                'attr'=>['placeholder'=>'Prénom']
                ])
            ->add('surname',null,[
                'label'=>false,
                'attr'=>['placeholder'=>'Nom']
                ])
            ->add('password',PasswordType::class,[
                'label'=>false,
                'attr'=>['placeholder'=>'Mot de passe']
                ])
            ->add('confirm_password',PasswordType::class,[
                'label'=>false,
                'attr'=>['placeholder'=>'Confirmation mot de passe']
                ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
