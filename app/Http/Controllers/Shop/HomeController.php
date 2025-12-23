<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\View\View;
use App\Models\Slider;
use App\Models\Banner;

class HomeController extends Controller
{
    public function __construct(protected ProductRepositoryInterface $productRepository)
    {
    }

    public function __invoke(): View
    {
        $sliders = Slider::where('is_active', true)->orderBy('order')->get();
        $banners = Banner::where('is_active', true)->orderBy('order')->get();
        
        $featuredProducts = $this->productRepository->getFeatured(8);
        $newProducts = $this->productRepository->getNew(8);

        return view('shop.home', compact('featuredProducts', 'newProducts', 'sliders', 'banners'));
    }
}
