@php use App\Models\Setting; @endphp
@extends('layouts.app')
@section('content')

<div class="bg-white border-b border-gray-100 py-12">
    <div class="max-w-6xl mx-auto px-6 text-center">
        <h1 class="font-playfair text-4xl font-bold text-gray-900">Checkout</h1>
        <div class="w-16 h-1 bg-orange-600 mx-auto rounded mt-4"></div>
    </div>
</div>

<div class="max-w-5xl mx-auto px-6 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <h2 class="font-playfair text-xl font-bold text-gray-900 mb-6">Your Details</h2>
                <form action="{{ route('order.place') }}" method="POST">
                    @csrf
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name *</label>
                        <input type="text" name="customer_name" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                            placeholder="Your name">
                    </div>
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone Number *</label>
                        <input type="text" name="customer_phone" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                            placeholder="+91 XXXXX XXXXX">
                    </div>
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order Type *</label>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach([['dine_in','🪑','Dine In'],['takeaway','🛍️','Takeaway'],['delivery','🛵','Delivery']] as [$val,$emoji,$label])
                            <label class="cursor-pointer">
                                <input type="radio" name="order_type" value="{{ $val }}" class="sr-only" {{ $val === 'dine_in' ? 'checked' : '' }}>
                                <div class="order-type-box text-center p-3 rounded-xl border-2 {{ $val === 'dine_in' ? 'border-orange-500 bg-orange-50' : 'border-gray-200' }} hover:border-orange-400 transition">
                                    <div class="text-2xl mb-1">{{ $emoji }}</div>
                                    <div class="text-xs font-medium text-gray-700">{{ $label }}</div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-5" id="table-field">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Table Number</label>
                        <input type="text" name="table_number"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 transition"
                            placeholder="e.g. Table 5">
                    </div>
                    <div class="mb-5 hidden" id="address-field">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Delivery Address *</label>
                        <textarea name="delivery_address" rows="3"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 transition"
                            placeholder="Full address"></textarea>
                    </div>
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                        <div class="space-y-3">
                            @foreach([['upi','📱','UPI / GPay / PhonePe','Pay using any UPI app'],['cash','💵','Cash on Delivery','Pay when food arrives'],['whatsapp','💬','Via WhatsApp','We confirm your order']] as [$val,$emoji,$label,$sub])
                            <label class="cursor-pointer block">
                                <input type="radio" name="payment_method" value="{{ $val }}" class="sr-only" {{ $val === 'upi' ? 'checked' : '' }}>
                                <div class="payment-box border-2 {{ $val === 'upi' ? 'border-orange-500 bg-orange-50' : 'border-gray-200' }} rounded-xl p-3 flex items-center gap-3 hover:border-orange-400 transition">
                                    <span class="text-xl">{{ $emoji }}</span>
                                    <div>
                                        <p class="font-semibold text-gray-800 text-sm">{{ $label }}</p>
                                        <p class="text-gray-400 text-xs">{{ $sub }}</p>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Special Instructions</label>
                        <textarea name="notes" rows="2"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 transition"
                            placeholder="Any allergies or special requests?"></textarea>
                    </div>
                    <button type="submit"
                        class="w-full bg-orange-600 text-white py-4 rounded-xl font-bold text-base hover:bg-orange-700 transition">
                        Place Order — ₹{{ $total }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Order summary --}}
        <div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sticky top-24">
                <h2 class="font-playfair text-xl font-bold text-gray-900 mb-6">Order Summary</h2>
                <div class="space-y-3 mb-6">
                    @foreach($cart as $item)
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-full {{ $item['is_veg'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} flex items-center justify-center text-xs font-bold">
                                {{ $item['quantity'] }}
                            </span>
                            <span class="text-gray-700 text-sm">{{ $item['name'] }}</span>
                        </div>
                        <span class="font-medium text-gray-800 text-sm">₹{{ $item['price'] * $item['quantity'] }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="border-t border-gray-100 pt-4">
                    <div class="flex justify-between font-bold text-lg">
                        <span class="font-playfair">Total</span>
                        <span class="text-orange-600">₹{{ $total }}</span>
                    </div>
                    <p class="text-gray-400 text-xs mt-2">Payment at counter / on delivery</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.querySelectorAll('input[name="order_type"]').forEach(input => {
    input.addEventListener('change', () => {
        document.querySelectorAll('.order-type-box').forEach(b => {
            b.classList.remove('border-orange-500', 'bg-orange-50');
            b.classList.add('border-gray-200');
        });
        input.nextElementSibling.classList.add('border-orange-500', 'bg-orange-50');
        input.nextElementSibling.classList.remove('border-gray-200');
        document.getElementById('address-field').classList.add('hidden');
        document.getElementById('table-field').classList.add('hidden');
        if (input.value === 'delivery') document.getElementById('address-field').classList.remove('hidden');
        if (input.value === 'dine_in') document.getElementById('table-field').classList.remove('hidden');
    });
});
document.querySelectorAll('input[name="payment_method"]').forEach(input => {
    input.addEventListener('change', () => {
        document.querySelectorAll('.payment-box').forEach(b => {
            b.classList.remove('border-orange-500', 'bg-orange-50');
            b.classList.add('border-gray-200');
        });
        input.nextElementSibling.classList.add('border-orange-500', 'bg-orange-50');
        input.nextElementSibling.classList.remove('border-gray-200');
    });
});
</script>
@endpush