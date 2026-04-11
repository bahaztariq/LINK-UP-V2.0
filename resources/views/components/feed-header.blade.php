<header class="sticky top-0 z-10 glass-header px-6 py-4 border-b border-slate-200 flex flex-col md:flex-row gap-4 items-center justify-between">
    <form action="{{ route('search') }}" method="GET" class="relative group flex-1 w-full">
        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">search</span>
        <input name="q" value="{{ request('q') }}" class="w-full bg-slate-100 border-none rounded-xl pl-12 pr-4 py-3 text-sm focus:ring-2 focus:ring-primary transition-all placeholder-slate-400" placeholder="SearchLink..." type="text"/>
    </form>
    <div class="flex items-center gap-2 shrink-0">
        <button onclick="toggleQR(true)" class="p-3 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-all flex items-center justify-center shrink-0" title="Show My QR">
            <span class="material-symbols-outlined text-[20px]">qr_code_2</span>
        </button>
        <button onclick="document.getElementById('join-friend-modal').classList.remove('hidden')" class="px-5 py-3 bg-black text-white rounded-xl font-bold text-sm hover:bg-gray-800 transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-[20px]">person_add</span>
            Join Friend
        </button>
    </div>
</header>
