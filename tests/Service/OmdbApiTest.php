<?php

namespace App\Tests\Service;

use App\Service\OmdbApi;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OmdbApiTest extends KernelTestCase
{
    /**
     * Teste la méthode fetch()
     */
    public function testFetch(): void
    {
        // on démarre le Kernel de Symfony pour avoir accès au conteneur de services
        $kernel = self::bootKernel();

        // on s'assure qu'on est bien en environnement de  (optionnel)
        $this->assertSame('test', $kernel->getEnvironment());

        // les services de Symfony disposent d'un alias, ici le router
        // $routerService = static::getContainer()->get('router');
        
        // si on souhaite appeler un service à nous
        // ici, OmdbApi
        $omdbApi = static::getContainer()->get(OmdbApi::class);

        // on appelle la méthode fetch('Avatar') pour récupérer le tableau des données du film
        $result = $omdbApi->fetch('Avatar');
        // on affirme que ce résultat est un tableau
        $this->assertIsArray($result);
        // on affirme que la clé "Title" est présente
        $this->assertArrayHasKey('Title', $result);
        // on affirme que la valeur à la clé Title est "Avatar"
        $this->assertEquals('Avatar', $result['Title']);
    }
}
