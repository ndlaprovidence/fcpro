<?php

namespace App\Form;

use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class FormationType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name');
        $builder->add('content', TinymceType::class);
        $builder->add('capacityMin');
        $builder->add('capacity');
        $builder->add('price');
        // $builder->add('createdAt');
        $builder->add('description');
        $builder->add('startDateTime', DateTimeType::class, [
            'widget' => 'single_text',
            'html5' => true,
            'required' => false,
        ]);

        $builder->add('endDateTime', DateTimeType::class, [
            'widget' => 'single_text',
            'html5' => true,
            'required' => false,
        ]);
        $builder->add('place');
        $builder->add('speaker');
        $builder->add('createdBy');
        //Partie PDF
        $builder->add('objectif', TinymceType::class, [
            'required' => false,
        ]);
        $builder->add('prerequis', TinymceType::class, ['required' => false, 'label' => 'Prérequis']);
        $builder->add('moyenPedagogique', TinymceType::class, ['required' => false, 'label' => 'Moyen pédagogique']);
        $builder->add('evaluation', TinymceType::class, ['required' => false, 'label' => 'Méthode d\'évaluation']);
        $builder->add('modalites', TinymceType::class, ['required' => false, 'label' => 'Modalités']);
        $builder->add('format', TinymceType::class, ['required' => false, 'label' => 'Format (champ modalités dans la partie grise à gauche du pdf)']);
        $builder->add('accessibilite', TinymceType::class, ['required' => false, 'label' => 'Accessiblité']);
        $builder->add('contact', TinymceType::class, ['required' => false, 'label' => 'Contact']);

        $builder->add('image', FileType::class, [
            'label' => $this->translator->trans('Illustration'),

            // unmapped means that this field is not associated to any entity property
            'mapped' => false,

            // make it optional so you don't have to re-upload the PDF file
            // every time you edit the Product details
            'required' => false,

            // unmapped fields can't define their validation using annotations
            // in the associated entity, so you can use the PHP constraint classes
            'constraints' => [
                new File([
                    'maxSize' => '2048k',
                    'mimeTypes' => [
                        'image/jpg',
                        'image/jpeg',
                        'image/png',
                    ],
                    'mimeTypesMessage' => $this->translator->trans('Please upload a valid image file'),
                ])
            ],
        ]);

        $builder->add('validation', CheckboxType::class, [
            'label' => 'Valider la formation',
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
