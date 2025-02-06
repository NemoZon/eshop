<?php
namespace App\Twig;

use App\Class\Cart;
use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
  private $categoryRepository;
  private $cart;

  public function __construct(CategoryRepository $categoryRepository, Cart $cart)
  {
    $this->categoryRepository = $categoryRepository;
    $this->cart = $cart;
  }
  public function getFilters()
  {
    return [
      new TwigFilter('price', [$this, 'formatPrice']),
    ];
  }
  public function formatPrice($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
  {
    $price = number_format($number, $decimals, $decPoint, $thousandsSep);

    return $price . ' €';
  }

  public function getGlobals(): array
  {
    return [
      'allCategories' => $this->categoryRepository->findAll(),
      'totalQuantity' => $this->cart->getTotalQuantity(),
    ];
  }
}