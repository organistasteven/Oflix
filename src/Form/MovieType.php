<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Movie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('duration')
            ->add('summary')
            ->add('synopsys')
            ->add('rating')
            ->add('country')
            //? https://symfony.com/doc/current/reference/forms/types/choice.html#select-tag-checkboxes-or-radio-buttons
            ->add('type', ChoiceType::class, [
                "multiple" => false,
                "expanded" => false,
                "choices" => [
                    "Film ou Série" => [
                        "Film" => "film",
                        "Série" => "serie"
                    ],
                    "Autre chose" => [
                        "Film" => "film",
                        "Série" => "serie"
                    ]
                ]
            ])
            ->add('releaseDate', DateType::class, [
                // je précise que j'utilise le widget choicie pour pourovir modifier l'option years
                "widget" => "choice",
                // ? https://symfony.com/doc/current/reference/forms/types/date.html#years
                // ? https://www.php.net/manual/fr/function.range.php
                "years" => range(1920, date('Y'))
            ])
            // ? https://symfony.com/doc/current/reference/forms/types/url.html
            ->add('poster', UrlType::class)
            // TODO je veux pouvoir dire à quel genre appartient le film
            // je dois pouvoir choisir plusieurs genre
            // il s'agit donc d'un ChoiceType
            // un choiceType avec choix multiple
            // soit checkbox soit select-multiple
            // ? mais comment on fait pour y mettre nos entités ?
            // ? https://symfony.com/doc/current/reference/forms/types/entity.html
             ->add('genres', EntityType::class, [
                // looks for choices from this entity
                'class' => Genre::class,
            
                // uses the property as the visible option string
                'choice_label' => 'title',
            
                // used to render a select box, check boxes or radios
                // ? https://symfony.com/doc/current/reference/forms/types/choice.html#select-tag-checkboxes-or-radio-buttons
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
