<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

   //Je créé une méthode de ma class ArticleRepository dans laquelle je vais
    //créer une requête personnalisée en sql.

    public function searchByWords($search)
    {

        $queryBuilder = $this->createQueryBuilder('a');

        //J'utilise mon queryBuilder qui a pour allias a, afin de créer ma requête.

        $query = $queryBuilder

            ->select('a')
            //Je filtre parmis les articles de ma bdd ceux qui contiennent ma recherche
            ->where('a.content LIKE :search')

            //Je met comme parametre search ma variable $search de ma méthode
            ->setParameter('search', '%'.$search.'%')
            // je récupère ma requête
            ->getQuery();


        // je retourne les résultats de la requete Sql
        return $query->getResult();
    }



}