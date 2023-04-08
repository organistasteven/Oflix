<?php

namespace App\DataFixtures;

use App\Entity\Casting;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use App\Entity\Review;
use App\Entity\Season;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    /**
     * un tableau de code de pays
     *
     * @var array
     */
    private $country = ["FR", "UK", "USA", "GER", "JP", "KR", "BR", "ES", "PT"];

    private $genres = [
        'Americana',
        'Art vidéo',
        'Buddy movie',
        'Chanbara',
        'Chronique',
        'Cinéma amateur',
        'Cinéma d\'auteur',
        'Cinéma de montagne',
        'Cinéma expérimental',
        'Cinéma abstrait',
        'Cinéma structurel',
        'Cinéma underground',
        'Found footage',
        'Comédie',
        'Burlesque',
        'Comédie de mœurs',
        'Comédie dramatique',
        'Comédie policière',
        'Comédie romantique',
        'Parodie',
        'Screwball comedy',
        'Documentaire',
        'Cinéma ethnographique',
        'Cinéma d\'observation',
        'Cinéma vérité',
        'Cinéma direct',
        'Docufiction',
        'Ethnofiction',
        'Essai cinématographique',
        'Film d\'archives',
        'Journal filmé',
        'Portrait',
        'Cinéma surréaliste',
        'Drame',
        'Mélodrame',
        'Docudrama',
        'Film à sketches',
        'Film à suspense',
        'Film d\'action',
        'Film d\'aventures',
        'Film de cape et d\'épée',
        'Film catastrophe',
        'Film érotique',
        'Film d\'espionnage',
        'Film d\'exploitation',
        'Film fantastique',
        'Film de vampires',
        'Film de zombies',
        'Film de guerre',
        'Film de guérilla',
        'Film historique',
        'Film biographique',
        'Film autobiographique',
        'Film institutionnel',
        'Film de mariage',
        'Film publicitaire',
        'Film d\'entreprise',
        'Film de propagande',
        'Film d\'horreur',
        'Slasher',
        'Film de super-héros',
        'Film musical',
        'Film policier',
        'Film de gangsters',
        'Film noir',
        'Film d\'opéra',
        'Film pornographique',
        'Teen movie',
        'Ken Geki',
        'Masala',
        'Road movie',
        'Film d\'amour',
        'Péplum',
        'Science-fiction',
        'Sérial',
        'Thriller',
        'Troma',
        'Western',
    ];

    public function load(ObjectManager $manager): void
    {
        // ? ObjectManager et le parent de EntityManagerInterface
        // on y trouvera les méthodes persist() et flush() comme dans un controller avec EntityManager

        // je commence par créer les tableau d'objet qui vont me servir à plusieurs endroits
        $allMovieArray = [];
        $allGenreArray = [];
        $allPersonArray = [];

        // Utilisation de Faker
        // use the factory to create a Faker\Generator instance
        $faker = Factory::create('fr_FR');
        // ? https://fakerphp.github.io/#seeding-the-generator
        $faker->seed(23012023);
        // ici on ajoute des provider de data supplémentaire
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Movie($faker));
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Character($faker));
        $faker->addProvider(new \Xylis\FakerCinema\Provider\TvShow($faker));
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Person($faker));

        /* la classe n'est pas à jour, dommage ça aurait été intérressant
        $populator = new \Faker\ORM\Doctrine\Populator($faker, $manager);
        $populator->addEntity(Movie::class, 5);
        $insertedPKs = $populator->execute();
        */
        // https://www.lorraine-ipsum.fr/
        // Mélanie Netendoenmarche
        // met la nintendo en marche

        // TODO : 300 films
        for ($i=0; $i < 150; $i++) { 
            // 1. je créer le film
            $movie = new Movie();

            // 2. je remplit les propriétés
            // `film` ou `serie`
            $randomType = rand(0,1) === 1 ? 'serie' : 'film';
            $movie->setType($randomType);

            if ($randomType === 1) {
                $movie->setTitle($faker->unique()->tvShow());
            } else {
                $movie->setTitle($faker->unique()->movie());
            }
            
            // entre 60 et 240 minutes
            $duration = $faker->numberBetween(60,240);
            $movie->setDuration($duration);

            $movie->setSummary($faker->paragraph(2));
            $movie->setSynopsys($faker->realText(300));
            // entre 1 et 5 étoiles
            // Pour avoir une note avec des nombres décimaux
            //? https://fakerphp.github.io/formatters/numbers-and-strings/#randomfloat
            $rating = $faker->randomFloat(1, 1, 5);
            $movie->setRating($rating);

            // on établit une liste de country possible, dans un tableau
            // * j'utilise la propriété privée pour ne pas re-créer le tableau à chauqe passage de boucle
            // on en prend un index au hasard
            //$randomIndexForCountry = rand(0, count($this->country) -1);
            //$randomCountry = $this->country[$randomIndexForCountry];
            $fakerCountry = $faker->country();
            // dd($fakerCountry);
            $movie->setCountry($fakerCountry);



            // entre 01/01/1895 et aujourdh'ui
            // trop compliqué, merci les dates, on utilisera un outil pour ça : Faker
            // TODO : à reprendre car pas aléatoire
            //? https://fakerphp.github.io/formatters/date-and-time/#datetimebetween
            $movie->setReleaseDate($faker->dateTimeBetween('-80 years'));

            // ? https://picsum.photos ==> Static Random Image
            // on enregistre URL qui nous redirigera vers une image aléatoire lors de l'affichage
            // ? https://amc-theatres-res.cloudinary.com/amc-cdn/static/images/fallbacks/DefaultOneSheetPoster.jpg
            $movie->setPoster("https://picsum.photos/seed/oflix" . $i . "/300/450");

            // TODO Season
            // TODO Casting
            // pour générer nos premières données, on est pas obligé de faire les relations


            // 3. je demande au manager de prendre en compte cette nouvelle entité
            $manager->persist($movie);

            // 4. je conserve l'objet film pour une utilisation ultérieure
            $allMovieArray[] = $movie;
        }

        // TODO : 150 personnes
        for ($i=0; $i < 150; $i++) { 
            //1. création de la nouvelle entité
            $person = new Person();
            // 2. remplir les propriétés
            $fakeActorFullName = $faker->unique()->actor();
            
            $person->setFullName($fakeActorFullName);
            //$person->setFirstname("Prénom #".rand(0,10000));
            //$person->setLastname("Nom #".rand(0,10000));

            $manager->persist($person);

            $allPersonArray[] = $person;
        }

        // TODO : genres
        // on créer à partir le la propriété privés
        foreach ($this->genres as $genreName) {
            $genre = new Genre();
            $genre->setTitle($genreName);

            $manager->persist($genre);

            $allGenreArray[] = $genre;
        }

        // TODO : entre 1 et 5 genres par film
        // il faut le faire pour chaque films : j'ai besoin d'une liste de tout les films qui existe
        // ? j'ai pas encore fait flush(), donc il n'y a rien en BDD
        // je vais donc me créer une liste des objets dont j'ai besoin : $allMovieArray
        
        // * petite astuce pour aider VSCode à l'auto-completion
        // on donne le type que contient ma variable
        /** @var Movie $movie */
        foreach ($allMovieArray as $movie) {
            // un chiffre aléatoire entre 1 et 5
            $randomNbGenre = rand(1,5);
            for ($i=0; $i < $randomNbGenre; $i++) { 
                // ici on fait X fois l'association
                // il me faut un objet genre au hasard
                // j'ai besoin d'une liste de tout les genres qui existe
                /** @var Genre $randomGenre */
                // ? https://www.php.net/manual/en/function.array-key-last.php
                $randomGenre = $allGenreArray[rand(0, array_key_last($allGenreArray))];
                $movie->addGenre($randomGenre);
            }
        }
        

        // TODO : entre 1 et 5 saison pour un film de type `serie`
        // pour chaque film de type `serie`
        foreach ($allMovieArray as $movie) {
            // Que pour les type == 'serie'
            if ($movie->getType() === 'serie'){
                // un chiffre aléatoire entre 1 et 5
                $randomNbSeason = rand(1,5);
                // je met $i=1 pour avoir un numéro de saison utilisation dans son nom
                for ($i=1; $i <= $randomNbSeason; $i++) { 
                    // je créer un season spécifique pour cette série
                    $season = new Season();
                    $season->setName("Saison ".$i);
                    $season->setNumberEpisode(rand(6,24));

                    $season->setMovie($movie);

                    // ne pas oublier de persist l'objet
                    $manager->persist($season);
                }
            }
        }
        


        // TODO : entre 1 et 5 rôles par film (Casting)
        // pas deux fois, je ne vous fait pas le challenge :D

        // Movie 1N <--> 11 Casting 11 <--> 0N Person
        // En regardant les relations, on voit qu'il faut créer d'abord Movie et Person avant de pouvoir créer Casting
        // $allMovieArray
        // $allPersonArray
        // il ne reste plus qu'a créer des casting
        // puisque la relation minimum avec Person est 0, il n'est donc pas obligatoire à faire.
        // on en déduit qu'il faut partir de Movie
        foreach ($allMovieArray as $movie) {
            // entre 1 et 5 casting par film
            $randomNbCasting = rand(1, 5);
            // on change la boucle pour le creditOrder
            for ($i=1; $i <= $randomNbCasting; $i++) { 
                $casting = new Casting();
                // ? https://github.com/JulienRAVIA/FakerCinemaProviders#xylisfakercinemaprovidercharacter
                $casting->setRole($faker->character());
                $casting->setCreditOrder($i);
                
                // les relations
                // person aléatoire
                $randomIndexPerson = rand(0, array_key_last($allPersonArray));
                $casting->setPerson($allPersonArray[$randomIndexPerson]);

                $casting->setMovie($movie);
                // on oublie pas de persist notre nouvelle entité
                $manager->persist($casting);

            }
        }

        // TODO : ajouter des reviews
        // j'ajoute de 0 à 5 reviews sur tout les films
        
        foreach ($allMovieArray as $movie) {
            $randomNbReview = rand(0,5);
            for ($i=0; $i < $randomNbReview; $i++) {
                // je créer une nouvelle instance
                $review = new Review();
                // je remplit les propriétés
                $review->setContent($faker->paragraph());
                $review->setEmail($faker->email());
                $review->setUsername($faker->name());
                $review->setRating(rand(0, 5));
                $review->setWatchedAt($faker->dateTimeBetween());
                // sans oublier les relations
                $review->setMovie($movie);
                // je persist
                $manager->persist($review);
            }
        }

        $manager->flush();
    }
}
