<?php

namespace App\Repository;

use App\Entity\Casting;
use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Casting>
 *
 * @method Casting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Casting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Casting[]    findAll()
 * @method Casting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CastingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Casting::class);
    }

    public function add(Casting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Casting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getCastingAndPersonForMovie(Movie $movie): array
    {
        // TODO objectif faire une requete DQL pour obtenir les casstings ET les personn d'un film
        // 1. j'ai besoin d'un film
        // je vais donc demander un film en argument : $movie
        // 2. je récupère mon manager
        $entityManager = $this->getEntityManager();
        // 3. je créer ma requète
        $query = $entityManager->createQuery("
            SELECT c, p
            FROM App\Entity\Casting c
            JOIN c.person p
            WHERE c.movie = :movie
        ");
        // ! object of class App\Entity\Movie could not be converted in string
        // on ne peut pas fournir directement un objet dans la requete DQL
        // on doit passer par un paramètre
        $query->setParameter('movie', $movie);

        $results = $query->getResult();
        // dd($results);
        // 4. je récupère les résultats et je les renvoit
        return $results;

    }


   /**
    * @return Casting[] Returns an array of Casting objects
    */
   public function findByExampleField($value): array
   {
        // l'alias que l'on trouve dans le FROM DQL : FROM App\Entity\Casting c
        // on a pas besoin de préciser le FQCN, car on utilise $this (CastingRepository) 
        // qui donc ma dire que l'on travaille avec App\Entity\Casting
        $results = $this->createQueryBuilder('c')  
            // WHERE c.movie = :movie
           ->andWhere('c.exampleField = :val')
           // le parmètre nommé 
           ->setParameter('val', $value)
           // ORDER BY c.creditOrder ASC
           ->orderBy('c.id', 'ASC')
           // LIMIT 10
           ->setMaxResults(10)
           // on créer la requete
           ->getQuery()
            // on récupère les résultats
           ->getResult()
       ;

       return $results;
   }

   public function findOneBySomeField($value): ?Casting
   {
       return $this->createQueryBuilder('c')
           ->andWhere('c.exampleField = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }
}
