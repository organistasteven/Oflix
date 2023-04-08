<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movie>
 *
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function add(Movie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Movie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllMovieOrderByTitle(): array
    {
        //? https://www.doctrine-project.org/projects/doctrine-orm/en/2.14/reference/dql-doctrine-query-language.html
        // TODO : il nous faut l'EntityManager
        $entityManager = $this->getEntityManager();
        // 2. on créer la requete DQL
        // si on réflechit en SQL on devrait avoir quelquechose de similaire
        // ! la grosse différence c'est que l'on utilise des objets
        $query = $entityManager->createQuery('SELECT movieAlias FROM App\Entity\Movie movieAlias ORDER BY movieAlias.title DESC');
        // 3. on exécute la requete, et on récupère les résultats
        $allMovies = $query->getResult();
        // dd($allMovies);
        return $allMovies;
    }

    public function getNumberMovie():int
    {
        // 1. apeller le chef, EntityManager
        $entityManager = $this->getEntityManager();
        // 2. on créer notre requete
        $query = $entityManager->createQuery("SELECT COUNT(m) FROM App\Entity\Movie m");
        // 3. je récupère le résultat
        // si on utilise la méthode getResult(), Doctrine ne pouvans pas nous donner un objet
        // cela nous renvoit un tableau
        /*
        $count = $query->getResult();
        dd($count);
        array:1 [▼
            0 => array:1 [▼
                1 => 5
            ]
        ]*/
        // ? https://www.doctrine-project.org/projects/doctrine-orm/en/2.14/reference/dql-doctrine-query-language.html#dql-select-examples
        $count = $query->getSingleScalarResult();
        
        return $count;
    }


//    /**
//     * @return Movie[] Returns an array of Movie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Movie
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
