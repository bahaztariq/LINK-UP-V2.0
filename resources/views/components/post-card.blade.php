@props(['post'])

<article class="p-6 border-b border-slate-200 hover:bg-slate-50/50 transition-colors cursor-pointer" x-data="{ showComments: false, editing: false, editContent: @js($post->content) }">
    <div class="flex gap-4">
        <!-- Avatar -->
        <div class="shrink-0">
             <a href="{{ route('user.show', $post->user->id) }}">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos() && $post->user->profile_photo_url)
                    <div class="w-12 h-12 rounded-full bg-cover bg-center shrink-0" style='background-image: url("{{ $post->user->profile_photo_url }}")'></div>
                @else
                    <div class="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-slate-400">person</span>
                    </div>
                @endif
            </a>
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-1 text-[15px] leading-5">
                    <a href="{{ route('user.show', $post->user->id) }}" class="font-bold text-slate-900 hover:underline truncate">{{ $post->user->name }}</a>
                    <span class="material-symbols-outlined text-primary text-sm font-fill">verified</span>
                    <span class="text-slate-500 ml-1">@user_{{ $post->user->id }}</span>
                    <span class="text-slate-500">·</span>
                    <span class="text-slate-500 hover:underline">{{ $post->created_at->diffForHumans(null, true, true) }}</span>
                </div>
                
                @if(Auth::id() === $post->user_id)
                <div class="relative" x-data="{ open: false }">
                    <button @click.stop="open = !open" @click.away="open = false" class="text-slate-400 hover:text-primary p-1 rounded-full transition-colors">
                        <span class="material-symbols-outlined">more_horiz</span>
                    </button>
                    <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-[0_0_10px_rgba(0,0,0,0.1)] border border-slate-100 z-20 py-1" x-cloak>
                        <button @click="editing = true; open = false" class="w-full text-left px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">edit</span>
                            Edit Post
                        </button>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full text-left px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-slate-50 flex items-center gap-2" onclick="return confirm('Are you sure?')">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                Delete Post
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>

            <!-- Text Content -->
            <template x-if="!editing">
                <p class="mt-2 text-slate-800 leading-normal whitespace-pre-wrap">{{ $post->content }}</p>
            </template>
            
            <template x-if="editing">
                <div class="mt-2" @click.stop>
                    <form action="{{ route('posts.update', $post->id) }}" method="POST" class="space-y-2">
                        @csrf
                        @method('PUT')
                        <textarea x-model="editContent" name="content" rows="3" class="w-full bg-white border border-slate-200 rounded-xl p-3 text-slate-800 focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
                        <div class="flex gap-2 justify-end">
                            <button type="button" @click="editing = false" class="text-sm font-bold text-slate-500 hover:text-slate-700 px-3 py-1.5 rounded-full hover:bg-slate-100">Cancel</button>
                            <button type="submit" class="bg-primary text-white text-sm font-bold px-4 py-1.5 rounded-full hover:bg-primary/90">Save</button>
                        </div>
                    </form>
                </div>
            </template>

            <!-- Media Attachment -->
            @if($post->image_path)
            <div class="mt-4 rounded-2xl overflow-hidden border border-slate-200 bg-slate-100">
                 <img src="{{ asset('storage/' . $post->image_path) }}" alt="Post Image" class="w-full h-auto object-cover max-h-[500px]">
            </div>
            @endif

            @if($post->video_path)
            <div class="mt-4 rounded-2xl overflow-hidden border border-slate-200 bg-black">
                 <video src="{{ asset('storage/' . $post->video_path) }}" controls class="w-full h-auto max-h-[500px]"></video>
            </div>
            @endif

            <!-- Social Actions -->
            <div class="flex items-center justify-start gap-4 mt-4 max-w-md">
                <!-- Like -->
                 <form action="{{ route('reactions.toggle') }}" method="POST" @click.stop>
                    @csrf
                    <input type="hidden" name="reactable_id" value="{{ $post->id }}">
                    <input type="hidden" name="user_id" value="{{ $post->user_id }}">
                    <input type="hidden" name="reactable_type" value="App\Models\Post">
                    <input type="hidden" name="type" value="like">
                    <button type="submit" class="flex items-center gap-2 text-slate-500 hover:text-red-500 transition-colors group">
                        <span class="material-symbols-outlined text-[20px] {{ $post->isLikedBy(Auth::user()) ? 'text-red-500 font-fill' : 'group-hover:font-fill' }}">favorite</span>
                        <span class="text-xs font-bold {{ $post->isLikedBy(Auth::user()) ? 'text-red-500' : '' }}">{{ $post->reactions()->count() }}</span>
                    </button>
                </form>

                <!-- Comments -->
                <button @click.stop="showComments = !showComments" class="flex items-center gap-2 text-slate-500 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[20px]">chat_bubble</span>
                    <span class="text-xs font-bold">{{ $post->comments()->whereNull('parent_id')->count() }}</span>
                </button>
            </div>

            <!-- Comments Section -->
            <div x-show="showComments" class="mt-4 pt-4 border-t border-slate-100" @click.stop x-cloak>
                 <!-- Comment Form -->
                <form action="{{ route('comments.store') }}" method="POST" class="flex gap-3 mb-4 items-start">
                    @csrf
                    <input type="hidden" name="commentable_id" value="{{ $post->id }}">
                    <input type="hidden" name="user_id" value="{{ $post->user_id }}">
                    <input type="hidden" name="commentable_type" value="App\Models\Post">
                    
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos() && Auth::user()->profile_photo_url)
                        <img src="{{ Auth::user()->profile_photo_url }}" class="w-8 h-8 rounded-full object-cover">
                    @else
                        <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center">
                            <span class="material-symbols-outlined text-slate-500 text-sm">person</span>
                        </div>
                    @endif
                    
                    <div class="flex-1">
                        <input type="text" name="body" placeholder="Post your reply" class="w-full bg-transparent border-0 border-b border-slate-200 focus:border-primary focus:ring-0 px-0 py-2 text-sm placeholder-slate-500">
                    </div>
                    <button type="submit" class="text-primary font-bold text-sm hover:bg-primary/10 px-3 py-1.5 rounded-full transition-colors disabled:opacity-50">Reply</button>
                </form>

                <!-- Comments List -->
                <div class="space-y-0 pl-0">
                    @foreach($post->comments->whereNull('parent_id') as $comment)
                        <x-comment-item :comment="$comment" />
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</article>
