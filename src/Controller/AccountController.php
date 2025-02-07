<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressUserType;
use App\Form\PasswordUserType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AccountController extends AbstractController
{
    #[Route('/mon-compte/adresses', name: 'app_address')]
    public function address(): Response
    {
        return $this->render('account/address.html.twig');
    }

    #[Route('/mon-compte/adresses/ajouter', name: 'app_add_address')]
    public function addressAddForm(Request $request, EntityManagerInterface $em): Response
    {

        $address = new Address();

        $address->setUser($this->getUser());

        $form = $this->createForm(AddressUserType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($address);
            $em->flush();
            $this->addFlash('success', 'Adresse ajoutée');
            return $this->redirectToRoute('app_address');
        }

        return $this->render('account/addressAddForm.html.twig', [
            'addressForm' => $form->createView(),
        ]);
    }

    #[Route('/mon-compte/adresses/modifier/{id}', name: 'app_modify_address')]
    public function addressModifyForm($id, Request $request, AddressRepository $addressRepository, EntityManagerInterface $em): Response
    {

        $address = $addressRepository->find($id);

        $isUserAddress = $this->getUser()->getAddresses()->contains($address);

        if (!$isUserAddress || !$address) {
            return $this->redirectToRoute('app_address');
        }

        $form = $this->createForm(AddressUserType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Adresse modifiée');
            return $this->redirectToRoute('app_address');
        }

        return $this->render('account/addressModifyForm.html.twig', [
            'addressForm' => $form->createView(),
        ]);
    }

    #[Route('/mon-compte/adresses/delete/{id}', name: 'app_delete_address')]
    public function addressDelete($id, Request $request, AddressRepository $addressRepository, EntityManagerInterface $em): Response
    {
        $address = $addressRepository->find($id);

        if (!$address || !$this->getUser()->getAddresses()->contains($address)) {
            return $this->redirectToRoute('app_address');
        }

        $em->remove($address);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/mon-compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    #[Route('/modifier-mot-de-passe', name: 'app_modifyPwd')]
    public function modifyPwd(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        //on recupere l'user en cours
        $user = $this->getUser();
        //j'embarque l'user dans le formulaire + j'envoi passwordHasher à notre form
        $form = $this->createForm(PasswordUserType::class, $user, [
            'passwordHasher' => $passwordHasher,
        ]);

        // écoute la soumission du form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
