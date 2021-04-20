<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticlesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        // Boucle B1: générer des catégories
        for ($i=1; $i <= 5; $i++){
            $category = new Category();
            $category->setName($faker->word())
                    ->setDescription($faker->sentence());
            $manager->persist($category);
    
            
            // boucle b2 : generation des articles
                for ($j= 1; $j <= mt_rand(3,5) ; $j ++){
                    $article = new Article();
                    // création aléatoire de contenu
                    $content = "<p>";
                    $content .= join( " </p><p> ", $faker->paragraphs(4));
                    $content .="</p>";
                    // création d'image dummy image
                    // str_replace = remplacer le "#" par ""
                    $backColor = str_replace("#","", $faker->hexColor());
                    $textColor = str_replace("#","", $faker->hexColor());
                    $image = "https://dummyimage.com/600x400/$backColor/$textColor";

                    $article->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setImage($image)
                        // pour DateTime, utiliser un anti Slash
                        ->setCreatedAt($faker->dateTimeBetween('-50 years'))
                        ->setCategory($category);
                    $manager->persist($article);

                    // Boucle 3; Génération des commentaires de cet article
                    for ($k = 1; $k <= mt_rand(0, 15); $k ++){
                        $comment = new Comment;
                        // génération de commentaire aléatoire
                        $content = "<p>". join( " </p><p> ", $faker->paragraphs(2))."</p>";

    // pour les dates: on calcule la différence entre la date d'aujourd'hui et la date de création de l'article
                        $curDate = new \DateTime();
                        $interval = $curDate->diff($article->getCreatedAt());
                        $nbDays = $interval->days;
    // plus simple et en 1 ligne $nbDays= (new DateTime())->diff($article->getCreatedAt())->days;
    // '- $nbDays days' =>'-'.$nbDays. 'days' ()' ('-30 days)
                        $comment->setAuthor($faker->name())
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween('-'.$nbDays.'days'))
                            ->setArticle($article);
                    $manager->persist($comment);

                } //fin boucle B3
        } // fin boucle B2
    } // fin boucle B1
        // le persist consiste uniquement à enregistrer les $articles dans la mémoire vive
        // le flush permettra de les enregistrer dans la base de donnée
        $manager->flush();
        
    }
}
