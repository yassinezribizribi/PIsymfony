<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenomUser')
            ->add('email')
            ->add('pwd')
            ->add('dateInscri', null, [
                'widget' => 'single_text'
            ])
            ->add('role', ChoiceType::class, [ // Ajout du ChoiceType
                'choices' => [
                    'Étudiant' => 'etudiant',   // Clé : affichée dans la liste
                    'Enseignant' => 'enseignant', // Valeur : stockée dans l'entité
                    'Autre' => 'autre',
                ],
                'label' => 'Rôle', // Étiquette du champ
                'placeholder' => 'Sélectionnez un rôle', // Optionnel : valeur par défaut
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}