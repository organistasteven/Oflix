<?php

namespace App\Tests\Controller\Backoffice;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test des routes pour le rôle "ROLE_MANAGER"
 * @see https://symfony.com/doc/current/best_practices.html#smoke-test-your-urls
 * @dataProvider nomDeleMethode() => indique où sont les données à utiliser
 */
class RoleManagerAccessTest extends WebTestCase
{
    /**
     * Routes en GET pour ROLE_MANAGER
     * auquelles il a accès (en lecture)
     * 
     * @dataProvider getReadUrls
     */
    public function testPageGetIsSuccessful($url)
    {
        $client = self::createClient();

        // ### 1. on se connecte

        // on connecte un ROLE_USER
        // @see https://symfony.com/doc/5.4/testing.html#logging-in-users-authentication
        // accès au service UserRepository
        $userRepository = static::getContainer()->get(UserRepository::class);
        // on connecte user@user.com qui a le ROLE_USER
        $testUser = $userRepository->findOneByEmail('manager@manager.com');
        // on simule une connexion avec cet utilisateur
        $client->loginUser($testUser);

        // ### 2. on émet la requête

        // les pages en GET sont inaccessibles...
        $client->request('GET', $url);

        // ... on obtient un statut 2xx
        $this->assertResponseIsSuccessful();
    }

    public function getReadUrls()
    {
        // ces URLs sont des pages de listing ou d'affichage de form ou d'affichage d'une entité
        yield ['/backoffice/casting/1'];
        yield ['/backoffice/casting/'];
        yield ['/backoffice/genre/'];
        yield ['/backoffice/genre/1'];
        yield ['/backoffice/movie/'];
        yield ['/backoffice/movie/1'];
        yield ['/backoffice/season/'];
        yield ['/backoffice/season/1'];
        yield ['/backoffice/user/'];
        yield ['/backoffice/user/1'];
        // ...
    }

    /**
     * Routes en GET pour ROLE_MANAGER
     * auquelles il n'a pas accès (les formulaires new, edit en GET)
     * 
     * @dataProvider getWriteUrls
     */
    public function testPageGetIsForbidden($url)
    {
        $client = self::createClient();

        // ### 1. on se connecte

        // on connecte un ROLE_USER
        // @see https://symfony.com/doc/5.4/testing.html#logging-in-users-authentication
        // accès au service UserRepository
        $userRepository = static::getContainer()->get(UserRepository::class);
        // on connecte user@user.com qui a le ROLE_USER
        $testUser = $userRepository->findOneByEmail('manager@manager.com');
        // on simule une connexion avec cet utilisateur
        $client->loginUser($testUser);

        // ### 2. on émet la requête

        // les pages en GET sont inaccessibles...
        $client->request('GET', $url);

        // ... on obtient un statut 403 car connecté mais pas la permission
        $this->assertResponseStatusCodeSame(403);
    }

    public function getWriteUrls()
    {
        // ces URLs sont des pages de listing ou d'affichage de form ou d'affichage d'une entité
        yield ['/backoffice/casting/new'];
        yield ['/backoffice/casting/1/edit'];
        yield ['/backoffice/genre/new'];
        yield ['/backoffice/genre/1/edit'];
        yield ['/backoffice/movie/new'];
        yield ['/backoffice/movie/1/edit'];
        yield ['/backoffice/season/new'];
        yield ['/backoffice/season/1/edit'];
        yield ['/backoffice/user/new'];
        yield ['/backoffice/user/1/edit'];
        // ...
    }

    /**
     * Routes en POST pour Anonymous
     * 
     * @dataProvider postUrls
     */
    public function testPagePostIsForbidden($url)
    {
        $client = self::createClient();

        // ### 1. on se connecte

        // accès au service UserRepository
        $userRepository = static::getContainer()->get(UserRepository::class);
        // on connecte user@user.com qui a le ROLE_MANAGER
        $testUser = $userRepository->findOneByEmail('manager@manager.com');
        // on simule une connexion avec cet utilisateur
        $client->loginUser($testUser);

        // ### 2. on émet la requête

        // idem en méthode HTTP POST
        $client->request('POST', $url);

        // ... on obtient un statut 403 car connecté mais pas la permission
        $this->assertResponseStatusCodeSame(403);
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
