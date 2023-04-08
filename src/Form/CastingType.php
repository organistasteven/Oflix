<?php

namespace App\Form;

use App\Entity\Casting;
use App\Entity\Movie;
use App\Entity\Person;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CastingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('role')
            ->add('creditOrder')
            // relation : EntityType
            ->add('movie', EntityType::class, [
                "class" => Movie::class,
                "choice_label" => "title",
                "multiple"=> false,
                "expanded" => false
            ])
            ->add('person',EntityType::class, [
                "class" => Person::class,
                // j'ai besoin de prénom + nom
                /* choice_label =>[first,last] */
                // on créé une méthode spécifique pour cet affichage
                "choice_label" => "getFullname",
                "multiple"=> false,
                "expanded" => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Casting::class,
        ]);
    }
}
