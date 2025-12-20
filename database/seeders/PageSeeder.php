<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '<h1>About LaraShop</h1><p>We are a modern e-commerce platform built with Laravel.</p>',
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<h1>Privacy Policy</h1><p>Your privacy is important to us.</p>',
            ],
            [
                'title' => 'Terms and Conditions',
                'slug' => 'terms-and-conditions',
                'content' => '<h1>Terms and Conditions</h1><p>Please read these terms carefully.</p>',
            ],
            [
                'title' => 'FAQ',
                'slug' => 'faq',
                'content' => '<h1>Frequently Asked Questions</h1><p>Here you can find answers to common questions.</p>',
            ],
            [
                'title' => 'Location',
                'slug' => 'location',
                'content' => '<h1>Our Location</h1><p>We are located at 123 Street, London, UK.</p>',
            ],
            [
                'title' => 'Affiliates',
                'slug' => 'affiliates',
                'content' => '<h1>Affiliate Program</h1><p>Join our affiliate program and earn commissions.</p>',
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact',
                'content' => '<h1>Contact Us</h1><p>Email: info@larashop.com<br>Phone: +1 234 567 890</p>',
            ],
            [
                'title' => 'Blog',
                'slug' => 'blog',
                'content' => '<h1>Our Blog</h1><p>Latest news and updates from LaraShop.</p>',
            ],
        ];

        foreach ($pages as $page) {
            Page::firstOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }
    }
}
