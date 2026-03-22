@php use App\Models\Setting; @endphp
@extends('layouts.app')
@section('content')

<div class="max-w-2xl mx-auto px-6 py-16">
    <div class="text-center mb-10">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <span class="text-4xl">✅</span>
        </div>
        <h1 class="font-playfair text-4xl font-bold text-gray-900 mb-2">Order Confirmed!</h1>
        <p class="text-gray-500">Thank you, <strong>{{ $order->customer_name }}</strong>! We're on it.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-4 bg-orange-50 border-b border-orange-100 flex justify-between items-center">
            <div>
                <p class="font-playfair font-bold text-gray-900">Order #{{ $order->id }}</p>
                <p class="text-gray-400 text-sm">{{ $order->created_at->format('d M Y, h:i A') }}</p>
            </div>
            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold uppercase">
                {{ $order->status }}
            </span>
        </div>
        <div class="p-6">
            <div class="space-y-3 mb-4">
                @foreach($order->orderItems as $oi)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">{{ $oi->quantity }}× {{ $oi->menuItem->name }}</span>
                    <span class="font-medium text-gray-800">₹{{ $oi->price * $oi->quantity }}</span>
                </div>
                @endforeach
            </div>
            <div class="border-t border-gray-100 pt-4 flex justify-between font-bold text-lg">
                <span class="font-playfair">Total</span>
                <span class="text-orange-600">₹{{ $order->total_amount }}</span>
            </div>
        </div>
        <div class="px-6 pb-6 grid grid-cols-2 gap-3 text-sm">
            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-gray-400 text-xs mb-1">Order Type</p>
                <p class="font-medium text-gray-800 capitalize">{{ str_replace('_', ' ', $order->order_type) }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-gray-400 text-xs mb-1">Payment</p>
                <p class="font-medium text-gray-800 capitalize">{{ str_replace('_', ' ', $order->payment_method ?? 'cash') }}</p>
            </div>
            @if($order->table_number)
            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-gray-400 text-xs mb-1">Table</p>
                <p class="font-medium text-gray-800">{{ $order->table_number }}</p>
            </div>
            @endif
            @if($order->delivery_address)
            <div class="bg-gray-50 rounded-xl p-3 col-span-2">
                <p class="text-gray-400 text-xs mb-1">Delivery Address</p>
                <p class="font-medium text-gray-800">{{ $order->delivery_address }}</p>
            </div>
            @endif
        </div>
    </div>

    <div class="flex gap-4 justify-center flex-wrap">
        @if($order->tracking_token)
        <a href="{{ route('order.track', $order->tracking_token) }}"
        class="bg-orange-600 text-white px-8 py-3 rounded-full font-semibold hover:bg-orange-700 transition flex items-center gap-2">
            📍 Track Order
        </a>
        @endif
        <a href="{{ route('menu') }}"
        class="border-2 border-gray-200 text-gray-700 px-8 py-3 rounded-full font-semibold hover:border-orange-600 hover:text-orange-600 transition">
            Order More
        </a>
        <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hi! I placed Order #'.$order->id.' at '.$shopName) }}"
        target="_blank"
        class="border-2 border-green-500 text-green-600 px-8 py-3 rounded-full font-semibold hover:bg-green-500 hover:text-white transition">
            💬 WhatsApp
        </a>
    </div>

    @if($order->tracking_token)
    <div class="mt-6 bg-orange-50 border border-orange-200 rounded-xl p-4 text-center">
        <p class="text-orange-700 text-sm font-medium mb-2">📍 Your order tracking link:</p>
        <p class="text-orange-600 text-xs font-mono break-all">{{ url('/track/'.$order->tracking_token) }}</p>
        <button onclick="navigator.clipboard.writeText('{{ url('/track/'.$order->tracking_token) }}').then(() => this.textContent = '✅ Copied!')"
            class="mt-2 text-xs text-orange-600 border border-orange-300 px-3 py-1 rounded-full hover:bg-orange-100 transition">
            Copy Link
        </button>
    </div>
    @endif

    {{-- Tracking link --}}
    <!-- <div class="mt-6 bg-orange-50 border border-orange-200 rounded-xl p-4 text-center">
        <p class="text-orange-700 text-sm font-medium mb-2">📍 Your order tracking link:</p>
        <p class="text-orange-600 text-xs font-mono break-all">{{ url('/track/'.$order->tracking_token) }}</p>
        <button onclick="navigator.clipboard.writeText('{{ url('/track/'.$order->tracking_token) }}').then(() => this.textContent = '✅ Copied!')"
            class="mt-2 text-xs text-orange-600 border border-orange-300 px-3 py-1 rounded-full hover:bg-orange-100 transition">
            Copy Link
        </button>
    </div> -->
</div>
@endsection