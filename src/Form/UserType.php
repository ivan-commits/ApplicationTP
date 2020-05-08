<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',null,[
                'attr' => [
                    'placeholder'=>'Prénom',
                ],
                'label' => 'Enfant'])
            ->add('surname',null,[
                'attr' => [
                    'placeholder'=>'Nom de famille',
                ],
                'label' => false])
                ->add('date_de_naissance',DateType::class,[
                    'label' => false,
                    'years' => range(date('Y'), date('Y')-50),
                    'format' => 'yyyy-MM-dd',
                    'widget' => 'single_text',
    
                ])
            ->add('firstname_parent1',null,[
                'attr' => [
                    'placeholder'=>'Prénom Parent1',
                ],
                'label' => 'Parent n°1'])
            ->add('surname_parent1',null,[
                'attr' => [
                    'placeholder'=>'Nom Parent1',
                ],
                'label' => false])
            ->add('firstname_parent2',null,[
                'attr' => [
                    'placeholder'=>'Prénom Parent2',
                ],
                'label' => 'Parent n°2'])
            ->add('surname_parent2',null,[
                'attr' => [
                    'placeholder'=>'Nom Parent2',
                ],
                'label' => false])
            ->add('firstname_educateur',null,[
                'attr' => [
                    'placeholder'=>'Prénom éducateur',
                ],
                'label' => 'Éducateur'])
            ->add('surname_educateur',null,[
                'attr' => [
                    'placeholder'=>'Nom éducateur',
                ],
                'label' => false])
            ->add('firstname_orthophoniste',null,[
                'attr' => [
                    'placeholder'=>'Prénom orthophoniste',
                ],
                'label' => 'Orthophoniste'])
            ->add('surname_orthophoniste',null,[
                'attr' => [
                    'placeholder'=>'Nom orthophoniste',
                ],
                'label' => false])
            ->add('enabled',null,['label' => 'Compte activé']);
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
