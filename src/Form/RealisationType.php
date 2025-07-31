<?php
// src/Form/RealisationType.php
namespace App\Form;

use App\Entity\Realisation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RealisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Charpente' => 'charpente',
                    'Maison Ossature Bois' => 'maison-ossature-bois',
                    'Aménagement Extérieur' => 'amenagement-exterieur',
                    'Escaliers' => 'escaliers',
                ],
                'label' => 'Catégorie'
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image (JPG, PNG)',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Formats acceptés : JPG, PNG',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Realisation::class,
        ]);
    }
}
