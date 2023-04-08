<?php

namespace App\Tests\Controller\Frontoffice;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * WebTestCase nous apporte son lot de nouvelles assertions utiles pour le Web !
 */
class HomeTest extends WebTestCase
{
    public function testHome(): void
    {
        // on crée un client Web
        $client = static::createClient();
        // on exécute une requête HTTP GET sur le chemin '/'
        // $crawler => nous permettrait de parcourir le DOM
        $crawler = $client->request('GET', '/');

        // un status en 2xx ? (200 = OK)
        $this->assertResponseIsSuccessful();
        // est-on sur la page d'accueil
        $this->assertSelectorTextContains('h1', 'Films, séries TV et popcorn en illimité.');
    }
}
