<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('firstName')
            ->add('surname')
            ->add('firstname_parent1')
            ->add('surname_parent1')
            ->add('firstname_parent2')
            ->add('surname_parent2')
            ->add('firstname_educateur')
            ->add('surname_educateur')
            ->add('firstname_orthophoniste')
            ->add('surname_orthophoniste')
            ->add('enabled')
            ->add('date_de_naissance')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
