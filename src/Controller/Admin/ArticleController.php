<?php


namespace App\Controller\Admin;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    /**
     * @Route("/admin/articles/insert", name="admin_insert_article")
     */
    public function insertArticle(EntityManagerInterface $entityManager)
    {

        // je créé une entité article avec les valeurs que je rentre moi-même
        $article = new Article();
        $article->setTitle('Article inséré depuis le contrôleur');
        $article->setContent('blab abla');
        $article->setResume('resume de blabla');
        $article->setDate(new \DateTime('NOW'));
        $article->setPublished('1');


        // on utilise un outil pour indiquer à doctrine qu'on a créé des entités
        // l'outil en question permet de gérer les entités
        $entityManager->persist($article);
        // puis on enregistre toutes les entités surveillées en base de données
        $entityManager->flush();

        return new Response('OK');
    }

}