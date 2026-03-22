@php 
use App\Models\Setting;
$foodImages = [
    'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=400&q=80',
    'https://images.unsplash.com/photo-1567337710282-00832b415979?w=400&q=80',
    'https://images.unsplash.com/photo-1630383249896-424e482df921?w=400&q=80',
    'https://images.unsplash.com/photo-1601050690117-94f5f7a1f55e?w=400&q=80',
    'https://images.unsplash.com/photo-1546549032-9571cd6b27df?w=400&q=80',
    'https://images.unsplash.com/photo-1596797038530-2c107229654b?w=400&q=80',
    'https://images.unsplash.com/photo-1606491956689-2ea866880c84?w=400&q=80',
    'https://images.unsplash.com/photo-1574653853027-5382a3d23a15?w=400&q=80',
];
@endphp
@extends('layouts.app')
@section('content')

{{-- Page Header --}}
<div class="bg-white border-b border-gray-100 py-12">
    <div class="max-w-6xl mx-auto px-6 text-center">
        <p class="text-orange-600 text-sm font-medium uppercase tracking-widest mb-3">Fresh Daily</p>
        <h1 class="font-playfair text-4xl font-bold text-gray-900 mb-4">Our Menu</h1>
        <div class="w-16 h-1 bg-orange-600 mx-auto rounded mb-4"></div>
        <p class="text-gray-500 text-sm">Homemade • Fresh • Delicious — Order online or scan QR at your table</p>
    </div>
</div>

<div class="max-w-6xl mx-auto px-6 py-10">

    {{-- Veg / Non-veg filter --}}
    <div class="flex flex-wrap justify-center gap-3 mb-8">
        <button onclick="filterMenu('all')" id="filter-all"
            class="filter-btn px-6 py-2 rounded-full text-sm font-medium bg-orange-600 text-white border-2 border-orange-600">
            All Items
        </button>
        <button onclick="filterMenu('veg')" id="filter-veg"
            class="filter-btn px-6 py-2 rounded-full text-sm font-medium border-2 border-green-600 text-green-600 hover:bg-green-600 hover:text-white transition">
            🟢 Veg Only
        </button>
        <button onclick="filterMenu('nonveg')" id="filter-nonveg"
            class="filter-btn px-6 py-2 rounded-full text-sm font-medium border-2 border-red-500 text-red-500 hover:bg-red-500 hover:text-white transition">
            🔴 Non-Veg
        </button>
    </div>

    {{-- Category quick-jump tabs --}}
    <div class="flex gap-3 overflow-x-auto pb-3 mb-10">
        @foreach($categories as $cat)
            @if($cat->menuItems->count() > 0)
            <button onclick="scrollToCategory('cat-{{ $cat->id }}')"
                class="whitespace-nowrap px-5 py-2 rounded-full bg-white shadow-sm border border-gray-200 text-gray-700 font-medium text-sm hover:bg-orange-600 hover:text-white hover:border-orange-600 transition flex-shrink-0">
                {{ $cat->icon }} {{ $cat->name }}
            </button>
            @endif
        @endforeach
    </div>

    {{-- Menu categories --}}
    @forelse($categories as $catIndex => $cat)
        @if($cat->menuItems->count() > 0)
        <div id="cat-{{ $cat->id }}" class="mb-16">

            {{-- Category heading --}}
            <div class="flex items-center gap-3 mb-8">
                <span class="text-3xl">{{ $cat->icon }}</span>
                <div>
                    <h2 class="font-playfair text-2xl font-bold text-gray-900">{{ $cat->name }}</h2>
                    <p class="text-gray-400 text-sm">{{ $cat->menuItems->count() }} items available</p>
                </div>
                <div class="flex-1 h-px bg-gray-100 ml-4"></div>
            </div>

            {{-- Items grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($cat->menuItems as $itemIndex => $item)
                <div class="menu-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 group"
                     data-veg="{{ $item->is_veg ? 'veg' : 'nonveg' }}">

                    {{-- Image --}}
                    <div class="relative h-48 overflow-hidden bg-orange-50">
                        @if($item->image)
                            <img src="{{ asset('storage/'.$item->image) }}"
                                 alt="{{ $item->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <img src="{{ $foodImages[($catIndex * 4 + $itemIndex) % count($foodImages)] }}"
                                 alt="{{ $item->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @endif

                        {{-- Veg/NonVeg indicator --}}
                        <div class="absolute top-3 left-3">
                            <div class="w-5 h-5 border-2 {{ $item->is_veg ? 'border-green-600' : 'border-red-600' }} bg-white rounded-sm flex items-center justify-center shadow-sm">
                                <div class="w-2.5 h-2.5 rounded-full {{ $item->is_veg ? 'bg-green-600' : 'bg-red-600' }}"></div>
                            </div>
                        </div>

                        {{-- Special badge --}}
                        @if($item->is_special)
                        <div class="absolute top-3 right-3 bg-orange-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow">
                            ⭐ Special
                        </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="p-5">
                        <div class="mb-3">
                            <h3 class="font-playfair font-bold text-gray-900 text-base">{{ $item->name }}</h3>
                            @if($item->description)
                            <p class="text-gray-400 text-sm mt-1 line-clamp-2">{{ $item->description }}</p>
                            @endif
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-orange-600 font-bold text-xl">₹{{ number_format($item->price, 0) }}</span>
                            <div class="flex items-center gap-2">
                                {{-- Qty control --}}
                                <div class="flex items-center border border-gray-200 rounded-full overflow-hidden">
                                    <button onclick="changeQty({{ $item->id }}, -1)"
                                        class="w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-100 text-sm font-bold transition">−</button>
                                    <span id="qty-{{ $item->id }}"
                                        class="w-6 text-center text-sm font-medium text-gray-700">1</span>
                                    <button onclick="changeQty({{ $item->id }}, 1)"
                                        class="w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-100 text-sm font-bold transition">+</button>
                                </div>
                                {{-- Add button --}}
                                <button onclick="addToCart({{ $item->id }}, '{{ addslashes($item->name) }}')"
                                    data-id="{{ $item->id }}"
                                    class="add-btn border border-orange-600 text-orange-600 px-4 py-1.5 rounded-full text-sm font-medium hover:bg-orange-600 hover:text-white transition">
                                    Add
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    @empty
        <div class="text-center py-20">
            <div class="text-6xl mb-4">🍽️</div>
            <h2 class="font-playfair text-2xl font-bold text-gray-600 mb-2">Menu coming soon</h2>
            <p class="text-gray-400">We're preparing something delicious!</p>
        </div>
    @endforelse

</div>

{{-- Floating cart bar --}}
<div id="cart-bar"
    class="fixed bottom-0 left-0 right-0 bg-gray-900 text-white py-4 px-6 transform translate-y-full transition-transform duration-300 z-50 shadow-2xl">
    <div class="max-w-6xl mx-auto flex justify-between items-center">
        <div>
            <span id="cart-bar-count" class="font-bold text-lg">0 items</span>
            <span class="text-gray-400 ml-2 text-sm">in your cart</span>
        </div>
        <a href="{{ route('cart.index') }}"
           class="bg-orange-600 text-white font-bold px-6 py-2.5 rounded-full hover:bg-orange-700 transition text-sm">
            View Cart →
        </a>
    </div>
</div>

@endsection

@push('scripts')
<script>
let quantities = {};

function changeQty(id, delta) {
    if (!quantities[id]) quantities[id] = 1;
    quantities[id] = Math.max(1, quantities[id] + delta);
    document.getElementById('qty-' + id).textContent = quantities[id];
}

function addToCart(id, name) {
    const qty = quantities[id] || 1;
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ menu_item_id: id, quantity: qty })
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('cart-count').textContent = data.count;
        updateCartBar(data.count);
        const btn = document.querySelector(`[data-id="${id}"]`);
        btn.textContent = '✓ Added!';
        btn.classList.add('bg-green-600', 'text-white', 'border-green-600');
        btn.classList.remove('text-orange-600', 'border-orange-600');
        setTimeout(() => {
            btn.textContent = 'Add';
            btn.classList.remove('bg-green-600', 'text-white', 'border-green-600');
            btn.classList.add('text-orange-600', 'border-orange-600');
        }, 1500);
    });
}

function updateCartBar(count) {
    const bar = document.getElementById('cart-bar');
    document.getElementById('cart-bar-count').textContent = count + ' item' + (count !== 1 ? 's' : '');
    if (count > 0) {
        bar.classList.remove('translate-y-full');
    } else {
        bar.classList.add('translate-y-full');
    }
}

function filterMenu(type) {
    document.querySelectorAll('.menu-card').forEach(card => {
        card.style.display = (type === 'all' || card.dataset.veg === type) ? '' : 'none';
    });
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.className = btn.className
            .replace('bg-orange-600 text-white border-orange-600', '')
            .replace('bg-green-600 text-white border-green-600', '')
            .replace('bg-red-500 text-white border-red-500', '');
    });
    const colorMap = {
        all: 'bg-orange-600 text-white border-orange-600',
        veg: 'bg-green-600 text-white border-green-600',
        nonveg: 'bg-red-500 text-white border-red-500'
    };
    const activeBtn = document.getElementById('filter-' + type);
    colorMap[type].split(' ').forEach(cls => activeBtn.classList.add(cls));
}

function scrollToCategory(id) {
    const el = document.getElementById(id);
    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Init cart bar
updateCartBar({{ collect(session('cart', []))->sum('quantity') }});
</script>
@endpush