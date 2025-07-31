<?php

namespace App\Form;

use App\Entity\Devis;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('email')
            ->add('localisation')
            ->add('typeProjet')
            ->add('surface', ChoiceType::class, [
                'choices' => [
                    'Moins de 50 m²' => 'moins_50',
                    '50 à 100 m²' => '50_100',
                    'Plus de 100 m²' => 'plus_100',
                ],
                'placeholder' => 'Sélectionnez une surface',
                'required' => false
            ])
            ->add('delai', TextType::class, ['required' => false])
            ->add('description', TextareaType::class)
            ->add('createdAt')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
        ]);
    }
}
