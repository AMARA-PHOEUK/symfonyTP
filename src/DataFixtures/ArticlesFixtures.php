<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticlesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i= 1; $i <= 10 ; $i ++){
            $article = new Article();
            $article->setTitle("Titre de l'article $i")
                ->setContent("Contenu de l'article  n°$i")
                ->setImage("http://placehold.it/400*200")
                // pour DateTime, utiliser un anti Slash
                ->setCreatedAt(new \DateTime());
            
            $manager->persist($article);

        }
        // le persist consiste uniquement à enregistrer les $articles dans la mémoire vive
        // le flush permettra de les enregistrer dans la base de donnée
        $manager->flush();
        
    }
}
