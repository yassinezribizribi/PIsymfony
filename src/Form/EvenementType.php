<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints as Assert;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreEven', TextType::class, [
                'label' => 'Titre de l\'événement',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Saisir le titre'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le titre de l\'événement est obligatoire.']),
                ],
            ])
            ->add('descriptionEven', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control', 'rows' => 5],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La description de l\'événement est obligatoire.']),
                ],
            ])
            ->add('dateEvenement', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de l\'événement',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La date est obligatoire.']),
                    new Assert\GreaterThan(['value' => 'today', 'message' => 'La date doit être dans le futur.']),
                ],
            ])
            ->add('lieu', TextType::class, [
                'label' => 'Lieu',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Lieu de l\'événement'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le lieu est obligatoire.']),
                ],
            ])
            ->add('nbLimiteParticipants', IntegerType::class, [
                'label' => 'Nombre limite de participants',
                'required' => false,
                'attr' => ['class' => 'form-control', 'min' => 1],
            ])
            ->add('prixevenement', MoneyType::class, [
                'currency' => 'EUR',
                'label' => 'Prix (€)',
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\PositiveOrZero(['message' => 'Le prix doit être positif ou nul.']),
                ],
            ])
            ->add('imageevenement', FileType::class, [
                'label' => 'Image de l\'événement',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('videoevenement', FileType::class, [
                'label' => 'Vidéo de l\'événement',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
