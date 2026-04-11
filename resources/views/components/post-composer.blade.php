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
