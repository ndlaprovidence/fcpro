<?php

namespace App\Form;

use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //aa
        $builder
            ->add('name')
            ->add('content')
            ->add('capacity')
            ->add('capacityMin')
            ->add('price')
            ->add('createdAt')
            ->add('startDate')
            ->add('endDate')
            ->add('place')
            ->add('description')
            ->add('speaker')
            ->add('createdBy')
            ->add('objectif')
            ->add('prerequis')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
