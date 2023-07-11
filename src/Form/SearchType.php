<?php

namespace App\Form;

use App\Entity\Category;
use App\Model\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('q', TextType::class, [
                'attr' => [
                    'placeholder' => 'Recherche via un mot clé...'
                ],
                'empty_data' => '',
                'required' => false
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class, 
                'choice_label' => 'name',
                'expanded' => true, // afficher les categs sous forme de case à cocher  
                'multiple' => true, // Chocher plusieurs categs
                'required' => false

            ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
}
