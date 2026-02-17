<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LinkUP | Connect, Share, Evolve</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .hero-gradient {
            background: radial-gradient(circle at top left, rgba(19, 91, 236, 0.08), transparent 40%),
                        radial-gradient(circle at bottom right, rgba(19, 91, 236, 0.05), transparent 40%);
        }
        .blob {
            position: absolute;
            width: 500px;
            height: 500px;
            background: linear-gradient(135deg, rgba(19, 91, 236, 0.1) 0%, rgba(19, 91, 236, 0.05) 100%);
            filter: blur(80px);
            border-radius: 50%;
            z-index: -1;
        }
    </style>
</head>
<body class="bg-white text-slate-900 hero-gradient min-h-screen flex flex-col items-center">
    
    <!-- Animated Blobs -->
    <div class="blob top-[-10%] left-[-10%]"></div>
    <div class="blob bottom-[-10%] right-[-10%]"></div>

    <main class="relative z-10 max-w-5xl mx-auto px-6 text-center pt-32 pb-16">
        <!-- Badge -->
        <div class="inline-flex items-center px-4 py-2 rounded-full bg-primary/10 border border-primary/20 mb-10 animate-fade-in shadow-sm">
            <span class="text-primary text-sm font-bold tracking-wide flex items-center">
                <span class="w-2 h-2 rounded-full bg-primary me-2 animate-pulse"></span>
                THE NEXT GENERATION OF SOCIAL NETWORKING
            </span>
        </div>

        <!-- Hero Title -->
        <h1 class="text-6xl md:text-8xl font-extrabold tracking-tight text-slate-900 mb-6 leading-tight">
            Connect without <br>
            <span class="text-primary bg-clip-text">boundaries.</span>
        </h1>

        <!-- Subtitle -->
        <p class="text-lg md:text-xl text-slate-500 max-w-2xl mx-auto mb-12 leading-relaxed">
            LinkUP is the modern space to share your journey, connect with the world, and build meaningful relationships in a clean, private, and powerful environment.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto px-10 py-4 bg-primary text-white font-bold rounded-2xl shadow-xl shadow-primary/20 hover:scale-105 transition-all duration-300">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-10 py-4 bg-primary text-white font-bold rounded-2xl shadow-xl shadow-primary/20 hover:scale-105 transition-all duration-300">
                        Start Your Journey
                    </a>
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-10 py-4 bg-white text-slate-700 font-bold border border-slate-200 rounded-2xl hover:bg-slate-50 transition-all duration-300">
                        Welcome Back
                    </a>
                @endauth
            @endif
        </div>

        <!-- Social Proof/Stats -->
        <div class="pt-8 border-t border-slate-100 flex flex-wrap justify-center gap-12 text-slate-400">
            <div class="flex flex-col items-center">
                <span class="text-2xl font-bold text-slate-800 tracking-tighter">Fast</span>
                <span class="text-xs uppercase tracking-widest font-bold">Performance</span>
            </div>
            <div class="flex flex-col items-center border-x border-slate-100 px-12">
                <span class="text-2xl font-bold text-slate-800 tracking-tighter">Secure</span>
                <span class="text-xs uppercase tracking-widest font-bold">Authentication</span>
            </div>
            <div class="flex flex-col items-center">
                <span class="text-2xl font-bold text-slate-800 tracking-tighter">Modern</span>
                <span class="text-xs uppercase tracking-widest font-bold">Architecture</span>
            </div>
        </div>
    </main>

    <!-- App Preview/Mockup Placeholder -->
    <div class="mt-20 relative w-full max-w-4xl opacity-50 select-none pointer-events-none">
        <div class="aspect-video bg-slate-100 rounded-t-[3rem] border-x border-t border-slate-200 shadow-2xl overflow-hidden">
             <div class="w-full h-8 bg-slate-200 flex items-center px-4 gap-2">
                 <div class="w-2 h-2 rounded-full bg-slate-300"></div>
                 <div class="w-2 h-2 rounded-full bg-slate-300"></div>
                 <div class="w-2 h-2 rounded-full bg-slate-300"></div>
             </div>
             <div class="p-8 space-y-4">
                 <div class="w-1/3 h-6 bg-slate-200 rounded-lg"></div>
                 <div class="grid grid-cols-3 gap-4">
                     <div class="h-40 bg-slate-201 rounded-2xl bg-slate-200/50"></div>
                     <div class="h-40 bg-slate-201 rounded-2xl bg-slate-200/50"></div>
                     <div class="h-40 bg-slate-201 rounded-2xl bg-slate-200/50"></div>
                 </div>
             </div>
        </div>
    </div>

</body>
</html>
