<?php

namespace App\Controller\Frontoffice;

use App\Entity\Movie;
use App\Models\MovieModel;
use App\Repository\CastingRepository;
use App\Repository\MovieRepository;
use App\Repository\ReviewRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// * obligatoire pour utiliser l'annotation @Route
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * ! ne pas oublier le use
     * @Route("/home", name="home")
     * @Route("/", name="home_default")
     * 
     * ceci est la méthode pour la route par défaut
     */
    public function home(MovieRepository $movieRepository): Response
    {
        // TODO : connection à la BDD
        // c'est le rôle du Repository, lequel ?
        // Movie => MovieRepository
        // On utilise l'injection de dépendance de Symfony pour obtenir le Repository

        // TODO : findAll() sur les Movie
        // c'est le rôle du repository
        // TODO : je veux récuperer les films dans l'ordre alphabetique
        // $allMovies = $movieRepository->findAll();
        $allMovies = $movieRepository->findBy(
            // aucun critère, on veux tout les films
            [],
            // ORDER BY title
            [
                "title" => "ASC"
            ]
        );
        // * On peux aussi faire ça dans le repository avec DQL
        $allMoviesDQL = $movieRepository->findAllMovieOrderByTitle();
        // dd($allMoviesDQL);
        // TODO : je veux afficher le nombre de film
        // 1. je fait un count sur le tableau $allMovies, 
        // c'est long, et si je met en place la pagination (LIMIT 10) mon count($allMovies) sera faux
        // 2. on fait donc une requete personnalisée
        $countMovie = $movieRepository->getNumberMovie();


        // dd($allMovies);
        // TODO : fournir la liste des Movie à ma vue
        return $this->render("frontoffice/home/home.html.twig", [
            // la clé sera le nom de la variable disponible dans twig
            "allModelMovies" => $allMovies,
            "numberOfMovie" => $countMovie
        ]);

        /* Sans la BDD
        // TODO : créer un MovieModel
        $model = new MovieModel();
        // TODO : récupérer la liste des films, depuis le model
        $allMovies = $model->getAllMovies();
        dump($allMovies);
        // TODO : fournir la liste à notre vue
        // * version 3
        return $this->render("home/home.html.twig", [
            // la clé sera le nom de la variable disponible dans twig
            "allModelMovies" => $allMovies
        ]);
        */
        // la méthode render viens du AbstractController
        // cette méthode nous renvoit une Response, pratique car on peut en faire une return immédiatement
        // il faut lui fournir un nom de fichier twig, le nom de la vue que l'on veux avoir
        // * version 2
        // return $this->render("home/home.html.twig");
        // * version 1
        // return new Response("Coucou les Lucy");
    }

    /**
     * méthode qui affiche le détail d'un film via son slug
     * @Route("/show/{slug}", name="movie_show")
     */
    public function show(Movie $movie = null, CastingRepository $castingRepository, ReviewRepository $reviewRepository): Response
    {
       

        // TODO : gérer le fait que 'on ne trouve pas le film
        if ($movie === null){ // on a pas trouvé notre film
            // on lance une exception 404
            throw $this->createNotFoundException("Ce film n'existe pas");
        }

        // TODO je cherche à obtenir les casting dans un certain ordre
        // 1. connection à la BDDD : Casting => CastingRepository
        // 2. avec le repository on a la méthode findBy() qui permet de préciser des critères (WHERE)
        // SELECT * FROM casting WHERE movie_id = ?
        // on travaille avec des objets, donc on parle pas de `movie_id` mais d'une propriété `movie`
        // cette propriété, on peut la comparer avec notre objet $movie
        //? https://symfony.com/doc/5.4/doctrine.html#fetching-objects-from-the-database
        $allCastingForMovie = $castingRepository->findBy(
            // les critères
            [
                "movie" => $movie
            ],
            // ORDER BY `column` ASC/DESC
            [
                "creditOrder" => "ASC"
            ]
        );
        // * person not initialized
       // dump($allCastingForMovie);
     
        // TODO faire une requete custom pour faire la jointure entre casting et person
        $allCastingAndPerson = $castingRepository->getCastingAndPersonForMovie($movie);
        // je vais chercher des personnes, donc tout ceux qui ont le même objet person sont mis à jour avec les infos que l'on récupère
        dump($allCastingAndPerson);
        // * person initialized !
        //dump($allCastingForMovie);
        
        // dd($movie);
        $allSeasons = $movie->getSeasons();
        // ! je n'utilise pas le tableau d'objet, il n'est pas initialized !
        //dump($allSeasons);

        // TODO afficher les reviews
        // 1. BDD, repository, Injection
        // 2. find All avec critères
        $allreviews = $reviewRepository->findBy([ "movie" => $movie]);


        // TODO : fournir le film à ma vue
        return $this->render("frontoffice/home/show.html.twig", 
        [
            "movieDetail" => $movie,
            "castings" => $allCastingAndPerson,
            "reviews" => $allreviews
        ]);
    }

    /**
     * Affiche une liste de films, suite à une recherche par exemple
     *
     * @return Response
     * 
     * @Route("/search", name="search_list")
     */
    public function list(Request $request): Response
    {
        // TODO : récupérer la requete, l'objet Request
        // ce n'est pas ce que l'on veux faire, créer une requete : new Request()

        // il faut demander à Symfony de nous fournir la requete lié à notre contexte
        // on demande à symfony en utilisant l'injection de dépendance
        // l'injection de dépendance ce fait en ajoutant un paramètre avec son Type
        // dump($request);
        // TODO : récuperer la session, contenu dans la requete
        $sessionUtilisateur = $request->getSession();
        // dump($sessionUtilisateur);
        
        // TODO : manipuler la session
        
        // écrire une valeur
        // en PHP natif : $_SESSION["key"] = "valeur";
        $sessionUtilisateur->set("key", "value");
        dump($sessionUtilisateur);
        
        // lire une valeur
        // en PHP natif : $value = $_SESSION["key"];
        $value = $sessionUtilisateur->get("key");
        dump($value);

        return $this->render("frontoffice/home/list.html.twig");
    }


    /**
     * @Route("/movie/add", name="movie_add")
     *
     * @param MovieRepository $movieRepository
     */
    public function add(MovieRepository $movieRepository): Response
    {
        //? on aurait besoin d'un formulaire, on le fera demain
        // On imagine recevoir des informations du formulaire
        // TODO : Quand on veux inserer des données, on doit fournit un objet à Doctrine
        // on va donc faire un new Movie
        $movie = new Movie();
        // on doit remplir les valeurs de ses propriétés
        // avec les informations du formulaire (kon a pas)
        $movie->setTitle("Fin de journée du Lundi");
        $movie->setDuration(90);
        $movie->setSummary("azerty Lorem Ipsum");
        $movie->setSynopsys("le synopsys avec trop de Y");
        $movie->setRating(2);
        $movie->setCountry("UK");
        $movie->setType("Film");
        // il faut fournir un objete en paramètre
        // je lui fournis directement un new DateTime()
        // la valeur par défaut d'un DateTime est : mainenant (now)
        $movie->setReleaseDate(new DateTime());

        // TODO : je vais demander à Doctrine d'ajouter mon objet en BDD
        // pour cela le repository m'aide, il a une méthode add()
        // qui, comme par hasard, demande un objet Movie
        // Je demande mon MovieRepository à Symfony
        $movieRepository->add($movie, true);
        
        return $this->redirectToRoute("home");
    }
}