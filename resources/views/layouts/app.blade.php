<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LinkUP') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script> <!-- Fallback/Quick prototyping -->
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
            .font-fill { font-variation-settings: 'FILL' 1; }
            .glass-header { background-color: rgba(11, 14, 20, 0.8); backdrop-filter: blur(12px); }
            [x-cloak] { display: none !important; }
        </style>

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="bg-background-light text-slate-900 antialiased">
        <x-banner />

        <div class="max-w-[1440px] mx-auto flex min-h-screen">
            <!-- Left Sidebar Navigation -->
            @include('navigation-menu')

            <!-- Main Content Area -->
            <main class="flex-1  xl:ml-64 xl:mr-[350px] min-h-screen border-r border-slate-200">
                {{ $slot }}
            </main>

            <!-- Right Sidebar (Trends & Suggested) -->
            <aside class="hidden xl:block max-w-[350px] fixed right-0 h-screen p-6 overflow-y-auto flex flex-col gap-6">
                <!-- Trending Topics -->
                <section class="bg-slate-100 rounded-2xl p-5 border border-slate-200">
                    <h2 class="text-lg font-bold mb-4">Trending Topics</h2>
                    <div class="flex flex-col gap-5">
                        <div class="cursor-pointer group">
                            <p class="text-xs text-slate-500 flex items-center justify-between">Technology · Trending <span class="material-symbols-outlined text-sm">more_horiz</span></p>
                            <p class="font-bold group-hover:text-primary transition-colors">#UnifiedFeed</p>
                            <p class="text-xs text-slate-500">12.4k posts</p>
                        </div>
                        <div class="cursor-pointer group">
                            <p class="text-xs text-slate-500 flex items-center justify-between">Design · Trending <span class="material-symbols-outlined text-sm">more_horiz</span></p>
                            <p class="font-bold group-hover:text-primary transition-colors">UIUX Trends 2024</p>
                            <p class="text-xs text-slate-500">8,521 posts</p>
                        </div>
                        <div class="cursor-pointer group">
                             <p class="text-xs text-slate-500 flex items-center justify-between">World News · Live <span class="material-symbols-outlined text-sm">more_horiz</span></p>
                             <p class="font-bold group-hover:text-primary transition-colors">Mars Rover Discovery</p>
                             <p class="text-xs text-slate-500">45.2k posts</p>
                        </div>
                    </div>
                </section>

                <!-- Suggested for You -->
                <section class="bg-slate-100 rounded-2xl p-5 border border-slate-200">
                    <h2 class="text-lg font-bold mb-4">Who to follow</h2>
                    <!-- We should fetch real users here eventually, but for layout we can keep static or include a component -->
                    @if(isset($suggestedUsers))
                        <div class="flex flex-col gap-4">
                            @foreach($suggestedUsers as $suggestedUser)
                             <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2 min-w-0">
                                    <a href="{{ route('user.show', $suggestedUser->id) }}">
                                        @if ($suggestedUser->profile_photo_url)
                                            <div class="size-10 rounded-full bg-cover bg-center shrink-0" style='background-image: url("{{ $suggestedUser->profile_photo_url }}")'></div>
                                        @else
                                            <div class="size-10 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-500">{{ substr($suggestedUser->name, 0, 1) }}</div>
                                        @endif
                                    </a>
                                    <div class="min-w-0">
                                        <a href="{{ route('user.show', $suggestedUser->id) }}" class="text-sm font-bold truncate block hover:underline">{{ $suggestedUser->name }}</a>
                                        <p class="text-xs text-slate-500 truncate">@ {{ strtolower(str_replace(' ', '', $suggestedUser->name)) }}</p>
                                    </div>
                                </div>
                                <form action="{{ route('friendships.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="addressee_id" value="{{ $suggestedUser->id }}">
                                    <button class="bg-slate-900 text-white text-xs font-bold px-4 py-2 rounded-full hover:opacity-80 transition-opacity">Follow</button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </section>

                <!-- Footer Links -->
                <footer class="px-5 text-xs text-slate-500 flex flex-wrap gap-x-4 gap-y-2">
                    <a class="hover:underline" href="#">Terms of Service</a>
                    <a class="hover:underline" href="#">Privacy Policy</a>
                    <a class="hover:underline" href="#">Cookie Policy</a>
                    <a class="hover:underline" href="#">Accessibility</a>
                    <span>© 2026 LinkUP</span>
                </footer>
            </aside>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
