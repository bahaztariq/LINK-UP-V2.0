<x-app-layout>
    <!-- Sticky Search Header -->
    <!-- Sticky Search Header -->
    <header class="sticky top-0 z-10 glass-header px-6 py-4 border-b border-slate-200 flex flex-col md:flex-row gap-4 items-center justify-between">
        <form action="{{ route('search') }}" method="GET" class="relative group flex-1 w-full">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">search</span>
            <input name="q" value="{{ request('q') }}" class="w-full bg-slate-100 border-none rounded-xl pl-12 pr-4 py-3 text-sm focus:ring-2 focus:ring-primary transition-all placeholder-slate-400" placeholder="SearchLink..." type="text"/>
        </form>
        <button onclick="document.getElementById('join-friend-modal').classList.remove('hidden')" class="px-5 py-3 bg-black text-white rounded-xl font-bold text-sm hover:bg-gray-800 transition-all flex items-center gap-2 shrink-0">
            <span class="material-symbols-outlined text-[20px]">person_add</span>
            Join Friend
        </button>
    </header>

    <!-- Composer Section -->
    <div class="p-6 border-b border-slate-200" x-data="{ imagePreview: null, videoPreview: null }">
        <div class="flex gap-4">
             @if (Laravel\Jetstream\Jetstream::managesProfilePhotos() && Auth::user()->profile_photo_url)
                <div class="size-12 rounded-full bg-cover bg-center shrink-0" style='background-image: url("{{ Auth::user()->profile_photo_url }}")'></div>
             @else
                <div class="size-12 rounded-full bg-slate-200 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-slate-400">person</span>
                </div>
             @endif
            <div class="flex-1">
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <textarea name="content" class="w-full bg-transparent border-none focus:ring-0 text-lg placeholder:text-slate-400 resize-none p-0" placeholder="What's on your mind?" rows="3" required></textarea>
                    
                    <!-- Image Preview -->
                    <div x-show="imagePreview" x-cloak class="mt-4 relative">
                        <img :src="imagePreview" class="max-h-80 rounded-2xl border border-slate-200 object-cover w-full" alt="Preview">
                        <button type="button" @click="imagePreview = null; $refs.imageInput.value = ''" class="absolute top-2 right-2 bg-black/60 hover:bg-black/80 text-white rounded-full p-2 transition-colors">
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </button>
                    </div>

                    <!-- Video Preview -->
                    <div x-show="videoPreview" x-cloak class="mt-4 relative">
                        <video :src="videoPreview" class="max-h-80 rounded-2xl border border-slate-200 w-full" controls></video>
                        <button type="button" @click="videoPreview = null; $refs.videoInput.value = ''" class="absolute top-2 right-2 bg-black/60 hover:bg-black/80 text-white rounded-full p-2 transition-colors">
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </button>
                    </div>
                    
                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-100">
                        <div class="flex gap-1">
                            <label class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors cursor-pointer">
                                <span class="material-symbols-outlined">image</span>
                                <input type="file" name="image" x-ref="imageInput" class="hidden" accept="image/*" 
                                    @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { imagePreview = e.target.result; videoPreview = null; }; reader.readAsDataURL(file); }">
                            </label>
                            <label class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors cursor-pointer">
                                <span class="material-symbols-outlined">video_library</span>
                                <input type="file" name="video" x-ref="videoInput" class="hidden" accept="video/*"
                                    @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { videoPreview = e.target.result; imagePreview = null; }; reader.readAsDataURL(file); }">
                            </label>
                            
                        </div>
                        <button type="submit" class="bg-primary text-white font-bold px-6 py-2 rounded-lg hover:bg-primary/90 transition-colors">Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Feed Content -->
    <div class="flex flex-col pb-20">
        @forelse($posts as $post)
            <x-post-card :post="$post" />
        @empty
            <div class="p-8 text-center text-slate-500">
                <p>No posts yet. Be the first to share something!</p>
            </div>
        @endforelse
    </div>
    <!-- Join Friend Modal -->
    <div id="join-friend-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-[32px] p-8 max-w-md w-full mx-4 shadow-2xl relative">
            <button onclick="this.closest('#join-friend-modal').classList.add('hidden')" class="absolute top-6 right-6 p-2 hover:bg-gray-100 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
            
            <h3 class="text-2xl font-black text-gray-900 mb-2">Connect instantly</h3>
            <p class="text-gray-500 mb-8">Paste an invitation link or upload a QR code.</p>

            <div class="space-y-6">
                <!-- Link Input -->
                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700">Invitation Link</label>
                    <input type="text" oninput="if(this.value.includes('/invitations/accept/')) window.location.href=this.value" placeholder="Paste link here..." class="w-full bg-gray-50 border-gray-100 rounded-2xl p-4 focus:ring-black text-sm">
                </div>

                <div class="relative py-4">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-100"></div></div>
                    <div class="relative flex justify-center text-xs uppercase"><span class="bg-white px-2 text-gray-400">Or Upload QR</span></div>
                </div>

                <!-- QR Upload -->
                <label class="w-full border-2 border-dashed border-gray-200 rounded-[24px] p-10 flex flex-col items-center justify-center gap-3 cursor-pointer hover:border-black transition-colors group">
                    <input type="file" accept="image/*" class="hidden" onchange="decodeQR(this)">
                    <span class="material-symbols-outlined text-4xl text-gray-300 group-hover:text-black">qr_code_scanner</span>
                    <span class="text-sm font-medium text-gray-500 group-hover:text-black" id="qr-status">Click to upload image</span>
                </label>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <script>
        function decodeQR(input) {
            const file = input.files[0]; if (!file) return;
            const status = document.getElementById('qr-status'); status.innerText = 'Decoding...';
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    canvas.width = img.width; canvas.height = img.height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0);
                    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height);
                    if (code && code.data.includes('/invitations/accept/')) {
                        window.location.href = code.data;
                    } else {
                        status.innerText = 'Invalid QR code. Try again.';
                    }
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    </script>
</x-app-layout>
