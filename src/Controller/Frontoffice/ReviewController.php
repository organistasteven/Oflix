<?php

namespace App\Controller\Frontoffice;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    /**
     * 
     * @Route("/review/add/{movieId}", name="review_add", methods={"GET", "POST"}, requirements={"movieId"="\d+"})
     */
    public function add($movieId, MovieRepository $movieRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // TODO : Afficher le formulaire sur la page dédiée "Ajouter une critique sur le film (nom du film)"
        // il me faut le film, je vais le chercher en BDD
        // MovieRepository, find($movieId) :
        // 1. Injection de dépendance de MovieRepository
        // 2. un paramètre de route : $movieId
        $movie = $movieRepository->find($movieId);

        // TODO : créer le formulaire

        // 1. sur quelle entité on créer le formulaire : Review
        $reviewForFormulaire = new Review();
        // 2. je crée un formulaire suivant la personalisation faites avec make:form => ReviewType
        $formulaire = $this->createForm(ReviewType::class, $reviewForFormulaire);

        // TODO : gestion de la réception des données venant du formulaire
        // 1. on a maintenant un formulaire qui connait le contenue de le request
        // on lui demande donc de prendre en compte le contenu de request
        // * injection de dépendance de Request
        $formulaire->handleRequest($request);

        // 2. puisque on utilise la même route, vérifions que le formulaire a été soumis
        // 2.bis on ajoute la demande de validation des infos de l'entité
        if ($formulaire->isSubmitted() && $formulaire->isValid()) {

            // 3. comme on a demander au formulaire de récupérer les infos de request
            // notre instance $reviewForFormulaire est automatiquement remplis avec les données du formulaire
            
            // ! ne pas oublier les relations
            // on a une relaiton avec movie
            $reviewForFormulaire->setMovie($movie);

            // il ne reste plus qu'a faire un persist, flush
            // qui dit persist, dit EntityManager, injection de dépendance
            $entityManager->persist($reviewForFormulaire);
            $entityManager->flush();

            // on a traité un formulaire, on doit rediriger
            return $this->redirectToRoute('show_with_index', ["index" => $movieId]);
        }


        // en symfony 5.3, il est demandé d'utiliser render()
        // en symfony 5.4, il est demandé d'utiliser renderForm()
        // en symfony 6.2, il est demandé d'utiliser render()
        return $this->render('frontoffice/review/index.html.twig', [
            "movie" => $movie,
            // 3. on fournit le formulaire à la vue
            // ! erreur si on n'ajoute pas la méthode createView()
            "formulaire" => $formulaire->createView()
        ]);
    }
}
