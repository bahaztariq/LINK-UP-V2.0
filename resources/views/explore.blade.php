<x-app-layout>
    <div class="flex min-h-screen">
        <!-- Middle Column: Explore Grid -->
        <div class="flex-1 border-r border-gray-100 dark:border-gray-800">
            <!-- Search Header -->
            <div class="sticky top-0 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md z-30 p-3 border-b border-gray-100 dark:border-gray-800">
                <div class="relative group">
                     <span class="absolute left-4 top-3 text-gray-500 group-focus-within:text-blue-500">
                        <span class="material-symbols-outlined text-[20px]">search</span>
                    </span>
                    <form action="{{ route('search') }}" method="GET">
                        <input type="text" name="query" placeholder="Search LinkUP" class="w-full bg-gray-100 dark:bg-gray-800 border-none rounded-full py-2.5 pl-12 pr-4 text-[15px] focus:bg-white dark:focus:bg-gray-700 focus:ring-2 focus:ring-blue-400 transition-all placeholder-gray-500">
                    </form>
                </div>
            </div>

            <!-- Category Pills -->
            <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-800 flex gap-3 overflow-x-auto no-scrollbar">
                @foreach(['For you', 'Trending', 'News', 'Sports', 'Entertainment', 'Tech', 'Art'] as $cat)
                <a href="{{ route('explore', ['category' => $cat]) }}" class="whitespace-nowrap px-4 py-1.5 rounded-full font-bold text-[15px] transition-colors {{ (isset($category) ? $category : 'For you') === $cat ? 'bg-black dark:bg-white text-white dark:text-black' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    {{ $cat }}
                </a>
                @endforeach
            </div>

            <!-- Media Grid -->
            <div class="grid grid-cols-3 gap-0.5 pb-20">
                 @forelse($posts as $post)
                 <div class="relative aspect-square bg-gray-100 dark:bg-gray-800 group cursor-pointer overflow-hidden">
                    @if($post->image_path)
                        <img src="{{ Storage::url($post->image_path) }}" alt="Explore Media" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                    @elseif($post->video_path)
                        <video class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" muted>
                            <source src="{{ Storage::url($post->video_path) }}" type="video/mp4">
                        </video>
                    @else
                        <div class="w-full h-full flex items-center justify-center p-4 text-center text-sm text-gray-500">
                            {{ Str::limit($post->content, 50) }}
                        </div>
                    @endif
                    
                    <!-- Hover Overlay -->
                    <a href="{{ route('posts.show', $post->id) }}" class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-4 text-white font-bold">
                        <div class="flex items-center gap-1">
                            <span class="material-symbols-outlined font-fill text-[20px]">favorite</span>
                            <span>{{ $post->reactions->count() }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="material-symbols-outlined font-fill text-[20px]">chat_bubble</span>
                            <span>{{ $post->comments->count() }}</span>
                        </div>
                    </a>

                    <!-- Type Indicator (Video) -->
                    @if($post->video_path)
                    <div class="absolute top-2 right-2 text-white drop-shadow-md">
                        <span class="material-symbols-outlined font-fill text-[20px]">movie</span>
                    </div>
                    @endif
                 </div>
                 @empty
                 <div class="col-span-3 p-20 text-center text-gray-500">
                     <p class="text-lg">No posts found in this category.</p>
                 </div>
                 @endforelse
            </div>
            
            <div class="p-4">
                {{ $posts->appends(['category' => request('category')])->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
