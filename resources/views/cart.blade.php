@php use App\Models\Setting; @endphp
@extends('layouts.app')
@section('content')

<div class="max-w-3xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">🛒 Your Cart</h1>

    @if(empty($cart))
    <div class="text-center py-20">
        <div class="text-8xl mb-6">🛒</div>
        <h2 class="text-2xl font-bold text-gray-600 mb-4">Your cart is empty</h2>
        <a href="{{ route('menu') }}" class="bg-orange-600 text-white px-8 py-3 rounded-full font-bold hover:bg-orange-700">
            Browse Menu
        </a>
    </div>
    @else
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        @foreach($cart as $key => $item)
        <div class="flex items-center gap-4 p-4 border-b border-gray-50 last:border-0" id="cart-row-{{ $key }}">
            <div class="w-8 h-8 border-2 {{ $item['is_veg'] ? 'border-green-600' : 'border-red-600' }} bg-white rounded-sm flex items-center justify-center flex-shrink-0">
                <div class="w-4 h-4 rounded-full {{ $item['is_veg'] ? 'bg-green-600' : 'bg-red-600' }}"></div>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-gray-800">{{ $item['name'] }}</h3>
                <p class="text-orange-600 font-medium">₹{{ $item['price'] }}</p>
            </div>
            <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
                <button onclick="updateCart({{ $key }}, {{ $item['quantity'] - 1 }})"
                    class="px-3 py-2 text-gray-600 hover:bg-gray-100 font-bold">−</button>
                <span class="px-3 py-2 font-medium" id="qty-{{ $key }}">{{ $item['quantity'] }}</span>
                <button onclick="updateCart({{ $key }}, {{ $item['quantity'] + 1 }})"
                    class="px-3 py-2 text-gray-600 hover:bg-gray-100 font-bold">+</button>
            </div>
            <div class="text-right min-w-16">
                <p class="font-bold text-gray-800" id="subtotal-{{ $key }}">₹{{ $item['price'] * $item['quantity'] }}</p>
            </div>
            <button onclick="removeItem({{ $key }})" class="text-red-400 hover:text-red-600 ml-2">✕</button>
        </div>
        @endforeach
    </div>

    {{-- Total --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex justify-between items-center text-lg">
            <span class="text-gray-600">Subtotal</span>
            <span class="font-bold text-gray-800" id="cart-total">₹{{ $total }}</span>
        </div>
        <div class="flex justify-between items-center text-sm text-gray-400 mt-2">
            <span>Taxes & charges</span>
            <span>Included</span>
        </div>
        <hr class="my-4">
        <div class="flex justify-between items-center text-xl font-bold">
            <span>Total</span>
            <span class="text-orange-600" id="cart-total-final">₹{{ $total }}</span>
        </div>
    </div>

    <div class="flex gap-4">
        <a href="{{ route('menu') }}" class="flex-1 text-center border-2 border-orange-600 text-orange-600 py-3 rounded-xl font-bold hover:bg-orange-50">
            ← Add More
        </a>
        <a href="{{ route('checkout') }}" class="flex-1 text-center bg-orange-600 text-white py-3 rounded-xl font-bold hover:bg-orange-700">
            Proceed to Checkout →
        </a>
    </div>
    @endif
</div>
@endsection
@push('scripts')
<script>
function updateCart(id, qty) {
    fetch('/cart/update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ id: id, quantity: qty })
    }).then(r => r.json()).then(data => {
        if (qty <= 0) {
            document.getElementById('cart-row-' + id).remove();
        } else {
            document.getElementById('qty-' + id).textContent = qty;
        }
        document.getElementById('cart-total').textContent = '₹' + data.total;
        document.getElementById('cart-total-final').textContent = '₹' + data.total;
    });
}

function removeItem(id) {
    fetch('/cart/remove', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ id: id })
    }).then(r => r.json()).then(data => {
        document.getElementById('cart-row-' + id).remove();
        document.getElementById('cart-total').textContent = '₹' + data.total;
        document.getElementById('cart-total-final').textContent = '₹' + data.total;
        document.getElementById('cart-count').textContent = 
            document.querySelectorAll('[id^="cart-row-"]').length;
    });
}
</script>
@endpush