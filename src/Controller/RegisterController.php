<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]

    //injection de dépendance de request et entityManagerInterface
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // une instance de User
        $user = new User();

        //creation du formulaire depuis RegisterType et on passe user en parametre
        $form= $this->createForm(RegisterType::class, $user);
        //écoute la soumission du form en prenant request
        $form->handleRequest($request);

        //verifier si le form est valide et si il a été submit
        if ($form->isSubmitted() && $form->isValid()) {
            //figer les données sur la table user
            $entityManager->persist($user);
            // j'envoi les données en BD
            //dd($user);
            $entityManager->flush();

            //afficher un flash message de succes
            $this->addFlash(
                'success',
                'Compte créé, merci de vous connecter'
            );

            return $this->redirectToRoute('app_login');

        }

        return $this->render('register/index.html.twig', [
            'controller_name' => 'RegisterController',
            'registerForm' => $form->createView(),
        ]);
    }
}