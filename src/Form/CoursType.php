<?php

namespace App\Form;

use App\Entity\Cours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints as Assert;

class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreCours', null, [
                'label' => 'Titre du cours',
                'attr' => ['class' => 'form-control shadow-sm'],
            ])
            ->add('descriptionCours', null, [
                'label' => 'Description du cours',
                'attr' => ['class' => 'form-control shadow-sm'],
            ])
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début',
                'attr' => [
                    'class' => 'form-control shadow-sm js-datepicker',
                    'placeholder' => 'Choisir une date',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez renseigner la date de début.']),
                    new Assert\GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date de début ne peut pas être dans le passé.',
                    ]),
                ],
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
                'attr' => [
                    'class' => 'form-control shadow-sm js-datepicker',
                    'placeholder' => 'Choisir une date',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez renseigner la date de fin.']),
                    new Assert\GreaterThan([
                        'propertyPath' => 'parent.all[dateDebut].data',
                        'message' => 'La date de fin doit être postérieure à la date de début.',
                    ]),
                ],
            ])
            ->add('image', FileType::class, [
                'required' => false,
                'mapped' => false,
                'label' => 'Image (JPG, PNG)',
                'constraints' => [
                    new File([
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Veuillez télécharger une image JPG ou PNG valide.',
                    ]),
                ],
            ])
            ->add('typeContenu', ChoiceType::class, [
                'choices' => [
                    'PDF' => 'PDF',
                    'Vidéo' => 'Vidéo',
                ],
                'placeholder' => 'Choisir un type de contenu',
                'required' => true,
                'label' => 'Type de contenu',
            ])
            ->add('contenu', FileType::class, [
                'label' => 'Télécharger le contenu du cours (PDF ou vidéo)',
                'mapped' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'application/pdf',
                            'video/mp4',
                            'video/avi',
                            'video/mkv',
                            'video/x-matroska',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF ou une vidéo valide (MP4, AVI, MKV).',
                    ]),
                ],
            ])
            ->add('prix', MoneyType::class, [
                'currency' => 'EUR',
                'required' => true,
                'label' => 'Prix du cours',
                'attr' => ['class' => 'form-control shadow-sm'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
