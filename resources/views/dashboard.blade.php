<x-app-layout>
    <!-- Sticky Search Header -->
    <x-feed-header />

    <!-- Composer Section -->
    <x-post-composer />

    <!-- Feed Content -->
    <div class="flex flex-col pb-20">
        @forelse($posts as $post)
            <x-post-card :post="$post" />
        @empty
            <div class="p-8 text-center text-slate-500">
                <p>No posts yet. Be the first to share something!</p>
            </div>
        @endforelse

        <div class="px-6 py-4">
            {{ $posts->links() }}
        </div>
    </div>
@push('modals')
    <!-- Join Friend Modal -->
    <div id="join-friend-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-[32px] p-8 max-w-sm w-full mx-4 shadow-2xl relative">
            <button onclick="this.closest('#join-friend-modal').classList.add('hidden')" class="absolute top-6 right-6 p-2 hover:bg-gray-100 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
            
            <h3 class="text-2xl font-black text-gray-900 mb-2">Connect instantly</h3>
            <p class="text-gray-500 mb-8 text-sm">Paste an invitation link or upload a QR code.</p>

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
                    <span class="text-sm font-medium text-gray-500 group-hover:text-black text-center" id="qr-status">Click to upload image</span>
                </label>
            </div>
        </div>
    </div>

    <!-- QR Modal -->
    <div id="qr-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-black/60 backdrop-blur-sm">
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
@endpush

    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <script>
        async function toggleQR(show) {
            const modal = document.getElementById('qr-modal');
            modal.classList.toggle('hidden', !show);
            if (!show) return;

            const res = await fetch('{{ route('invitations.generate') }}', { 
                method: 'POST', 
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                } 
            }).then(r => r.json());
            
            document.getElementById('qr-image').src = `{{ url('/invitations/qr') }}/${res.token}`;
            document.getElementById('invite-link-text').innerText = res.url;
            document.getElementById('qr-image').onload = () => {
                document.getElementById('qr-loading').classList.add('hidden');
                document.getElementById('qr-image').classList.remove('hidden');
            };
        }

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
