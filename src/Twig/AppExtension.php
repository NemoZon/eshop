<?php
namespace App\Twig;

use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
  private $categoryRepository;
  public function __construct(CategoryRepository $categoryRepository)
  {
    $this->categoryRepository = $categoryRepository;
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
    ];
  }
}