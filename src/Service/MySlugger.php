<?php

namespace App\Service;

use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Classe pour générer les slugs sur O'flix
 */
class MySlugger
{
    /** @var SluggerInterface $slugger */
    private $slugger;

    /** @var bool $toLower */
    private $toLower;

    public function __construct(SluggerInterface $slugger, bool $toLower)
    {
        // on inclut le slugger de Symfony dans notre slugger à nous
        // pour pouvoir s'appuyer dessus
        $this->slugger = $slugger;
        $this->toLower = $toLower;
    }

    /**
     * Slugifie une chaine
     * 
     * @param string $string La chaine à "slugifier"
     * @return string La chaine "slugifiée"
     */
    public function slugify(string $string)
    {
        // slug à partir du titre, grâve au service Slugger injecté dans le constructeur
        // par ex. "12 Angry Men" => "12-Angry-Men"
        $slug = $this->slugger->slug($string);

        // en minuscule ?
        if ($this->toLower) {
            // "12-Angry-Men" => "12-angry-men"
            $slug = $slug->lower();
        }

        return $slug;
    }

    /**
     * Get the value of toLower
     */ 
    public function getToLower()
    {
        return $this->toLower;
    }

    /**
     * Set the value of toLower
     *
     * @return  self
     */ 
    public function setToLower($toLower)
    {
        $this->toLower = $toLower;

        return $this;
    }
}