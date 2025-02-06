<?php

namespace App\Controller;

use App\Class\Cart;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add(Request $request, $id, Cart $cart, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        $cart->add($product);

        $this->addFlash('success', 'Article ajouté au panier');

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_remove')]
    public function remove(Request $request, $id, Cart $cart, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        $cart->delete($product);

        $this->addFlash('danger', 'Article supprimé');

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/cart/clear', name: 'app_cart_clear')]
    public function clear(Request $request, Cart $cart): Response
    {
        $cart->clear();

        $this->addFlash('danger', 'Panier vidé');

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/mon-panier', name: 'app_cart')]
    public function index(Cart $cart): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getCart(),
            'totalPrice' => $cart->getTotalPrice(),
        ]);
    }
}
