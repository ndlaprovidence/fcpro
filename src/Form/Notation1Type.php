<?php

namespace App\Form;

use App\Entity\Notation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class Notation1Type extends AbstractType
{
    private Security $security;
    private EntityManagerInterface $entityManager;  // Assurez-vous que c'est bien Doctrine\ORM\EntityManagerInterface

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        $builder
            ->add('formation', EntityType::class, [
                'class' => Formation::class,
                'query_builder' => function(FormationRepository $er) {
                    return $er->createQueryBuilder('f')
                        ->andWhere('f.validation = :validation')
                        ->setParameter('validation', 1);
                },
                'choice_label' => 'name',
            ])
            ->add('user', HiddenType::class, [
                'data' => $user,
            ])
            ->add('note', ChoiceType::class, [
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
                'placeholder' => 'Choisir une note', // Optionnel, ajoutez ceci si vous voulez un libellé de choix par défaut
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Notation::class,
        ]);
    }
}