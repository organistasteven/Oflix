<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // 3. ajoute des champs pour générer le HTML * ET * associer ces champs aux propriétés
        // pour <propriété> rajoute un champs HTML
        //? https://symfony.com/doc/current/reference/forms/types/text.html
        $builder->add('username', TextType::class, [
            "label" => "Votre Pseudo:"
        ]);
        // ? https://symfony.com/doc/current/reference/forms/types/email.html
        $builder->add('email', EmailType::class);
        $builder->add('content', TextareaType::class, ["label" => "Qu'est ce que vous en avez pensez ?"]);
        // TODO custom le rating avec des valeurs de 1 à 5
        // deux solutions : 
        // button radio
        // liste déroulante
        // ? https://symfony.com/doc/current/reference/forms/types/choice.html#select-tag-checkboxes-or-radio-buttons
        $builder->add('rating', ChoiceType::class, 
        [
            "multiple" => false, // par default : false
            "expanded" => true, // par default : false
            "placeholder" => "choisissez de 1 à 5 étoiles",
            // ? https://symfony.com/doc/current/reference/forms/types/choice.html#example-usage
            'choices'  => [
                'Votre note'=>[
                    // ? https://emojipedia.org/star/
                    '⭐'=>'1',
                    '⭐⭐'=>'2',
                    '⭐⭐⭐'=>'3',
                    '⭐⭐⭐⭐'=>'4',
                    '⭐⭐⭐⭐⭐'=>'5'
                ],
                // '1 étoiles' => 1,
                // '2 étoiles' => 2,
                // '3 étoiles' => 3,
                // '4 étoiles' => 4,
                // '5 étoiles' => 5
            ],
            // * il faut mettre les valeurs et pas le label
            // 'preferred_choices' => [3,5],
        ]);
        //? https://symfony.com/doc/current/reference/forms/types/date.html
        $builder->add('watchedAt', DateType::class, [
            "widget" => "single_text",
            "label" => "Quand avez vous vu ce film ?"
        ]);

        
        /* code généré
        $builder
            ->add('username')
            ->add('email')
            ->add('content')
            ->add('rating')
            ->add('reactions')
            ->add('watchedAt')
            ->add('movie')
        ;
        */
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
