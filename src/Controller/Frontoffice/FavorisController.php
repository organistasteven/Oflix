<?php

namespace App\Controller\Frontoffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavorisController extends AbstractController
{
    /**
     * affichage des favoris
     * 
     * @Route("/favoris", name="favoris")
     */
    public function favoris(): Response
    {
        return $this->render("frontoffice/favoris/favorites.html.twig");
    }
}