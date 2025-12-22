<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
 <head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <meta name="csrf-token" content="{{ csrf_token() }}">

 <title>{{ $meta_title ?? config('app.name', 'Laravel') }}</title>

 <meta name="description" content="{{ $meta_description ?? '' }}">
 {{ $meta ?? '' }}

 <!-- Fonts -->
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

 <!-- Scripts -->
 @vite(['resources/css/app.css', 'resources/js/app.js'])
 </head>
 <body class="font-sans antialiased text-secondary bg-white">
 <div class="min-h-screen flex flex-col">
 
 <!-- Header Group -->
 @include('components.layout.top-header')
 @include('components.layout.main-header')
 @include('components.layout.navbar')

 <!-- Page Content -->
 <main class="flex-grow">
 {{ $slot }}
 </main>

         @include('layouts.footer')
         </div>
         
         <!-- Add to Wishlist Modal -->
         <x-wishlist.add-modal />
 
         <!-- Quick View Modal -->
         <div x-data="{ open: false, html: '' }" 
              @open-quick-view.window="open = true; html = '<div class=\'p-10 text-center\'><svg class=\'animate-spin h-8 w-8 text-indigo-600 mx-auto\' xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\'><circle class=\'opacity-25\' cx=\'12\' cy=\'12\' r=\'10\' stroke=\'currentColor\' stroke-width=\'4\'></circle><path class=\'opacity-75\' fill=\'currentColor\' d=\'M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z\'></path></svg></div>'; fetch('/products/'+$event.detail.id+'/quick-view').then(r => r.text()).then(h => html = h);"
              x-show="open" 
              style="display: none;" 
              class="fixed inset-0 z-50 flex items-center justify-center"
              x-cloak>
             <!-- Backdrop -->
             <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-md transition-opacity" @click="open = false" x-show="open" x-transition.opacity></div>

             <!-- Content -->
             <div class="relative z-10" x-html="html" x-show="open" x-transition.scale></div>
         </div>
 
         <!-- Cart Sidebar -->
         <x-cart.sidebar />
     </body>
 </html>
