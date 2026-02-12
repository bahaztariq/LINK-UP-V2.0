<aside class="w-20 xl:w-64 fixed h-screen border-r border-slate-200 flex flex-col p-3 xl:p-6 gap-8 overflow-y-auto z-50 transition-all duration-300">
    <div class="flex items-center justify-center xl:justify-start gap-3 px-2">
        <div class="bg-primary size-10 rounded-xl flex items-center justify-center text-white shrink-0">
            <span class="material-symbols-outlined text-2xl">grid_view</span>
        </div>
        <h1 class="text-xl font-bold tracking-tight hidden xl:block text-slate-900">LinkUP</h1>
    </div>

    <nav class="flex flex-col gap-2 flex-1">
        @php
            $navItems = [
                ['route' => 'dashboard', 'label' => 'Home', 'icon' => 'home'],
                ['route' => 'explore', 'label' => 'Explore', 'icon' => 'explore'],
                ['route' => 'friendships.index', 'label' => 'Friends', 'icon' => 'group'],
                ['route' => 'notifications', 'label' => 'Notifications', 'icon' => 'notifications'],
                ['route' => 'messages', 'label' => 'Messages', 'icon' => 'mail'],
                ['route' => 'reels', 'label' => 'Reels', 'icon' => 'movie'],
            ];
        @endphp

        @foreach($navItems as $item)
            @php
                $isActive = request()->routeIs($item['route']);
                $activeClass = 'bg-primary/10 text-primary font-semibold';
                $inactiveClass = 'hover:bg-slate-100 text-slate-500';
            @endphp
       <a class="flex items-center gap-4 px-3 xl:px-4 py-3 rounded-xl transition-colors justify-center xl:justify-start {{ $isActive ? $activeClass : $inactiveClass }}" 
   href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}">

    <div class="relative">

        <span class="material-symbols-outlined {{ $isActive ? 'font-fill' : '' }}">
            {{ $item['icon'] }}
        </span>

        @if($item['label'] == 'Notifications' && auth()->user()->unreadNotifications()->exists())
            <span class="absolute -top-1 -right-1 h-2.5 w-2.5 bg-red-500 rounded-full"></span>
        @endif

    </div>

    <span class="hidden xl:inline text-slate-900">
        {{ $item['label'] }}
    </span>

</a>

        @endforeach

        <!-- Profile Link -->
        @php
            $isProfileActive = request()->routeIs('user.show') && request()->route('user')->id == Auth::id();
             $activeClass = 'bg-primary/10 text-primary font-semibold';
             $inactiveClass = 'hover:bg-slate-100 text-slate-500';
        @endphp
        <a class="flex items-center gap-4 px-3 xl:px-4 py-3 rounded-xl transition-colors justify-center xl:justify-start {{ $isProfileActive ? $activeClass : $inactiveClass }}" 
           href="{{ route('user.show', Auth::user()) }}">
            <span class="material-symbols-outlined {{ $isProfileActive ? 'font-fill' : '' }}">person</span>
            <span class="hidden xl:inline text-slate-900">Profile</span>
        </a>
    </nav>

    <div class="mt-auto flex flex-col gap-4">
        <button class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-3 rounded-xl shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-2 group">
            <span class="material-symbols-outlined">edit</span>
            <span class="hidden xl:inline">Post</span>
        </button>
        
        <!-- User Dropdown Trigger -->
        <div class="relative" x-data="{ open: false }">
            <div @click="open = !open" class="flex items-center gap-3 p-2 rounded-xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition-colors justify-center xl:justify-start">
                 @if (Laravel\Jetstream\Jetstream::managesProfilePhotos() && Auth::user()->profile_photo_url)
                    <div class="size-10 rounded-full bg-cover bg-center shrink-0" style='background-image: url("{{ Auth::user()->profile_photo_url }}")'></div>
                 @else
                    <div class="size-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold shrink-0">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                 @endif
                <div class="flex-1 min-w-0 hidden xl:block">
                    <p class="text-sm font-bold truncate text-slate-900">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500 truncate">@ {{ strtolower(str_replace(' ', '', Auth::user()->name)) }}</p>
                </div>
                <span class="material-symbols-outlined text-slate-400 hidden xl:block">more_horiz</span>
            </div>

            <!-- Dropdown Menu -->
            <div x-show="open" @click.away="open = false" 
                 class="absolute bottom-full left-0 w-60 mb-2 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-50"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95" 
                 x-cloak>
                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Settings</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-slate-50">Log Out</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Mobile Bottom Nav Placeholder (Visible only on small screens < md if needed, though app layout usually handles sidebar visibility) -->
    <!-- Note: Original ui.php desing is sidebar-first. For mobile, we might need a bottom bar. 
         Let's stick to sidebar for now as per ui.php which seems desktop focused, but adding responsive classes used above (w-20 xl:w-64) helps. -->
</aside>

<!-- Mobile Bottom Navigation (Visible only on small screens) -->
<div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 z-50 flex justify-around items-center py-3 px-2 pb-safe">
    @foreach($navItems as $item)
    <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}" class="p-2 {{ request()->routeIs($item['route']) ? 'text-primary' : 'text-slate-500' }}">
        <span class="material-symbols-outlined text-[28px] {{ request()->routeIs($item['route']) ? 'font-fill' : '' }}">{{ $item['icon'] }}</span>
    </a>
    @endforeach
    <a href="{{ route('user.show', Auth::user()) }}" class="p-2 {{ request()->routeIs('user.show') && request()->route('user')->id == Auth::id() ? 'text-primary' : 'text-slate-500' }}">
         @if (Laravel\Jetstream\Jetstream::managesProfilePhotos() && Auth::user()->profile_photo_url)
            <img class="h-6 w-6 rounded-full object-cover border border-slate-200 {{ request()->routeIs('user.show') ? 'ring-2 ring-primary' : '' }}" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
         @else
            <span class="material-symbols-outlined text-[28px] {{ request()->routeIs('user.show') ? 'font-fill' : '' }}">person</span>
         @endif
    </a>
</div>
