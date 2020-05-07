<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',null,['label' => false])
            ->add('surname',null,['label' => false])
            ->add('firstname_parent1',null,['label' => false])
            ->add('surname_parent1',null,['label' => false])
            ->add('firstname_parent2',null,['label' => false])
            ->add('surname_parent2',null,['label' => false])
            ->add('firstname_educateur',null,['label' => false])
            ->add('surname_educateur',null,['label' => false])
            ->add('firstname_orthophoniste',null,['label' => false])
            ->add('surname_orthophoniste',null,['label' => false])
            ->add('date_de_naissance',DateType::class,[
                'label' => false,
                'years' => range(date('Y'), date('Y')-50),
                'format' => 'yyyy-MM-dd',
                'widget' => 'single_text',

            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
