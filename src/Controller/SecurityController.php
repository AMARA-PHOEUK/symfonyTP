<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route ("/inscription", name="registration_secu")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder ){
        
   
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request); 
        if($form->isSubmitted() && $form->isValid()){

            $hash = $encoder->encodePassword($user, $user->getPassword() ); // on va hasher le password
            $user->setPassword($hash);// on remplace le mdp en clair par le $hash
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('blog');  
        }

        return $this->render('security/registration.html.twig',[
            'form' => $form->createView()
        ]);

    }
    /**
     * @Route("/connexion", name="login_secu")
     */
    public function login() {
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/deconnexion", name="logout_secu")
     */
    public function logout() {}

}
