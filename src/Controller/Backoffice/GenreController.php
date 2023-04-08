<?php

namespace App\Controller\Backoffice;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ici on préfixe toutes les routes de ce controller avec /backoffice/genre
 * on peut faire de même avec le nom des routes
 * @Route("/backoffice/genre", name="app_backoffice_")
 */
class GenreController extends AbstractController
{
    /**
     * @Route("/", name="genre_index", methods={"GET"})
     */
    public function index(GenreRepository $genreRepository): Response
    {
        return $this->render('backoffice/genre/index.html.twig', [
            'genres' => $genreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="genre_new", methods={"GET", "POST"})
     */
    public function new(Request $request, GenreRepository $genreRepository): Response
    {
        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $genreRepository->add($genre, true);

            return $this->redirectToRoute('app_backoffice_genre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/genre/new.html.twig', [
            'genre' => $genre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="genre_show", methods={"GET"})
     * cf cours sur ParamConverter
     */
    public function show(Genre $genre): Response
    {
        // je déduis :
        // j'ai un paramètre de route {id}
        // je veux un Genre
        // symfony va donc faire : $genreRepository->find($id)
        // et nous fournir le résultat

        return $this->render('backoffice/genre/show.html.twig', [
            'genre' => $genre,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="genre_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Genre $genre, GenreRepository $genreRepository): Response
    {
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $genreRepository->add($genre, true);

            return $this->redirectToRoute('app_backoffice_genre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/genre/edit.html.twig', [
            'genre' => $genre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="genre_delete", methods={"POST"})
     */
    public function delete(Request $request, Genre $genre, GenreRepository $genreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$genre->getId(), $request->request->get('_token'))) {
            $genreRepository->remove($genre, true);
        }

        return $this->redirectToRoute('app_backoffice_genre_index', [], Response::HTTP_SEE_OTHER);
    }
}
