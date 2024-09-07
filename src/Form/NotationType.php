<?php

namespace App\Form;

use App\Entity\Notation;
use App\Entity\Formation;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class NotationType extends AbstractType
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        $builder
            ->add('formation', EntityType::class, [
                'class' => Formation::class,
                'query_builder' => function (FormationRepository $er) {
                    return $er->createQueryBuilder('f')
                        ->andWhere('f.validation = :validation')
                        ->setParameter('validation', 1);
                },
                'choice_label' => 'name',
            ])
            ->add('user', HiddenType::class, [
                'data' => $user,
            ])
            ->add('note', HiddenType::class, [
                'data' => 1, // La valeur par défaut peut être modifiée si nécessaire
                'attr' => ['class' => 'selected-rating'], // Ajout de la classe CSS
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Notation::class,
        ]);
    }
}