<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">{{ __('Últimas Noticias') }}</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <div class="md:col-span-1">
                    <div class="bg-white p-4 shadow-sm rounded-lg border border-gray-200">
                        <h3 class="font-bold text-lg mb-4">{{ __('Categorías') }}</h3>
                        <ul class="space-y-2">
                            @foreach($categories as $category)
                                <li>
                                    <a href="#" class="text-gray-600 hover:text-primary-600">{{ $category->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Posts -->
                <div class="md:col-span-3 space-y-8">
                    @foreach($posts as $post)
                        <article class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200 flex flex-col md:flex-row">
                            @if($post->image_url)
                                <div class="md:w-1/3">
                                    <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="w-full h-48 md:h-full object-cover">
                                </div>
                            @endif
                            <div class="p-6 md:w-2/3 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center text-sm text-gray-500 mb-2">
                                        <span>{{ $post->published_at->format('d M, Y') }}</span>
                                        <span class="mx-2">•</span>
                                        <span class="text-primary-600">{{ $post->category->name }}</span>
                                    </div>
                                    <h2 class="text-xl font-bold text-gray-900 mb-2">
                                        <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-primary-600">{{ $post->title }}</a>
                                    </h2>
                                    <p class="text-gray-600 line-clamp-3">{{ Str::limit(strip_tags($post->content), 150) }}</p>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="text-primary-600 font-semibold hover:underline">{{ __('Leer más') }}</a>
                                </div>
                            </div>
                        </article>
                    @endforeach

                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>