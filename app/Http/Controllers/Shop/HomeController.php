<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(protected ProductRepositoryInterface $productRepository)
    {
    }

    public function __invoke(): View
    {
        $featuredProducts = $this->productRepository->getFeatured(8);
        $newProducts = $this->productRepository->getNew(8);

        return view('shop.home', compact('featuredProducts', 'newProducts'));
    }
}