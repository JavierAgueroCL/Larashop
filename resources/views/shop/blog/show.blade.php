<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <article class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
                @if($post->image_url)
                    <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="w-full h-96 object-cover">
                @endif
                <div class="p-8">
                    <div class="flex items-center text-sm text-gray-500 mb-4">
                        <span>{{ $post->published_at->format('d M, Y') }}</span>
                        <span class="mx-2">•</span>
                        <span class="text-primary-600">{{ $post->category->name }}</span>
                        <span class="mx-2">•</span>
                        <span>{{ __('Por') }} {{ $post->user ? $post->user->name : 'Admin' }}</span>
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
</x-app-layout>