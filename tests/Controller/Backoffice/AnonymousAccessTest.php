<?php

namespace App\Tests\Controller\Backoffice;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test des routes pour le rôle "Anonyme"
 * @see https://symfony.com/doc/current/best_practices.html#smoke-test-your-urls
 * @dataProvider nomDeleMethode() => indique où sont les données à utiliser
 */
class AnonymousAccessTest extends WebTestCase
{
    /**
     * Routes en GET pour Anonymous
     * 
     * @dataProvider getUrls
     */
    public function testPageGetIsRedirect($url)
    {
        $client = self::createClient();
        // les pages en GET sont inaccessibles...
        $client->request('GET', $url);

        // ... on obtient un statut 302, donc une redirection
        $this->assertResponseRedirects();
    }

    public function getUrls()
    {
        // ces URLs sont des pages de listing ou d'affichage de form ou d'affichage d'une entité
        yield ['/backoffice/casting/'];
        yield ['/backoffice/casting/1'];
        yield ['/backoffice/casting/new'];
        yield ['/backoffice/casting/1/edit'];
        yield ['/backoffice/genre/'];
        yield ['/backoffice/genre/new'];
        yield ['/backoffice/genre/1'];
        yield ['/backoffice/genre/1/edit'];
        yield ['/backoffice/movie/'];
        yield ['/backoffice/movie/new'];
        yield ['/backoffice/movie/1'];
        yield ['/backoffice/movie/1/edit'];
        yield ['/backoffice/season/'];
        yield ['/backoffice/season/new'];
        yield ['/backoffice/season/1'];
        yield ['/backoffice/season/1/edit'];
        yield ['/backoffice/user/'];
        yield ['/backoffice/user/new'];
        yield ['/backoffice/user/1'];
        yield ['/backoffice/user/1/edit'];
        // ...
    }

    /**
     * Routes en POST pour Anonymous
     * 
     * @dataProvider postUrls
     */
    public function testPagePostIsRedirect($url)
    {
        $client = self::createClient();
        $client->request('POST', $url);

        $this->assertResponseRedirects();
    }

    public function postUrls()
    {
        // pages de traitement (POST) de formulaire create et update, et de suppression
        yield ['/backoffice/casting/1']; // Delete
        yield ['/backoffice/casting/new'];
        yield ['/backoffice/casting/1/edit'];
        yield ['/backoffice/genre/1']; // Delete
        yield ['/backoffice/genre/new'];
        yield ['/backoffice/genre/1/edit'];
        yield ['/backoffice/movie/new'];
        yield ['/backoffice/movie/1/edit'];
        yield ['/backoffice/movie/1'];
        yield ['/backoffice/season/new'];
        yield ['/backoffice/season/1/edit'];
        yield ['/backoffice/season/1'];
        yield ['/backoffice/user/new'];
        yield ['/backoffice/user/1/edit'];
        yield ['/backoffice/user/1'];
        // ...
    }
}
