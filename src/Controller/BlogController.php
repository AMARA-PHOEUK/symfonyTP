<?php

namespace App\Controller;

use App\Entity\Article;
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
    public function index( ArticleRepository $repo): Response
    {
        // getDoctrine = obtenir une instance (comme le $this)
        // $repo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repo->findAll();
        return $this->render('blog/index.html.twig', [
            'articles'=>$articles,
        ]);
    
    }
/**
 *@Route ("/", name="home")
 */

    public function home(){
        return $this->render('blog/home.html.twig',
    ['titre'=> "Bienvenue sur mon blog (titre paramétré)",
    'age' => 50
    ]
    );
    }
    /**
     * @Route("/blog/new", name="new_article")

     */
    
    public function addNewArticle(Request $request, EntityManagerInterface $manager) {
        
        $article = new Article();
        $form = $this->createFormBuilder($article)
                ->add('title', TextareaType::class)
                ->add('content')
                ->add('image')
                /*  pour title, content, et image, symfony comprend combien de place cela va prendre.
                * 
                */
                ->add('save', submitType::class, [
                    'label'=> 'Enregistrer article'
                ])
                ->getForm();
                // traitement du formulaire
                $form->handleRequest($request);

                dump($request);
                
                /* vérifier si le formulaire est submit && valide = traitement*/
                if($form->isSubmitted() && $form->isValid()){
                    $article->setCreatedAt(new \Datetime());
                    $manager->persist($article);
                    $manager->flush();
                    return $this->redirectToRoute('blog');
                }
            return $this->render('blog/form_article.html.twig',
        ['formArticle' =>$form->createView()]
        );

    }

    /**
     * @Route("/blog/{id}", name="detail_art")
     * 
     */

    public function detailArticle(Article $article){
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

}