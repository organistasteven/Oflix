<?php

namespace App\Controller\Api;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MovieController extends AbstractController
{
    /**
     * Get movies collection
     * 
     * @Route("/api/movies", name="app_api_movies_get_collection", methods={"GET"})
     */
    public function getCollection(MovieRepository $movieRepository): JsonResponse
    {
        // liste des films (je n'en prends que 10 pour alléger le retour)
        $movies = $movieRepository->findBy([], null, 10);

        // on retourne la liste sous forme de JSON
        return $this->json(
            // les données à sérialiser
            ['movies' => $movies],
            // statut 200
            Response::HTTP_OK,
            // pas de modification d'en-tête de réponse
            [],
            // on indique au Serializer d'utiliser le groupe "movies_get_collection" défini dans l'entité
            ['groups' => 'movies_get_collection']
        );
    }

    /**
     * Get movies item
     * 
     * Raccourci dans le requirement {id} = \d+,
     * @see https://symfony.com/doc/current/routing.html#parameters-validation
     * 
     * @Route("/api/movies/{id<\d+>}", name="app_api_movies_get_item", methods={"GET"})
     */
    public function getItem(Movie $movie = null): JsonResponse
    {
        // film non trouvé ?
        if ($movie === null) {

            // on pourrait utiliser les exceptions Symfony et le Normalizer
            // @see https://symfony.com/doc/current/controller/error_pages.html#overriding-error-output-for-non-html-formats

            // on peut le faire à la mano "en attendant"
            return $this->json(['message' => 'Film non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        // @todo : utiliser la requête de jointure des castings créée précédemment

        // on retourne la ressource sous forme de JSON
        return $this->json(
            // les données à sérialiser
            ['movie' => $movie],
            // statut 200
            Response::HTTP_OK,
            // pas de modification d'en-tête de réponse
            [],
            // on indique au Serializer d'utiliser le groupe "movies_get_collection" défini dans l'entité
            ['groups' => 'movies_get_item']
        );
    }

    /**
     * Get movies by genre
     * 
     * @Route("/api/genres/{id}/movies", name="app_api_get_movies_by_genre", methods={"GET"})
     */
    public function getMoviesByGenre(Genre $genre, MovieRepository $movieRepository)
    {
        // genre non trouvé ?
        if ($genre === null) {
            return $this->json(['message' => 'Genre non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        // les films par genre
        $movies = $movieRepository->findByGenre($genre);
        // dd($movies);

        // on retourne la liste sous forme de JSON
        return $this->json(
            // les données à sérialiser
            ['movies' => $movies],
            // statut 200
            Response::HTTP_OK,
            // pas de modification d'en-tête de réponse
            [],
            // on indique au Serializer d'utiliser le groupe "movies_get_collection" défini dans l'entité
            ['groups' => 'movies_get_collection']
        );
    }

    /**
     * Get a random movie
     * 
     * @Route("/api/movies/random", name="app_api_get_movies_random", methods={"GET"})
     */
    public function getRandomItem(MovieRepository $movieRepository)
    {
        $movie = $movieRepository->getRandomMovie();

        // on retourne la ressource sous forme de JSON
        return $this->json(
            // les données à sérialiser
            ['movie' => $movie],
            // statut 200
            Response::HTTP_OK,
            // pas de modification d'en-tête de réponse
            [],
            // on indique au Serializer d'utiliser le groupe "movies_get_collection" défini dans l'entité
            ['groups' => 'movies_get_item']
        );
    }

    /**
     * Create movie
     * 
     * @Route("/api/movies", name="app_api_post_movies_item", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        // Le JSON d'entrée contient les mêmes propriétés que l'entité cible !

        // Le contenu est récupéré via $request->getContent();
        $jsonContent = $request->getContent();

        // (de)Serializer : on desérialise ce contenu JSON pour le transformer en entité Movie.
        // Récupérer en injection le service SerializerInterface
        $movie = $serializer->deserialize($jsonContent, Movie::class, 'json');
        //dd($movie); // <= Votre objet Movie doit exister

        // Utiliser le service Validator (composant) pour valider l'entité.
        // @see https://symfony.com/doc/5.4/validation.html#using-the-validator-service
        $errors = $validator->validate($movie);

        // si tableau non vide, erreurs
        // notre tableau d'erreurs pour le JSON de sortie
        $errorsList = [];
        if (count($errors) > 0) {

            foreach ($errors as $error) {
                // on push à la clé $propertyPath l'erreur dans un tableau
                $errorsList[$error->getPropertyPath()][] = $error->getMessage();
            }

            return $this->json($errorsList, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Sinon, l'entité est sauvegardée via le Manager de Doctrine.
        $entityManager->persist($movie);
        $entityManager->flush();

        // On renvoie une réponse avec un en-tête Location: vers la ressource créée
        // ainsi qu'un status code 201 (Created)
        // optionnel : on peut renvoyer l'entité complète
        // on retourne la ressource sous forme de JSON
        return $this->json(
            // les données à sérialiser
            ['movie' => $movie],
            // statut 201 Created
            Response::HTTP_CREATED,
            // pour faire plaisir à REST :D
            [
                'Location' => $this->generateUrl(
                    'app_api_movies_get_item', ['id' => $movie->getId()]
                )
            ],
            // on indique au Serializer d'utiliser le groupe "movies_get_collection" défini dans l'entité
            ['groups' => 'movies_get_item']
        );
    }

    /**
     * Update movie
     * 
     * @Route("/api/movies/{id<\d+>}", name="app_api_patch_movies_item", methods={"PATCH"})
     */
    public function patch(Movie $movie = null, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        // film non trouvé ?
        if ($movie === null) {
            return $this->json(['message' => 'Film non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        // Le JSON d'entrée contient les mêmes propriétés que l'entité cible !

        // Le contenu est récupéré via $request->getContent();
        $jsonContent = $request->getContent();

        // (de)Serializer : on desérialise ce contenu JSON pour le transformer en entité Movie.
        // Récupérer en injection le service SerializerInterface
        // on met à jour une entité existante via "AbstractNormalizer::OBJECT_TO_POPULATE"
        // @see https://symfony.com/doc/5.4/components/serializer.html#deserializing-in-an-existing-object
        $movie = $serializer->deserialize($jsonContent, Movie::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $movie]);
        //dd($movie); // <= Votre objet Movie doit exister

        // Utiliser le service Validator (composant) pour valider l'entité.
        // @see https://symfony.com/doc/5.4/validation.html#using-the-validator-service
        $errors = $validator->validate($movie);

        // si tableau non vide, erreurs
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Sinon, l'entité est sauvegardée via le Manager de Doctrine.
        $entityManager->persist($movie);
        $entityManager->flush();

        // On renvoie une réponse avec un en-tête Location: vers la ressource créée
        // ainsi qu'un status code 201 (Created)
        // optionnel : on peut renvoyer l'entité complète
        // on retourne la ressource sous forme de JSON
        return $this->json(
            // les données à sérialiser si le client en a besoin
            ['movie' => $movie],
            // statut 200 OK
            Response::HTTP_OK,
            // pas de header particulier
            [],
            // on indique au Serializer d'utiliser le groupe "movies_get_collection" défini dans l'entité
            ['groups' => 'movies_get_item']
        );
    }
}
