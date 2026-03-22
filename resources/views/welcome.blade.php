@php use App\Models\Setting; @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ Setting::get('shop_name', 'Tiffin Shop') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap');
        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-inter { font-family: 'Inter', sans-serif; }
        html { scroll-behavior: smooth; }
        .nav-link { position: relative; }
        .nav-link::after { content: ''; position: absolute; bottom: -2px; left: 0; width: 0; height: 2px; background: #EA580C; transition: width 0.3s; }
        .nav-link:hover::after { width: 100%; }
        .hero-bg { background: linear-gradient(135deg, #fff8f0 0%, #fff 60%, #fef3e8 100%); }
        .card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .card-hover:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.12); }
    </style>
</head>
<body class="font-inter bg-white text-gray-800">

{{-- Navbar --}}
<nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-100 shadow-sm">
    <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
        <a href="/" class="flex items-center gap-2">
            <span class="text-2xl">🍱</span>
            <span class="font-playfair text-xl font-bold text-gray-900">{{ Setting::get('shop_name') }}</span>
        </a>
        <div class="hidden md:flex items-center gap-8">
            <a href="/" class="nav-link text-gray-600 hover:text-orange-600 font-medium text-sm transition">Home</a>
            <a href="{{ route('menu') }}" class="nav-link text-gray-600 hover:text-orange-600 font-medium text-sm transition">Menu</a>
            <a href="{{ route('contact') }}" class="nav-link text-gray-600 hover:text-orange-600 font-medium text-sm transition">Contact</a>
            <a href="{{ route('cart.index') }}" class="relative bg-orange-600 text-white px-5 py-2 rounded-full text-sm font-medium hover:bg-orange-700 transition">
                Cart
                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                    {{ collect(session('cart', []))->sum('quantity') }}
                </span>
            </a>
        </div>
        <!-- Mobile menu btn -->
        <button class="md:hidden text-gray-600" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>
    <!-- Mobile menu -->
    <div id="mobile-menu" class="hidden md:hidden px-6 pb-4 space-y-2 bg-white border-t border-gray-100">
        <a href="/" class="block py-2 text-gray-600 hover:text-orange-600">Home</a>
        <a href="{{ route('menu') }}" class="block py-2 text-gray-600 hover:text-orange-600">Menu</a>
        <a href="{{ route('contact') }}" class="block py-2 text-gray-600 hover:text-orange-600">Contact</a>
        <a href="{{ route('cart.index') }}" class="block py-2 text-orange-600 font-medium">Cart ({{ collect(session('cart', []))->sum('quantity') }})</a>
    </div>
</nav>

{{-- Hero --}}
<section class="hero-bg min-h-screen flex items-center pt-20">
    <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center py-20">
        <div>
            <p class="text-orange-600 font-medium text-sm uppercase tracking-widest mb-4">Since 2010 • Vijayawada</p>
            <h1 class="font-playfair text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
                Welcome to<br>
                <span class="text-orange-600">{{ Setting::get('shop_name') }}</span>
            </h1>
            <p class="text-gray-500 text-lg leading-relaxed mb-8 max-w-md">
                Authentic South Indian home-style cooking. Every dish made fresh daily with love and traditional recipes passed down generations.
            </p>
            <p class="text-gray-400 text-sm mb-10">{{ Setting::get('shop_timings') }}</p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('menu') }}"
                   class="bg-orange-600 text-white px-8 py-3 rounded-full font-semibold hover:bg-orange-700 transition shadow-lg shadow-orange-200">
                    View Our Menu
                </a>
                <a href="https://wa.me/{{ Setting::get('whatsapp_number') }}" target="_blank"
                   class="border-2 border-gray-200 text-gray-700 px-8 py-3 rounded-full font-semibold hover:border-orange-600 hover:text-orange-600 transition flex items-center gap-2">
                    <span>💬</span> WhatsApp Us
                </a>
            </div>
            <div class="flex gap-8 mt-12">
                <div>
                    <p class="font-playfair text-3xl font-bold text-gray-900">15+</p>
                    <p class="text-gray-400 text-sm">Years of taste</p>
                </div>
                <div class="w-px bg-gray-200"></div>
                <div>
                    <p class="font-playfair text-3xl font-bold text-gray-900">50+</p>
                    <p class="text-gray-400 text-sm">Menu items</p>
                </div>
                <div class="w-px bg-gray-200"></div>
                <div>
                    <p class="font-playfair text-3xl font-bold text-gray-900">500+</p>
                    <p class="text-gray-400 text-sm">Happy customers</p>
                </div>
            </div>
        </div>
        <div class="relative hidden lg:block">
            <div class="relative z-10 rounded-3xl overflow-hidden shadow-2xl h-[500px]">
                <img src="https://images.unsplash.com/photo-1567337710282-00832b415979?w=600&q=80"
                     alt="South Indian Food" class="w-full h-full object-cover">
            </div>
            <div class="absolute -bottom-6 -left-6 w-48 h-48 rounded-2xl overflow-hidden shadow-xl border-4 border-white z-20">
                <img src="https://images.unsplash.com/photo-1630383249896-424e482df921?w=300&q=80"
                     alt="Dosa" class="w-full h-full object-cover">
            </div>
            <div class="absolute -top-4 -right-4 w-32 h-32 rounded-2xl overflow-hidden shadow-xl border-4 border-white z-20">
                <img src="https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=200&q=80"
                     alt="Filter Coffee" class="w-full h-full object-cover">
            </div>
            <div class="absolute top-8 -left-8 w-16 h-16 bg-orange-100 rounded-full opacity-60"></div>
            <div class="absolute bottom-20 -right-8 w-24 h-24 bg-orange-50 rounded-full opacity-80"></div>
        </div>
    </div>
</section>

{{-- Signature Menu --}}
<section class="py-24 bg-white" id="menu">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-16">
            <p class="text-orange-600 text-sm font-medium uppercase tracking-widest mb-3">Fresh Daily</p>
            <h2 class="font-playfair text-4xl font-bold text-gray-900 mb-4">Our Signature Menu</h2>
            <div class="w-16 h-1 bg-orange-600 mx-auto rounded"></div>
        </div>
        @php
            $specials = \App\Models\MenuItem::where('is_special', true)
                ->where('is_available', true)
                ->with('category')
                ->take(6)->get();
            $foodImages = [
                'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=400&q=80',
                'https://images.unsplash.com/photo-1567337710282-00832b415979?w=400&q=80',
                'https://images.unsplash.com/photo-1630383249896-424e482df921?w=400&q=80',
                'https://images.unsplash.com/photo-1601050690117-94f5f7a1f55e?w=400&q=80',
                'https://images.unsplash.com/photo-1546549032-9571cd6b27df?w=400&q=80',
                'https://images.unsplash.com/photo-1596797038530-2c107229654b?w=400&q=80',
            ];
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($specials as $i => $item)
            <div class="card-hover bg-white rounded-2xl overflow-hidden shadow-md border border-gray-100">
                <div class="relative h-52 overflow-hidden">
                    <img src="{{ $item->image ? asset('storage/'.$item->image) : $foodImages[$i % count($foodImages)] }}"
                         alt="{{ $item->name }}"
                         class="w-full h-full object-cover hover:scale-105 transition duration-500">
                    <div class="absolute top-3 left-3">
                        <div class="w-5 h-5 border-2 {{ $item->is_veg ? 'border-green-600' : 'border-red-600' }} bg-white rounded-sm flex items-center justify-center">
                            <div class="w-2.5 h-2.5 rounded-full {{ $item->is_veg ? 'bg-green-600' : 'bg-red-600' }}"></div>
                        </div>
                    </div>
                </div>
                <div class="p-5">
                    <h3 class="font-playfair font-bold text-gray-900 text-lg mb-1">{{ $item->name }}</h3>
                    <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $item->description }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-orange-600 font-bold text-xl">₹{{ $item->price }}</span>
                        <button onclick="addToCart({{ $item->id }}, '{{ $item->name }}')"
                            data-id="{{ $item->id }}"
                            class="add-btn border border-orange-600 text-orange-600 px-4 py-2 rounded-full text-sm font-medium hover:bg-orange-600 hover:text-white transition">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-12">
            <a href="{{ route('menu') }}"
               class="inline-block border-2 border-orange-600 text-orange-600 px-10 py-3 rounded-full font-semibold hover:bg-orange-600 hover:text-white transition">
                View Full Menu →
            </a>
        </div>
    </div>
</section>

{{-- QR Section --}}
<section class="py-24 bg-gray-50">
    <div class="max-w-4xl mx-auto px-6">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="p-12 flex flex-col justify-center">
                    <p class="text-orange-600 text-sm font-medium uppercase tracking-widest mb-4">Scan & Order</p>
                    <h2 class="font-playfair text-3xl font-bold text-gray-900 mb-4">Order From Your Table</h2>
                    <p class="text-gray-500 mb-8">Scan the QR code with your phone camera to instantly browse our menu and place your order — no app needed!</p>
                    <div class="flex gap-4">
                        <a href="{{ route('menu') }}" class="bg-orange-600 text-white px-6 py-3 rounded-full font-medium hover:bg-orange-700 transition text-sm">
                            Open Menu
                        </a>
                        <button onclick="downloadQR()" class="border border-gray-200 text-gray-600 px-6 py-3 rounded-full font-medium hover:border-orange-600 hover:text-orange-600 transition text-sm">
                            Download QR
                        </button>
                    </div>
                </div>
                <div class="bg-orange-50 flex items-center justify-center p-12">
                    <div class="text-center">
                        <div id="qrcode" class="inline-block bg-white p-4 rounded-2xl shadow-md mb-3"></div>
                        <p class="text-gray-400 text-xs">Scan to view menu</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Why Us --}}
<section class="py-24 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-16">
            <p class="text-orange-600 text-sm font-medium uppercase tracking-widest mb-3">Why Choose Us</p>
            <h2 class="font-playfair text-4xl font-bold text-gray-900">The Sri Lakshmi Difference</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-8 rounded-2xl hover:bg-orange-50 transition">
                <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-6 text-3xl">🏠</div>
                <h3 class="font-playfair text-xl font-bold text-gray-900 mb-3">Home Style Cooking</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Made with traditional recipes and fresh ingredients, just like your mother's cooking.</p>
            </div>
            <div class="text-center p-8 rounded-2xl hover:bg-orange-50 transition">
                <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-6 text-3xl">⚡</div>
                <h3 class="font-playfair text-xl font-bold text-gray-900 mb-3">Quick Service</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Fast dine-in and delivery service. We respect your time as much as your appetite.</p>
            </div>
            <div class="text-center p-8 rounded-2xl hover:bg-orange-50 transition">
                <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-6 text-3xl">💰</div>
                <h3 class="font-playfair text-xl font-bold text-gray-900 mb-3">Pocket Friendly</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Premium quality food at prices that make every meal worth it. No compromise on taste.</p>
            </div>
        </div>
    </div>
</section>

{{-- Contact strip --}}
<section class="bg-gray-900 text-white py-16">
    <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
        <div>
            <p class="text-orange-400 text-sm uppercase tracking-widest mb-2">Location</p>
            <p class="text-gray-300">{{ Setting::get('shop_address') }}</p>
        </div>
        <div>
            <p class="text-orange-400 text-sm uppercase tracking-widest mb-2">Opening Hours</p>
            <p class="text-gray-300">{{ Setting::get('shop_timings') }}</p>
        </div>
        <div>
            <p class="text-orange-400 text-sm uppercase tracking-widest mb-2">Contact</p>
            <a href="tel:{{ Setting::get('shop_phone') }}" class="text-gray-300 hover:text-orange-400 transition block">{{ Setting::get('shop_phone') }}</a>
            <a href="https://wa.me/{{ Setting::get('whatsapp_number') }}" target="_blank" class="text-green-400 hover:text-green-300 transition text-sm">WhatsApp →</a>
        </div>
    </div>
</section>

{{-- Footer --}}
<footer class="bg-gray-950 text-gray-500 py-8 text-center text-sm">
    <p>© {{ date('Y') }} <span class="text-white">{{ Setting::get('shop_name') }}</span>. All rights reserved.</p>
    <p class="mt-1 text-xs">Designed with ❤️ for great food</p>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    new QRCode(document.getElementById("qrcode"), {
        text: "{{ url('/menu') }}",
        width: 160,
        height: 160,
        colorDark: "#EA580C",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });
});

function downloadQR() {
    setTimeout(() => {
        const canvas = document.querySelector('#qrcode canvas');
        if (canvas) {
            const link = document.createElement('a');
            link.download = 'menu-qr.png';
            link.href = canvas.toDataURL();
            link.click();
        }
    }, 300);
}

function addToCart(id, name) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ?
                document.querySelector('meta[name="csrf-token"]').content : '{{ csrf_token() }}'
        },
        body: JSON.stringify({ menu_item_id: id, quantity: 1 })
    })
    .then(r => r.json())
    .then(data => {
        const btn = document.querySelector(`[data-id="${id}"]`);
        const original = btn.textContent;
        btn.textContent = '✓ Added!';
        btn.classList.add('bg-green-600', 'text-white', 'border-green-600');
        btn.classList.remove('text-orange-600', 'border-orange-600');
        setTimeout(() => {
            btn.textContent = original;
            btn.classList.remove('bg-green-600', 'text-white', 'border-green-600');
            btn.classList.add('text-orange-600', 'border-orange-600');
        }, 1500);
    });
}
</script>
</body>
</html>