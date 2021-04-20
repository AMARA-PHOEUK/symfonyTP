<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;


class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo): Response
    {
        // getDoctrine = obtenir une instance (comme le $this)
        // $repo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repo->findAll();
        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
        ]);
    }
    /**
     *@Route ("/", name="home")
     */

    public function home()
    {
        return $this->render(
            'blog/home.html.twig',
            [
                'titre' => "Bienvenue sur mon blog (titre paramétré)",
                'age' => 50
            ]
        );
    }
    /**
     * @Route("/blog/new", name="new_article")
     * @Route("/blog/{id}/edit", name="edit_article")
     */

    public function updateAndAddNewArticle(Article $article=null, Request $request, EntityManagerInterface $manager)
    {
// on peut ajouter un article et modifier: ajouter une nouvelle route pour l'update
// Attention:  on va affecter une valeur par défaut ($article=null) à $article (non défini) 
// résultat, on peut afficher un formulaire de modif avec les champs prérempli ou un nouveau formulaire
        if (!$article){
            $article = new Article(); //on doit utiliser cette instruction que si article=null 
        }    
        // $form = $this->createFormBuilder($article)
        // construction à la main du formulaire:
        // ->add('title', TextareaType::class)
        // ->add('content')
        // ->add('image')
        // /*  pour title, content, et image, symfony comprend combien de place cela va prendre.
        // */
        // ->add('save', submitType::class, [
        //     'label'=> 'Enregistrer article'
        // ])
        // ->getForm();

        // méthode qui consistait à créer une classe formulaire nouvel article 
        // taper dans le terminal: symfony console make:form => nouvelle classe
        // NOTE: le bouton "enregistrer l'article sera intégré dans la page html
        $form = $this->createForm(ArticleType::class, $article);
        // traitement du formulaire
        $form->handleRequest($request);

        /* vérifier si le formulaire est submit && valide = traitement*/
        if ($form->isSubmitted() && $form->isValid()) {
            
            // if (!$article->getId()) contrôle si l'Id existe déjà dans la BDD
            // si oui, alors on est face à un update
            if (!$article->getId()){
                $article->setCreatedAt(new \Datetime());
            }
            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute('blog');
        }
        return $this->render(
            'blog/form_article.html.twig',
            ['formArticle' => $form->createView()]
        );
    }

    /**
     * @Route("/blog/{id}", name="detail_art")
     * 
     */

    public function detailArticle(Article $article)
    {
        //  le Parameter Converter retourne directement 
        // ouvrir repository des articles
        // $repo = $this->getDoctrine()->getRepository(Article::class);
        // l'injection articlerepo $repo permet de se passer de la commande "$repo = $this->getDoctrine()->getRepository(Article::class);"

        // recup article id
        // $article = $repo->find($id);
        // géréner la vue . ligne 50 on ajoute un tableau en paramètre
        return $this->render('blog/detail.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/blog/{id}/delete_art", name="delete_art")
     */
    public function deleteArticle(Article $article, EntityManagerInterface $manager)
    {
        $manager->remove($article);
        $manager->flush();

        return $this->redirectToRoute('blog');
    }
}
