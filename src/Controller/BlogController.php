<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(): Response
    {
        // getDoctrine = obtenir une instance (comme le $this)
        $repo = $this->getDoctrine()->getRepository(Article::class);
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
     * @Route("/blog/{id}", name="detail_art")
     * 
     */

    public function detailArticle($id){
        // ouvrir repository des articles
        $repo = $this->getDoctrine()->getRepository(Article::class);
        // recup article id
        $article = $repo->find($id);
        // géréner la vue
        return $this->render('blog/detail.html.twig',[
            'article' => $article 
        ]);
    }

}