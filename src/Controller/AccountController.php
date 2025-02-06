<?php

namespace App\Controller;

use App\Form\PasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AccountController extends AbstractController
{
    #[Route('/mon-compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    #[Route('/modifier-mot-de-passe', name: 'app_modifyPwd')]
    public function modifyPwd ( Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        //on recupere l'user en cours
        $user = $this->getUser();
        //j'embarque l'user dans le formulaire + j'envoi passwordHasher à notre form
        $form = $this->createForm(PasswordUserType::class, $user, [
            'passwordHasher' => $passwordHasher,
        ]);

        // écoute la soumission du form
        $form->handleRequest( $request );

        if ( $form->isSubmitted() && $form->isValid() ) {
            //envoyer en base de données (update) flush
            $entityManager->flush();

            //afficher un flash message de succes
            $this->addFlash(
                'success',
                'Mot de passe modifié'
            );

            return $this->redirectToRoute('app_account');
        }


        return $this->render('account/modifyPwd.html.twig', [
            'modifyPwd' => $form->createView(),
        ]);
    }
}
