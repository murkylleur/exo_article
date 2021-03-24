<?php

namespace App\Controller;

//je donne les namespaces dont j'aurais besoin pour ma class

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

// Je créé ma class je extend avec AbstractController pour bénéficier des fonctions de celui-ci

class ArticleController extends AbstractController
{

    // Je créé ma route, l'url à appeler pour lancer ma fonction

    /**
     * @Route("/articles", name="articles")
     */
    public function listArticles(ArticleRepository $articleRepository)
    {
        // faire une requête en base de données
        // pour récupérer tous les articles de la table article.

        $articles = $articleRepository->findAll();

        // Je retourne le rendu à mon twig Articles qui est ma liste d'articles

        return $this->render('articles.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/articles/search", name="search_articles")
     */
    public function searchArticles( ArticleRepository $articleRepository, Request $request)
    {
        // je récupère la valeur du parametre d'url "search"
        $search = $request->query->get('search');

        //Je stock ce que me retourne ma requete dans la variable $article
        $articles = $articleRepository->searchByWords($search);

        return $this->render('articles.html.twig', [
            'articles' => $articles
        ]);
    }

    //Comme précédement je créé ma route mais j'y ajoute une whitecard
    //Afin d'avoir un contenu ciblé
    /**
     * @Route("/articles/{id}", name="show_article")
     */

    //Dans ma fonction je donne le parametre de ma whitecard
    //J'appelle ArticleRepository pour pouvoir faire des requetes vers ma BDD

    public function showArticle(ArticleRepository $articleRepository, $id)
    {

        // Dans ma requete je cherche l'article qui à l'id correspondant à la whitecard
        $article = $articleRepository->findOneBy(['id' => $id]);

        // Je retourne le rendu à mon twig Articles qui est ma liste d'articles
        return $this->render('article.html.twig', [
            'article' => $article
        ]);
    }


    //Je met ma route qui ira vers ma page d'acceuil
    /**
     * @Route("/home", name="home")
     */
    //methode qui instencie ArticleRepository
    //passer en paramètre permet de tout laisser à symfony (C'est de l'autowire)!!
    public function homePage(ArticleRepository $articleRepository)
    {

        // Je fais une requete avant de selectionner les deux derniers articles sortis
        $article = $articleRepository->findBy(
            ["published" => '1'], ['date' => "DESC"], 2
        );

        //je retourne le résultat  a ma vue twig home
        return $this->render('home.html.twig', [
            'articles' => $article
        ]);
    }



}

