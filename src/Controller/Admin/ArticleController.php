<?php


namespace App\Controller\Admin;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    /**
     * @Route("/admin/articles", name="admin_list_articles")
     */
    public function listArticles(ArticleRepository $articleRepository)
    {
        // faire une requête en base de données
        // pour récupérer tous les articles de la table article.

        $articles = $articleRepository->findAll();

        // Je retourne le rendu à mon twig Articles qui est ma liste d'articles

        return $this->render('articlesAdmin.html.twig', [
            'articles' => $articles
        ]);
    }

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

    /**
     * @Route("/admin/article/update/{id}", name="article_update")
     */
    //Auto-wire du entity manager et du repository
    public function UpdateArticle(EntityManagerInterface $manager, ArticleRepository $repo, $id)
    {
        //Nouvelle instance de l'entité à envoyer en base de donnée
        $article= $repo->find($id);
        if (is_null($article))
        {
            return $this->render('articleManager.html.twig', ["content"=>"Aucun objet trouvé"]);
        }

        $article->setContent("bouboubcontenu encore plus recherché");

        //Pas de persist puisque l'objet est deja enregistré (avec find)
        $manager->flush();

        //Page de confirmation de l'opération
        return $this->render('articleUpdate.html.twig', ["content"=>"L'article ".$article->getTitle()." a bien été modifié",
            'articles*-'=> $article
            ]);
    }

    /**
     * @Route("/admin/article/delete/{id}", name="article_delete")
     */
    //Auto-wire du entity manager et du repository
    public function DeleteArticle( $id, EntityManagerInterface $manager, ArticleRepository $repo)
    {
        //Nouvelle instance de l'entité à envoyer en base de donnée
        $article= $repo->find($id);
        if (is_null($article))
        {
            return $this->render('articleManager.html.twig', ["content"=>"Aucun objet trouvé"]);
        }

        //Puisque les setters sont fluent, on peut enchainer les methodes.
        $manager->remove($article);

        //Pas de persist puisque l'objet est deja enregistré (avec find)
        $manager->flush();

        //Page de confirmation de l'opération
        return $this->render('articleDelete.html.twig', [
            'articles'=>$article
        ]);
    }


}