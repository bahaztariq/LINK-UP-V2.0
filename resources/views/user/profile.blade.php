<x-app-layout>
    <div class="flex min-h-screen">
        <!-- Middle Column: Profile Info & Feed -->
        <div class="flex-1 w-full  border-r border-gray-100">
            <!-- Header (Twitter Style) -->
            <div class="sticky top-0 bg-white/80 backdrop-blur-md z-30 px-4 py-1 border-b border-gray-100 flex items-center gap-6">
                <a href="{{ url()->previous() }}" class="rounded-full p-2 hover:bg-gray-100 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                </a>
                <div class="flex flex-col">
                    <h2 class="text-lg font-bold leading-5">{{ $user->name }}</h2>
                    <span class="text-xs text-gray-500">{{ $user->posts()->count() }} posts</span>
                </div>
            </div>

            <!-- Cover Image -->
            <div class="h-[200px] bg-gradient-to-r from-blue-400 to-purple-500 relative">
                <!-- Fallback or actual cover if available -->
            </div>

            <!-- Profile Actions & Info -->
            <div class="px-4 pb-4 border-b border-gray-100 relative">
                <div class="flex justify-between items-start">
                    <!-- Avatar (Overlapping) -->
                    <div class="-mt-[50px] mb-3 relative">
                         <div class="size-[134px] rounded-full p-1 bg-white">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos() && $user->profile_photo_url)
                                <img class="w-full h-full rounded-full object-cover" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
                            @else
                                <div class="w-full h-full rounded-full bg-gray-200 flex items-center justify-center text-4xl font-bold text-gray-400">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-3">
                         @if(Auth::id() === $user->id)
                            <div class="flex gap-2">
                                <a href="{{ route('profile.show') }}" class="px-4 py-1.5 border border-gray-300 rounded-full font-bold text-[15px] hover:bg-gray-100 transition-colors">
                                    Edit Profile
                                </a>
                                <button onclick="toggleQR(true)" class="w-9 h-9 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100 transition-colors group" title="Show QR Code">
                                    <span class="material-symbols-outlined text-[20px] text-slate-600 group-hover:text-primary transition-colors">qr_code_2</span>
                                </button>
                            </div>
                        @else
                             <!-- Friend/Follow Logic -->
                             <div class="flex gap-2">
                                @if(Auth::user()->isFriendWith($user))
                                    {{-- send message --}}
                                    <form action="{{ route('conversation.create', $user->id) }}" method="POST">
                                        @csrf @method('post')
                                        <button type="submit" class="px-4 py-1.5 bg-black  text-white rounded-full font-bold text-[15px] hover:bg-slate-800 transition-colors">
                                            message
                                        </button>
                                    </form>
                                    {{-- Unfriend --}}
                                    <form action="{{ route('friendships.destroy', $user->friendshipsReceived()->where('requester_id', Auth::id())->first()->id ?? $user->friendshipsSent()->where('addressee_id', Auth::id())->first()->id) }}" method="POST" onsubmit="return confirm('Remove friend?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-4 py-1.5 bg-white border border-red-200 text-red-600 rounded-full font-bold text-[15px] hover:bg-red-50 transition-colors">
                                            Unfriend
                                        </button>
                                    </form>
                                @elseif($request = Auth::user()->getPendingFriendRequestTo($user))
                                    {{-- Cancel Request --}}
                                    <form action="{{ route('friendships.destroy', $request->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-4 py-1.5 bg-white border border-slate-300 text-slate-600 rounded-full font-bold text-[15px] hover:bg-slate-100 transition-colors">
                                            Requested
                                        </button>
                                    </form>
                                @elseif($request = Auth::user()->getPendingFriendRequestFrom($user))
                                    {{-- Accept Request --}}
                                    <form action="{{ route('friendships.update', $request->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <button type="submit" class="px-4 py-1.5 bg-primary text-white rounded-full font-bold text-[15px] hover:bg-primary/90 transition-colors">
                                            Accept
                                        </button>
                                    </form>
                                @else
                                    {{-- Add Friend --}}
                                    <form action="{{ route('friendships.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="addressee_id" value="{{ $user->id }}">
                                        <button type="submit" class="px-4 py-1.5 bg-black text-white rounded-full font-bold text-[15px] hover:bg-gray-800 transition-colors">
                                            Add Friend
                                        </button>
                                    </form>
                                @endif
                             </div>
                        @endif
                    </div>
                </div>

                <!-- Bio & Stats -->
                <div>
                     <h1 class="text-xl font-extrabold leading-6">{{ $user->name }}</h1>
                     <p class="text-[15px] text-gray-500 mb-3">@ {{ strtolower(str_replace(' ', '', $user->name)) }}</p>
                     
                     {{-- <p class="text-[15px] text-gray-900 mb-3">
                         Digital Creator • Tech Enthusiast • Building LinkUP 🚀
                     </p>

                     <div class="flex gap-1 text-[15px] text-gray-500 mb-3">
                        <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                        <span>Joined {{ $user->created_at->format('F Y') }}</span>
                     </div>
                     
                     <div class="flex gap-4 text-[15px]">
                         <div class="hover:underline cursor-pointer">
                             <span class="font-bold text-gray-900">{{ rand(100, 500) }}</span> 
                             <span class="text-gray-500">Following</span>
                         </div>
                         <div class="hover:underline cursor-pointer">
                             <span class="font-bold text-gray-900">{{ rand(500, 2000) }}</span> 
                             <span class="text-gray-500">Followers</span>
                         </div>
                     </div> --}}
                </div>
            </div>

            <!-- Tabs -->
            <div class="flex border-b border-gray-100">
                <a href="#" class="flex-1 h-[53px] hover:bg-gray-50 flex items-center justify-center transition-colors relative group">
                     <span class="font-bold text-[15px]">Posts</span>
                     <div class="absolute bottom-0 h-[4px] w-[56px] bg-[#1DA1F2] rounded-full"></div>
                </a>
                <a href="#" class="flex-1 h-[53px] hover:bg-gray-50 flex items-center justify-center transition-colors text-gray-500 font-medium text-[15px]">
                     <span>Replies</span>
                </a>
                 <a href="#" class="flex-1 h-[53px] hover:bg-gray-50 flex items-center justify-center transition-colors text-gray-500 font-medium text-[15px]">
                     <span>Media</span>
                </a>
                 <a href="#" class="flex-1 h-[53px] hover:bg-gray-50 flex items-center justify-center transition-colors text-gray-500 font-medium text-[15px]">
                     <span>Likes</span>
                </a>
            </div>

            <!-- Feed -->
            <div class="flex flex-col pb-20">
                @forelse($posts as $post)
                    <x-post-card :post="$post" />
                @empty
                    <div class="p-10 text-center">
                        <div class="w-full flex justify-center mb-4">
                             <img src="https://abs.twimg.com/sticky/illustrations/empty-states/masked-doll-head-with-camera-800x400.v1.png" alt="No Posts" class="w-[300px] opacity-75">
                        </div>
                        <h2 class="text-3xl font-extrabold text-gray-900 mb-2">No posts yet</h2>
                        <p class="text-gray-500">When {{ $user->name }} posts, it'll show up here.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right Column: Widgets (Same as Dashboard for now) -->
        
    </div>

    <!-- QR Modal -->
    <div id="qr-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-[32px] p-8 max-w-sm w-full mx-4 shadow-2xl">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="text-2xl font-black text-gray-900">Scan to Connect</h3>
                    <p class="text-sm text-gray-500">Instantly add friends</p>
                </div>
                <button onclick="toggleQR(false)" class="size-10 flex items-center justify-center hover:bg-gray-100 rounded-full">
                    <span class="material-symbols-outlined text-gray-400">close</span>
                </button>
            </div>
            
            <div class="flex flex-col items-center">
                <div class="bg-gray-50 p-6 rounded-[24px] mb-8 border border-gray-100 relative min-h-[240px] min-w-[240px] flex items-center justify-center">
                    <div id="qr-loading" class="absolute inset-0 flex items-center justify-center">
                        <div class="size-10 border-4 border-gray-200 border-t-black rounded-full animate-spin"></div>
                    </div>
                    <img id="qr-image" src="" alt="QR Code" class="hidden w-full h-full">
                </div>
                
                <p class="text-center text-[15px] text-gray-500 mb-8 font-medium">
                    Show this to someone or share your link to connect! ⚡️
                </p>
                
                <div class="w-full flex flex-col gap-4">
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-between gap-4">
                        <span id="invite-link-text" class="text-xs text-gray-400 truncate font-mono">Generating...</span>
                        <button onclick="navigator.clipboard.writeText(document.getElementById('invite-link-text').innerText); this.innerText='Copied!'; setTimeout(()=>this.innerText='Copy Link', 2000)" class="text-black font-bold text-sm bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all active:scale-95 shrink-0">
                            Copy Link
                        </button>
                    </div>
                    <button onclick="toggleQR(false)" class="w-full py-4 bg-black text-white rounded-full font-bold text-[15px] hover:bg-gray-800 transition-all">
                        Done
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function toggleQR(show) {
            document.getElementById('qr-modal').classList.toggle('hidden', !show);
            if (!show) return;

            const res = await fetch('{{ route('invitations.generate') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(r => r.json());
            document.getElementById('qr-image').src = `{{ url('/invitations/qr') }}/${res.token}`;
            document.getElementById('invite-link-text').innerText = res.url;
            document.getElementById('qr-image').onload = () => {
                document.getElementById('qr-loading').classList.add('hidden');
                document.getElementById('qr-image').classList.remove('hidden');
            };
        }
    </script>
</x-app-layout>
