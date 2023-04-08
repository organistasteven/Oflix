<?php

namespace App\EventListener;

use App\Entity\Movie;
use App\Service\MySlugger;
use Doctrine\Persistence\Event\LifecycleEventArgs;

/**
 * Ecouteur d'événement sur l'entité Movie
 */
class MovieSlug
{
    private $slugger;

    public function __construct(MySlugger $slugger)
    {
        // notre service MySlugger
        $this->slugger = $slugger;
    }
    
    /**
     * On reçoit l'entité et l'événement assoicé
     */
    public function slugify(Movie $movie, LifecycleEventArgs $event)
    {
        // on slugifie le titre
        $slug = $this->slugger->slugify($movie->getTitle());
        $movie->setSlug($slug);
    }
}