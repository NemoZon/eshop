<?php

namespace App\Class;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
  public function __construct(private RequestStack $requestStack)
  {

  }

  public function add($product): void
  {
    $cart = $this->requestStack->getSession()->get("cart", []);

    if (isset($cart[$product->getId()])) {
      $cart[$product->getId()] = [
        'quantity' => $cart[$product->getId()]['quantity'] + 1,
        'product' => $product,
      ];
    } else {
      $cart[$product->getId()] = [
        'quantity' => 1,
        'product' => $product,
      ];
    }

    $this->requestStack->getSession()->set("cart", $cart);
  }

  public function clear(): void
  {
    $this->requestStack->getSession()->set("cart", []);
  }

  public function delete($product): void
  {
    $cart = $this->requestStack->getSession()->get("cart", []);

    if (isset($cart[$product->getId()])) {
      if ($cart[$product->getId()]['quantity'] > 1) {
        $cart[$product->getId()] = [
          'quantity' => $cart[$product->getId()]['quantity'] - 1,
          'product' => $product,
        ];
      } else {
        unset($cart[$product->getId()]);
      }
    }
    $this->requestStack->getSession()->set("cart", $cart);
  }

  public function getCart()
  {
    return $this->requestStack->getSession()->get("cart", []);
  }

  public function getTotalQuantity(): int
  {
    $totalQuantity = 0;
    $cart = $this->requestStack->getSession()->get("cart", []);
    foreach ($cart as $product) {
      $totalQuantity += $product['quantity'];
    }
    return $totalQuantity;
  }

  public function getTotalPrice(): int
  {
    $total = 0;
    $cart = $this->requestStack->getSession()->get("cart", []);
    foreach ($cart as $product) {
      $total += $product['quantity'] * $product['product']->getPriceWt();
    }
    return $total;
  }
}