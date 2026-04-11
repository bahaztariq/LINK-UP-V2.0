<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LinkUP | The Future of Connection</title>
    
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #050505;
        }
        .mesh-gradient {
            background-color: #050505;
            background-image: 
                radial-gradient(at 0% 0%, hsla(220,100%,15%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(260,100%,10%,1) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(220,100%,15%,1) 0, transparent 50%);
        }
        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .text-gradient {
            background: linear-gradient(to right, #ffffff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-image-mask {
            mask-image: radial-gradient(circle, black 40%, transparent 80%);
            -webkit-mask-image: radial-gradient(circle, black 40%, transparent 80%);
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="text-white selection:bg-primary selection:text-white mesh-gradient min-h-screen">

    <!-- Header -->
    <header class="fixed top-0 w-full z-50 px-6 py-4">
        <nav class="max-w-7xl mx-auto flex items-center justify-between glass rounded-2xl px-6 py-3">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center shadow-lg shadow-primary/20">
                    <span class="text-white font-bold">L</span>
                </div>
                <span class="text-xl font-bold tracking-tight">LinkUP</span>
            </div>
            
            <div class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-400">
                <a href="#" class="hover:text-white transition-colors">Features</a>
                <a href="#" class="hover:text-white transition-colors">Security</a>
                <a href="#" class="hover:text-white transition-colors">About</a>
            </div>

            <div class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2 bg-primary text-white text-sm font-bold rounded-xl shadow-lg shadow-primary/20 hover:scale-105 transition-all">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold hover:text-primary transition-colors">Sign In</a>
                        <a href="{{ route('register') }}" class="px-5 py-2 bg-white text-black text-sm font-bold rounded-xl hover:bg-slate-200 transition-all">Join Free</a>
                    @endauth
                @endif
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <main class="relative pt-48 pb-24 px-6 overflow-hidden">
        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 items-center">
            
            <!-- Left: Content -->
            <div class="relative z-10 space-y-8 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/20 border border-primary/30 text-xs font-bold tracking-widest text-primary uppercase">
                    <span class="flex h-2 w-2 rounded-full bg-primary animate-pulse"></span>
                    v2.0 is now live
                </div>

                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight leading-tight">
                    Beyond <span class="text-gradient">Social.</span> <br>
                    Pure <span class="text-primary">Connection.</span>
                </h1>

                <p class="text-lg md:text-xl text-slate-400 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                    LinkUP is a titanium-grade social environment built for privacy, speed, and meaningful interactions. Share your journey without noise.
                </p>

                <div class="flex flex-col sm:flex-row items-center gap-4 pt-4 justify-center lg:justify-start">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-primary text-white font-bold rounded-2xl shadow-xl shadow-primary/30 hover:scale-105 hover:bg-primary/90 transition-all text-center">
                        Start Building Connections
                    </a>
                    <a href="#features" class="w-full sm:w-auto px-8 py-4 glass text-white font-bold rounded-2xl hover:bg-white/5 transition-all text-center">
                        Explore Features
                    </a>
                </div>

                <div class="flex items-center gap-6 pt-8 justify-center lg:justify-start opacity-50 grayscale hover:grayscale-0 transition-all">
                    <div class="flex flex-col">
                        <span class="text-2xl font-bold">100k+</span>
                        <span class="text-[10px] uppercase tracking-widest">Early Adopters</span>
                    </div>
                    <div class="h-8 w-[1px] bg-white/20"></div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-bold">24/7</span>
                        <span class="text-[10px] uppercase tracking-widest">Uptime Proof</span>
                    </div>
                </div>
            </div>

            <!-- Right: Visual Asset -->
            <div class="relative lg:-mr-24 pointer-events-none">
                <div class="relative z-10 animate-float">
                    <img src="{{ asset('images/hero/hero-abstract.png') }}" alt="Digital Connectivity" class="w-full max-w-2xl mx-auto rounded-[3rem] shadow-2xl shadow-primary/20 hero-image-mask">
                </div>
                <!-- Blurred Glow -->
                <div class="absolute inset-0 bg-primary/20 filter blur-[100px] rounded-full z-0 translate-y-12 scale-90"></div>
            </div>

        </div>
    </main>

    <!-- Features Section -->
    <section id="features" class="py-24 px-6 border-t border-white/5">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 space-y-4">
                <h2 class="text-3xl md:text-4xl font-bold">Crafted for Excellence</h2>
                <p class="text-slate-500 max-w-md mx-auto">LinkUP isn't just another platform. It's an engine for your social life.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="glass p-8 rounded-[2rem] space-y-6 hover:-translate-y-2 transition-transform cursor-pointer group">
                    <div class="w-12 h-12 bg-primary/20 rounded-2xl flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-2">Privacy First</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">Your data belongs to you. End-to-end controls for every piece of content you share.</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="glass p-8 rounded-[2rem] space-y-6 hover:-translate-y-2 transition-transform cursor-pointer group">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-2xl flex items-center justify-center text-blue-400 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-2">Lightning Fast</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">Built with the latest tech stack for zero-latency interactions and real-time feeds.</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="glass p-8 rounded-[2rem] space-y-6 hover:-translate-y-2 transition-transform cursor-pointer group">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-2xl flex items-center justify-center text-purple-400 group-hover:bg-purple-500 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-2">Community Driven</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">Connect with groups, join conversations, and evolve alongside your peers.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 px-6 border-t border-white/5">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6 text-sm text-slate-500">
            <div class="flex items-center gap-2">
                <span class="font-bold text-white">LinkUP 2.0</span>
                <span>© 2026</span>
            </div>
            <div class="flex items-center gap-8">
                <a href="#" class="hover:text-white transition-colors">Terms</a>
                <a href="#" class="hover:text-white transition-colors">Privacy</a>
                <a href="#" class="hover:text-white transition-colors">Enterprise</a>
            </div>
        </div>
    </footer>

</body>
</html>
