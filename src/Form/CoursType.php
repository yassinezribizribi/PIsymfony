<?php

namespace App\Form;

use App\Entity\Cours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;  // Ajout du type MoneyType
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints as Assert;

class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreCours')
            ->add('descriptionCours')
            ->add('dateDebut')
            ->add('dateFin')
            ->add('image', FileType::class, [
                'required' => false,
                'mapped' => false,
                'label' => 'Image (JPG, PNG)',
            ])
            
            ->add('typeContenu', ChoiceType::class, [
                'choices' => [
                    'PDF' => 'PDF',
                    'Vidéo' => 'Vidéo',
                ],
                'placeholder' => 'Choisir un type de contenu',
                'required' => true,
            ])
            ->add('contenu', FileType::class, [
                'label' => 'Télécharger le contenu du cours (PDF ou vidéo)',
                'mapped' => false,  // Ce champ n'est pas directement lié à l'entité
                'constraints' => [
                    new File([
                        'mimeTypes' => ['application/pdf', 'video/mp4'],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF ou vidéo valide.',
                    ])
                ],
            ])
            ->add('prix', MoneyType::class, [  // Ajout du champ prix
                'currency' => 'EUR',  // Vous pouvez modifier la devise ici si nécessaire
                'required' => true,
                'label' => 'Prix du cours',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
