<?php


namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function insertArticle(EntityManagerInterface $entityManager, Request $request)
    {
        $article = new Article();

        // je récupère le gabarit de formulaire d'Article et je le relie à mon nouvel article
        $articleForm = $this->createForm(ArticleType::class, $article);

        // je récupère les données de POST (donc envoyées par le formulaire) grâce
        // à la classe Request, et je lie les données récupérées dans le formulaire
        $articleForm->handleRequest($request);

        // si mon formulaire a été envoyé et que les données de POST
        // correspondent aux données attendues par l'entité Article
        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            // alors je récupère l'entité Article enrichie avec les données du formulaire
            $article = $articleForm->getData();

            // et j'enregistre l'article en bdd
            $entityManager->persist($article);
            $entityManager->flush();
        }


        // je récupère (et compile) le fichier twig et je lui envoie le formulaire, transformé
        // en vue (donc exploitable par twig)
        return $this->render('admin/insert_article.html.twig', [
            'articleFormView' => $articleForm->createView()
        ]);
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