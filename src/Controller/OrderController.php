<?php

namespace App\Controller;

use App\Class\Cart;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Form\OrderType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    #[Route('/commande/livraison', name: 'app_order')]
    public function index(): Response
    {
        $addresses = $this->getUser()->getAddresses();

        if (count($addresses) == 0) {
            return $this->redirectToRoute('app_add_address');
        }

        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $addresses,
            'action' => $this->generateUrl('app_order_summary'),
        ]);

        return $this->render('order/index.html.twig', [
            'deliveryForm' => $form,
        ]);
    }

    #[Route('/commande/recap', name: 'app_order_summary')]
    public function summary(Request $request, Cart $cart, EntityManagerInterface $em): Response
    {

        if ($request->getMethod() != 'POST') {
            return $this->redirectToRoute('app_order');
        }
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $this->getUser()->getAddresses(),
        ]);

        $form->handleRequest($request);

        $items = $cart->getCart();
        if ($form->isSubmitted() && $form->isValid()) {
            $addressObject = $form->get('addresses')->getData();
            $address = $addressObject->getFirstName() . ' ' . $addressObject->getLastName() . '<br>';
            $address = $address . $addressObject->getAddress() . '<br>';
            $address = $address . $addressObject->getPostalCode() . " " . $addressObject->getCountry() . '<br>';
            $address = $address . $addressObject->getCity() . '<br>';

            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt(new \DateTime());
            $order->setStatus(1);
            $order->setCarrierName($form->get('carriers')->getData()->getName());
            $order->setCarrierPrice($form->get('carriers')->getData()->getPrice());
            $order->setDelivery($address);
            $order->setUser($this->getUser());

            foreach ($items as $item) {
                $orderDetail = new OrderDetail();
                $orderDetail->setProduct($item['product']->getName());
                $orderDetail->setProductIllustration($item['product']->getIllustration());
                $orderDetail->setProductQuantity($item['quantity']);
                $orderDetail->setProductPrice($item['product']->getPrice());

                $order->addOrderDetail($orderDetail);
            }
            $em->persist($order);
            $em->flush();

            $this->addFlash('success', 'Commande validée');
        }

        return $this->render('order/summary.html.twig', [
            'choices' => $form->getData(),
            'cart' => $cart->getCart(),
            'totalWt' => $cart->getTotalPrice(),
            'order' => $order,
        ]);
    }
}
