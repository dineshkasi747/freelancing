@php use App\Models\Setting; @endphp
@extends('layouts.app')
@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-orange-600 to-red-600 text-white py-20 px-4">
    <div class="max-w-4xl mx-auto text-center">
        <h1 class="text-5xl font-bold mb-4">🍱 {{ $shopName }}</h1>
        <p class="text-xl text-orange-100 mb-8">Fresh, Homemade, Delicious — Every Single Day</p>
        <p class="text-orange-200 mb-10">{{ $timings }}</p>
        <div class="flex justify-center gap-4 flex-wrap">
            <a href="{{ route('menu') }}" class="bg-white text-orange-600 font-bold px-8 py-3 rounded-full text-lg hover:bg-orange-50 transition">
                View Full Menu
            </a>
            <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number') }}" target="_blank"
               class="bg-green-500 text-white font-bold px-8 py-3 rounded-full text-lg hover:bg-green-600 transition">
                💬 Order on WhatsApp
            </a>
        </div>
    </div>
</section>

{{-- Today's Specials --}}
<section class="max-w-6xl mx-auto px-4 py-16">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">⭐ Today's Specials</h2>
    <p class="text-center text-gray-500 mb-10">Hand-picked favourites from our kitchen</p>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($specials as $item)
        <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-shadow">
            <div class="bg-gradient-to-br from-orange-100 to-orange-200 h-40 flex items-center justify-center text-6xl">
                {{ $item->is_veg ? '🟢' : '🔴' }}
            </div>
            <div class="p-4">
                <div class="flex justify-between items-start mb-1">
                    <h3 class="font-bold text-gray-800 text-lg">{{ $item->name }}</h3>
                    <span class="text-orange-600 font-bold text-lg">₹{{ $item->price }}</span>
                </div>
                <p class="text-gray-500 text-sm mb-4">{{ $item->description }}</p>
                <button onclick="addToCart({{ $item->id }}, '{{ $item->name }}')"
                    class="w-full bg-orange-600 text-white py-2 rounded-xl font-medium hover:bg-orange-700 transition add-btn"
                    data-id="{{ $item->id }}">
                    + Add to Cart
                </button>
            </div>
        </div>
        @endforeach
    </div>
    <div class="text-center mt-10">
        <a href="{{ route('menu') }}" class="bg-orange-600 text-white px-8 py-3 rounded-full font-bold text-lg hover:bg-orange-700 transition">
            See Full Menu →
        </a>
    </div>
</section>

{{-- Why choose us --}}
<section class="bg-white py-16 px-4">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-10">Why Customers Love Us</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
            <div class="p-6 rounded-2xl bg-orange-50">
                <div class="text-5xl mb-4">🏠</div>
                <h3 class="font-bold text-gray-800 mb-2">Home-Style Cooking</h3>
                <p class="text-gray-500 text-sm">Made fresh daily with traditional recipes passed down generations</p>
            </div>
            <div class="p-6 rounded-2xl bg-orange-50">
                <div class="text-5xl mb-4">⚡</div>
                <h3 class="font-bold text-gray-800 mb-2">Quick Service</h3>
                <p class="text-gray-500 text-sm">Fast delivery and dine-in service — we value your time</p>
            </div>
            <div class="p-6 rounded-2xl bg-orange-50">
                <div class="text-5xl mb-4">💰</div>
                <h3 class="font-bold text-gray-800 mb-2">Affordable Prices</h3>
                <p class="text-gray-500 text-sm">Quality food at prices that won't burn a hole in your pocket</p>
            </div>
        </div>
    </div>
</section>

@endsection
@push('scripts')
<script>
function addToCart(id, name) {
    fetch('/cart/add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ menu_item_id: id, quantity: 1 })
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('cart-count').textContent = data.count;
        const btn = document.querySelector(`[data-id="${id}"]`);
        btn.textContent = '✅ Added!';
        btn.classList.replace('bg-orange-600', 'bg-green-600');
        setTimeout(() => {
            btn.textContent = '+ Add to Cart';
            btn.classList.replace('bg-green-600', 'bg-orange-600');
        }, 1500);
    });
}
</script>
@endpush