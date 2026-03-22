@php use App\Models\Setting; @endphp
@extends('layouts.app')
@section('content')
<div class="max-w-md mx-auto px-4 py-16 text-center">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <div class="text-6xl mb-4">💳</div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Complete Payment</h1>
        <p class="text-gray-500 mb-6">You're paying <span class="text-orange-600 font-bold text-xl">₹{{ $total }}</span></p>

        <div class="bg-orange-50 rounded-xl p-4 mb-6 text-left">
            @foreach($cart as $item)
            <div class="flex justify-between text-sm text-gray-700 mb-1">
                <span>{{ $item['quantity'] }}x {{ $item['name'] }}</span>
                <span>₹{{ $item['price'] * $item['quantity'] }}</span>
            </div>
            @endforeach
            <hr class="my-2">
            <div class="flex justify-between font-bold">
                <span>Total</span>
                <span class="text-orange-600">₹{{ $total }}</span>
            </div>
        </div>

        <button id="pay-btn"
            class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold text-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
            <span>Pay with Razorpay</span>
        </button>

        <p class="text-gray-400 text-xs mt-4">Secured by Razorpay • UPI, Cards, NetBanking accepted</p>
    </div>
</div>

<form id="payment-form" action="{{ route('payment.verify') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
    <input type="hidden" name="razorpay_signature" id="razorpay_signature">
</form>
@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('pay-btn').addEventListener('click', function() {
    var options = {
        key: "{{ $razorpayKeyId }}",
        amount: {{ $total * 100 }},
        currency: "INR",
        name: "{{ $shopName }}",
        description: "Food Order",
        order_id: "{{ $razorpayOrderId }}",
        prefill: {
            name: "{{ $customerName }}",
            contact: "{{ $customerPhone }}"
        },
        theme: { color: "#EA580C" },
        handler: function(response) {
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
            document.getElementById('razorpay_signature').value = response.razorpay_signature;
            document.getElementById('payment-form').submit();
        },
        modal: {
            ondismiss: function() {
                alert('Payment cancelled. Please try again.');
            }
        }
    };
    var rzp = new Razorpay(options);
    rzp.open();
});
</script>
@endpush