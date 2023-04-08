<?php

namespace App\Controller\Frontoffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ThemeController extends AbstractController
{
    /**
     * @Route("/theme/{name}", name="app_theme")
     */
    public function index($name, Request $request): Response
    {
        // TODO : récupérer l'objet de session, via la requete
        $session = $request->getSession();
        // TODO : écrire dans la session le theme choisit
        // ? comment je récupère une information de mon utilisateur ?
        // via la route, je rajoute un paramètre à ma route et à ma function
        // TODO : stocker l'information dans la session
        $session->set('theme', $name);

        return $this->redirectToRoute("tagada");
    }
}
