<?php

namespace App\Form;

use App\Entity\Movie;
use App\Entity\Season;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('numberEpisode')
            //! Object of class Proxies\__CG__\App\Entity\Movie could not be converted to string
            // ? https://symfony.com/doc/current/reference/forms/types/entity.html
            ->add('movie', EntityType::class, [
                // pour afficher un objet il nous deux paramètres:
                // 1. le nom de la classe de l'objet
                'class' => Movie::class,
                // 2. pour écrire il faut une chaine de caractère : une propriété
                'choice_label' => 'title',
                // Puisque EntityType hérite de ChoiceType, on a la possibilité de changer l'affichage en multiple ET/OU expanded
                "multiple" => false,
                "expanded" => false,
                //? https://symfony.com/doc/current/reference/forms/types/entity.html#using-a-custom-query-for-the-entities
                // pour trier les noms de films
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.title', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
