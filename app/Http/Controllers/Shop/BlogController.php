<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogPost;
use App\Models\BlogCategory;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with('category', 'user')
            ->where('is_published', true)
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->paginate(10);
            
        $categories = BlogCategory::all();

        return view('shop.blog.index', compact('posts', 'categories'));
    }

    public function show($slug)
    {
        $post = BlogPost::with('category', 'user')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('shop.blog.show', compact('post'));
    }
}