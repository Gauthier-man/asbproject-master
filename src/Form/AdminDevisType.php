<?php

namespace App\Form;

use App\Entity\Devis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminDevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'En cours d’étude' => 'en_cours',
                    'Accepté' => 'accepte',
                    'Refusé' => 'refuse',
                ],
                'placeholder' => 'Choisir un statut'
            ])
            ->add('estimatedPrice', TextType::class, [
                'required' => false,
                'label' => 'Prix estimé (€)'
            ])
            ->add('adminNote', TextareaType::class, [
                'required' => false,
                'label' => 'Note pour le client'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
        ]);
    }
}
