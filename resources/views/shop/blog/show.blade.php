<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-[1350px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <div class="md:col-span-1">
                    <div class="bg-white p-4 shadow-sm rounded-lg border border-gray-200">
                        <h3 class="font-bold text-lg mb-4">{{ __('Categorías') }}</h3>
                        <ul class="space-y-2">
                            @foreach(\App\Models\BlogCategory::all() as $category)
                                <li>
                                    <a href="{{ route('blog.index', ['category' => $category->slug]) }}" class="text-gray-600 hover:text-primary-600">{{ $category->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Post Content -->
                <div class="md:col-span-3">
                    <article class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
                        @if($post->image_url)
                            <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="w-full h-96 object-cover">
                        @endif
                        <div class="p-8">
                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <span>{{ $post->published_at->format('d M, Y') }}</span>
                                <span class="mx-2">•</span>
                                <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="text-primary-600 hover:underline">{{ $post->category->name }}</a>
                            </div>
                            
                            <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $post->title }}</h1>
                            
                            <div class="prose max-w-none text-gray-700">
                                {!! nl2br(e($post->content)) !!}
                            </div>
                        </div>
                    </article>

                    <div class="mt-8">
                        <a href="{{ route('blog.index') }}" class="text-primary-600 font-semibold hover:underline">&larr; {{ __('Volver al Blog') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>