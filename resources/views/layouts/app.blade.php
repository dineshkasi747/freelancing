@php use App\Models\Setting; @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? Setting::get('shop_name', 'Tiffin Shop') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-playfair { font-family: 'Playfair Display', serif; }
        .nav-link { position: relative; }
        .nav-link::after { content: ''; position: absolute; bottom: -2px; left: 0; width: 0; height: 2px; background: #EA580C; transition: width 0.3s; }
        .nav-link:hover::after { width: 100%; }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

{{-- Navbar --}}
<nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100 shadow-sm">
    <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <span class="text-2xl">🍱</span>
            <span class="font-playfair text-xl font-bold text-gray-900">{{ Setting::get('shop_name') }}</span>
        </a>
        <div class="hidden md:flex items-center gap-8">
            <a href="{{ route('home') }}" class="nav-link text-gray-600 hover:text-orange-600 font-medium text-sm transition {{ request()->routeIs('home') ? 'text-orange-600' : '' }}">Home</a>
            <a href="{{ route('menu') }}" class="nav-link text-gray-600 hover:text-orange-600 font-medium text-sm transition {{ request()->routeIs('menu') ? 'text-orange-600' : '' }}">Menu</a>
            <a href="{{ route('contact') }}" class="nav-link text-gray-600 hover:text-orange-600 font-medium text-sm transition {{ request()->routeIs('contact') ? 'text-orange-600' : '' }}">Contact</a>
            <a href="{{ route('cart.index') }}" class="relative bg-orange-600 text-white px-5 py-2 rounded-full text-sm font-medium hover:bg-orange-700 transition">
                🛒 Cart
                <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                    {{ collect(session('cart', []))->sum('quantity') }}
                </span>
            </a>
        </div>
        <button class="md:hidden text-gray-600" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>
    <div id="mobile-menu" class="hidden md:hidden px-6 pb-4 space-y-2 bg-white border-t border-gray-100">
        <a href="{{ route('home') }}" class="block py-2 text-gray-600 hover:text-orange-600">Home</a>
        <a href="{{ route('menu') }}" class="block py-2 text-gray-600 hover:text-orange-600">Menu</a>
        <a href="{{ route('contact') }}" class="block py-2 text-gray-600 hover:text-orange-600">Contact</a>
        <a href="{{ route('cart.index') }}" class="block py-2 text-orange-600 font-medium">Cart ({{ collect(session('cart', []))->sum('quantity') }})</a>
    </div>
</nav>

@if(session('success'))
<div id="flash" class="fixed top-20 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 flex items-center gap-2">
    ✅ {{ session('success') }}
</div>
@endif

@if(session('error'))
<div id="flash" class="fixed top-20 right-4 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg z-50">
    ❌ {{ session('error') }}
</div>
@endif

<main class="pt-20">
    @yield('content')
</main>

<footer class="bg-gray-900 text-white mt-16">
    <div class="max-w-6xl mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
            <div class="flex items-center gap-2 mb-4">
                <span class="text-2xl">🍱</span>
                <span class="font-playfair text-xl font-bold text-orange-400">{{ Setting::get('shop_name') }}</span>
            </div>
            <p class="text-gray-400 text-sm leading-relaxed">Authentic South Indian home-style cooking. Fresh daily with love and tradition.</p>
        </div>
        <div>
            <h4 class="font-playfair text-lg font-bold mb-4">Quick Links</h4>
            <div class="space-y-2">
                <a href="{{ route('home') }}" class="block text-gray-400 hover:text-orange-400 text-sm transition">Home</a>
                <a href="{{ route('menu') }}" class="block text-gray-400 hover:text-orange-400 text-sm transition">Menu</a>
                <a href="{{ route('contact') }}" class="block text-gray-400 hover:text-orange-400 text-sm transition">Contact</a>
            </div>
        </div>
        <div>
            <h4 class="font-playfair text-lg font-bold mb-4">Contact Us</h4>
            <div class="space-y-2 text-sm text-gray-400">
                <p>{{ Setting::get('shop_address') }}</p>
                <p>{{ Setting::get('shop_timings') }}</p>
                <a href="tel:{{ Setting::get('shop_phone') }}" class="text-orange-400 hover:text-orange-300 block">{{ Setting::get('shop_phone') }}</a>
                <a href="https://wa.me/{{ Setting::get('whatsapp_number') }}" target="_blank" class="text-green-400 hover:text-green-300 block">💬 WhatsApp</a>
            </div>
        </div>
    </div>
    <div class="border-t border-gray-800 py-6 text-center text-gray-500 text-sm">
        © {{ date('Y') }} {{ Setting::get('shop_name') }}. All rights reserved.
    </div>
</footer>

<script>
    setTimeout(() => { const f = document.getElementById('flash'); if(f) f.style.display='none'; }, 3000);
</script>
@stack('scripts')
</body>
</html>