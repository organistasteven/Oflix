<?php

namespace App\Tests\Models;

use App\Models\MovieModel;
use PHPUnit\Framework\TestCase;

class MovieModelTest extends TestCase
{
    /**
     * test de la méthode getAllMovies()
     * info : les méthodes de test dans les classes de test
     * sont préfixées par "test"
     */
    public function testGetAllMovies(): void
    {
        // instancier MovieModel
        $movieModel = new MovieModel();

        // on appelle la méthode getAllMovies()
        $result = $movieModel->getAllMovies();

        // on affirme que le retour est un tableau
        $this->assertIsArray($result);
        // on affirme que le premier élément est un tableau qui contient la clé 'title'
        $firstMovie = $result[0];
        $this->assertArrayHasKey('title', $firstMovie);
    }

    public function testGetMovie(): void
    {
        // instancier MovieModel
        $movieModel = new MovieModel();

        // on appelle la méthode getAllMovies()
        $result = $movieModel->getMovie(0);

        // on affirme que le retour est un tableau
        $this->assertIsArray($result);
        // on affirme que le tableau contient la clé 'title'
        $this->assertArrayHasKey('title', $result);
    }

    // ce qu'on pourait tester
    // - que la méthode getMovie(1000) renvoie null si index non trouvé
    // - de typer getMovie(int $index) et de vérifier qu'on se fait jeter si on envoie autre chose qu'un index

    public function testGetMovieFailure(): void
    {
        // instancier MovieModel
        $movieModel = new MovieModel();

        // on appelle la méthode getAllMovies()
        // l'index 1000 n'existe pas !
        $result = $movieModel->getMovie(1000);

        // on affirme que le retour est un tableau
        $this->assertNull($result);
    }
}
