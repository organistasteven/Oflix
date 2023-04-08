<?php

namespace App\Controller\Api;

use App\Repository\GenreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GenreController extends AbstractController
{
    /**
     * Get genres collection
     * 
     * @Route("/api/genres", name="app_api_genres_get_collection", methods={"GET"})
     */
    public function getCollection(GenreRepository $genreRepository): JsonResponse
    {
        // liste des genres (je n'en prends que 10 pour alléger le retour)
        $genres = $genreRepository->findBy([], null, 10);

        // on retourne la liste sous forme de JSON
        return $this->json(
            // les données à sérialiser
            ['genres' => $genres],
            // statut 200
            Response::HTTP_OK,
            // pas de modification d'en-tête de réponse
            [],
            // on indique au Serializer d'utiliser le groupe "genres_get_collection" défini dans l'entité
            ['groups' => 'genres_get_collection']
        );
    }
}
