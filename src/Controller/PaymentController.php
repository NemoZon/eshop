<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Stripe\Checkout\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaymentController extends AbstractController
{
    #[Route('/payment/{id_order}', name: 'app_payment')]
    public function index($id_order, OrderRepository $orderRepository): Response
    {

        $order = $orderRepository->find($id_order);
        require_once '../vendor/autoload.php';

        \Stripe\Stripe::setApiKey('sk_test_51PzhL711jAaDd40G0kGDhqD8g4w70iwKYDbrKPexvb5CbYQUAqZ6xcWARwY96GszNHbGqD33YQLxSo4U8w0jrC3A00Iyml0o3e');
        header('Content-Type: application/json');

        $YOUR_DOMAIN = 'http://localhost:8000';

        $checkout_session = Session::create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => 1500,
                        'product_data' => [
                            'name' => 'Produit test'
                        ]
                    ],
                    'quantity' => 1,

                ]
            ],
            'mode' => 'payment',

            //les 2 template de page
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);

        header("HTTP/1.1 303 See Other");
        header("Location: " . $checkout_session->url);
        return $this->redirect($checkout_session->url);
    }
}
