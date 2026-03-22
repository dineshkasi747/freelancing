@php use App\Models\Setting; @endphp
@extends('layouts.admin')
@section('page-title', 'Orders')
@section('content')



{{-- Stats bar --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @php
        $statuses = ['pending' => ['🕐','yellow'], 'preparing' => ['👨‍🍳','blue'], 'ready' => ['✅','purple'], 'delivered' => ['🎉','green']];
    @endphp
    @foreach($statuses as $status => [$icon, $color])
    <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide">{{ ucfirst($status) }}</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">
                    {{ \App\Models\Order::where('status', $status)->count() }}
                </p>
            </div>
            <span class="text-2xl">{{ $icon }}</span>
        </div>
    </div>
    @endforeach
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-bold text-gray-800">All Orders</h3>
        <span class="text-gray-400 text-sm">{{ $orders->total() }} total</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-4 text-gray-500 font-medium">#</th>
                    <th class="text-left px-6 py-4 text-gray-500 font-medium">Customer</th>
                    <th class="text-left px-6 py-4 text-gray-500 font-medium">Items</th>
                    <th class="text-left px-6 py-4 text-gray-500 font-medium">Type</th>
                    <th class="text-left px-6 py-4 text-gray-500 font-medium">Amount</th>
                    <th class="text-left px-6 py-4 text-gray-500 font-medium">Status</th>
                    <th class="text-left px-6 py-4 text-gray-500 font-medium">Update</th>
                    <th class="text-left px-6 py-4 text-gray-500 font-medium">Notify</th>
                    <th class="text-left px-6 py-4 text-gray-500 font-medium">Track</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($orders as $order)
                @php
                    $colors = [
                        'pending'   => 'bg-yellow-100 text-yellow-700',
                        'preparing' => 'bg-blue-100 text-blue-700',
                        'ready'     => 'bg-purple-100 text-purple-700',
                        'delivered' => 'bg-green-100 text-green-700',
                        'cancelled' => 'bg-red-100 text-red-700',
                    ];
                    $c = $colors[$order->status] ?? 'bg-gray-100 text-gray-600';
                @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <span class="font-bold text-gray-800">#{{ $order->id }}</span>
                        <p class="text-gray-400 text-xs">{{ $order->created_at->diffForHumans() }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-800">{{ $order->customer_name }}</p>
                        <a href="tel:{{ $order->customer_phone }}" class="text-orange-600 text-xs hover:underline">
                            {{ $order->customer_phone }}
                        </a>
                        @if($order->table_number)
                        <p class="text-gray-400 text-xs">Table: {{ $order->table_number }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="space-y-0.5">
                            @foreach($order->orderItems as $oi)
                            <p class="text-gray-600 text-xs">{{ $oi->quantity }}× {{ $oi->menuItem->name }}</p>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-gray-600 text-xs capitalize">{{ str_replace('_',' ',$order->order_type) }}</span>
                        @if($order->payment_method ?? false)
                        <p class="text-gray-400 text-xs capitalize">{{ $order->payment_method }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-gray-800">₹{{ $order->total_amount }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $c }} capitalize">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="flex gap-2 items-center">
                            @csrf @method('PATCH')
                            <select name="status"
                                class="border border-gray-200 rounded-lg px-2 py-1.5 text-xs bg-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                                @foreach(['pending','preparing','ready','delivered','cancelled'] as $s)
                                <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                                @endforeach
                            </select>
                            <button type="submit"
                                class="bg-orange-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-orange-700 transition whitespace-nowrap">
                                Update
                            </button>
                        </form>
                    </td>
                        {{-- Notify column in table --}}
                        <td class="px-6 py-4">
                            <button onclick="notifyCustomer(
                                '{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}',
                                '{{ $order->customer_name }}',
                                '{{ $order->id }}',
                                '{{ $order->status }}'
                            )" class="inline-flex items-center gap-1 bg-green-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-green-600 transition">
                                💬 Notify
                            </button>
                        </td>

                            <td class="px-6 py-4">
                                <a href="{{ route('order.track', $order->tracking_token) }}" target="_blank"
                                class="inline-flex items-center gap-1 bg-blue-500 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-blue-600 transition">
                                    📍 Track
                                </a>
                            </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                        <div class="text-4xl mb-2">📋</div>
                        No orders yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $orders->links() }}
    </div>
    @endif
</div>


{{-- WhatsApp Notification Modal --}}
<div id="wa-modal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">💬</div>
            <h3 class="font-bold text-gray-900 text-lg">Notify Customer</h3>
            <p class="text-gray-500 text-sm mt-1">Send WhatsApp message to customer</p>
        </div>

        <div class="bg-green-50 rounded-xl p-4 mb-6">
            <p class="text-gray-700 text-sm" id="wa-preview"></p>
        </div>

        <div class="flex gap-3">
            <a id="wa-link" href="#" target="_blank"
               onclick="document.getElementById('wa-modal').classList.add('hidden')"
               class="flex-1 text-center bg-green-500 text-white py-3 rounded-xl font-bold hover:bg-green-600 transition text-sm">
                Open WhatsApp & Send
            </a>
            <button onclick="document.getElementById('wa-modal').classList.add('hidden')"
                class="px-5 py-3 border border-gray-200 text-gray-600 rounded-xl font-medium hover:bg-gray-50 transition text-sm">
                Cancel
            </button>
        </div>
        <p class="text-center text-gray-400 text-xs mt-3">WhatsApp will open with the message ready to send</p>
    </div>
</div>

@push('scripts')
<script>
const statusMessages = {
    pending:   (name, id) => `Hi ${name}! We received your order #${id} at {{ Setting::get('shop_name') }}. We'll start preparing it soon! 🍽️`,
    preparing: (name, id) => `👨‍🍳 Hi ${name}! Your order #${id} is now being *prepared*. Won't be long! 🔥`,
    ready:     (name, id) => `✅ Hi ${name}! Your order #${id} is *ready*! Please collect from the counter. 🎉`,
    delivered: (name, id) => `😊 Hi ${name}! Your order #${id} has been *delivered*. Enjoy your meal! Thank you for choosing {{ Setting::get('shop_name') }}! ⭐`,
    cancelled: (name, id) => `❌ Hi ${name}! Sorry, your order #${id} has been *cancelled*. Please call us at {{ Setting::get('shop_phone') }} for help.`,
};

function notifyCustomer(phone, name, orderId, status) {
    // Fix phone number
    phone = phone.replace(/\D/g, '');
    if (phone.length === 10) phone = '91' + phone;

    const message = statusMessages[status] ? statusMessages[status](name, orderId) : `Hi ${name}! Update on your order #${orderId}: Status is now *${status}*.`;

    document.getElementById('wa-preview').textContent = message;
    document.getElementById('wa-link').href = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
    document.getElementById('wa-modal').classList.remove('hidden');
}

// Close modal on backdrop click
document.getElementById('wa-modal').addEventListener('click', function(e) {
    if (e.target === this) this.classList.add('hidden');
});
</script>
@endpush
@endsection