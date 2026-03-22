@php use App\Models\Setting; @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="15"> {{-- Auto refresh every 15s --}}
    <title>Track Order #{{ $order->id }} — {{ $shopName }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-playfair { font-family: 'Playfair Display', serif; }
        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 1; }
            100% { transform: scale(1.4); opacity: 0; }
        }
        .pulse-ring::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            border: 3px solid currentColor;
            animation: pulse-ring 1.5s ease-out infinite;
        }
        @keyframes progress-bar {
            from { width: 0%; }
            to { width: 100%; }
        }
        .animate-progress { animation: progress-bar 15s linear forwards; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

{{-- Navbar --}}
<nav class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
    <div class="max-w-2xl mx-auto px-6 py-4 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <span class="text-xl">🍱</span>
            <span class="font-playfair font-bold text-gray-900">{{ $shopName }}</span>
        </a>
        <span class="text-gray-400 text-sm">Order Tracking</span>
    </div>
    {{-- Auto refresh progress bar --}}
    <div class="h-0.5 bg-gray-100">
        <div class="h-full bg-orange-400 animate-progress"></div>
    </div>
</nav>

<div class="max-w-2xl mx-auto px-6 py-10">

    {{-- Order header --}}
    <div class="text-center mb-10">
        <p class="text-gray-400 text-sm mb-1">Tracking Order</p>
        <h1 class="font-playfair text-3xl font-bold text-gray-900">#{{ $order->id }}</h1>
        <p class="text-gray-400 text-sm mt-1">{{ $order->customer_name }} • {{ $order->created_at->format('d M Y, h:i A') }}</p>
        <p class="text-xs text-gray-300 mt-1">Auto-refreshes every 15 seconds</p>
    </div>

    {{-- Status tracker --}}
    @php
        $steps = [
            ['key' => 'pending',   'label' => 'Order Placed',   'icon' => '📋', 'desc' => 'Your order has been received'],
            ['key' => 'preparing', 'label' => 'Preparing',      'icon' => '👨‍🍳', 'desc' => 'Kitchen is preparing your food'],
            ['key' => 'ready',     'label' => 'Ready',          'icon' => '✅', 'desc' => $order->order_type === 'delivery' ? 'Out for delivery' : 'Ready for pickup'],
            ['key' => 'delivered', 'label' => 'Delivered',      'icon' => '🎉', 'desc' => 'Enjoy your meal!'],
        ];
        $statusOrder = ['pending' => 0, 'preparing' => 1, 'ready' => 2, 'delivered' => 3, 'cancelled' => -1];
        $currentStep = $statusOrder[$order->status] ?? 0;
    @endphp

    @if($order->status === 'cancelled')
    {{-- Cancelled state --}}
    <div class="bg-red-50 border border-red-200 rounded-2xl p-8 text-center mb-8">
        <div class="text-5xl mb-4">❌</div>
        <h2 class="font-playfair text-2xl font-bold text-red-700 mb-2">Order Cancelled</h2>
        <p class="text-red-500 text-sm">Your order #{{ $order->id }} has been cancelled.</p>
        <p class="text-red-400 text-sm mt-1">Please contact us for more information.</p>
        <a href="tel:{{ Setting::get('shop_phone') }}"
           class="inline-block mt-4 bg-red-600 text-white px-6 py-2.5 rounded-full font-medium hover:bg-red-700 transition text-sm">
            📞 Call Us
        </a>
    </div>
    @else
    {{-- Status steps --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-6">
        <div class="relative">
            {{-- Progress line --}}
            <div class="absolute top-8 left-8 right-8 h-0.5 bg-gray-100 z-0"></div>
            <div class="absolute top-8 left-8 h-0.5 bg-orange-500 z-0 transition-all duration-1000"
                 style="width: calc({{ ($currentStep / 3) * 100 }}% * (100% - 4rem) / 100%);">
            </div>

            {{-- Steps --}}
            <div class="relative z-10 grid grid-cols-4 gap-2">
                @foreach($steps as $i => $step)
                @php
                    $isDone    = $i < $currentStep;
                    $isCurrent = $i === $currentStep;
                    $isPending = $i > $currentStep;
                @endphp
                <div class="flex flex-col items-center text-center">
                    {{-- Icon circle --}}
                    <div class="relative mb-3">
                        @if($isCurrent)
                        <div class="w-16 h-16 rounded-full bg-orange-600 flex items-center justify-center text-2xl shadow-lg relative pulse-ring text-orange-600">
                            <span class="z-10 relative">{{ $step['icon'] }}</span>
                        </div>
                        @elseif($isDone)
                        <div class="w-16 h-16 rounded-full bg-green-500 flex items-center justify-center text-2xl shadow-md">
                            {{ $step['icon'] }}
                        </div>
                        @else
                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-2xl opacity-40">
                            {{ $step['icon'] }}
                        </div>
                        @endif
                    </div>
                    <p class="font-semibold text-xs {{ $isCurrent ? 'text-orange-600' : ($isDone ? 'text-green-600' : 'text-gray-400') }}">
                        {{ $step['label'] }}
                    </p>
                    @if($isCurrent)
                    <p class="text-gray-400 text-xs mt-0.5 leading-tight">{{ $step['desc'] }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- Current status message --}}
        <div class="mt-8 p-4 rounded-xl {{ $order->status === 'delivered' ? 'bg-green-50 border border-green-200' : 'bg-orange-50 border border-orange-200' }}">
            @php
                $messages = [
                    'pending'   => ['We received your order and will start preparing it shortly! ⏳', 'text-orange-700'],
                    'preparing' => ['Our kitchen is working on your order right now! 🔥', 'text-orange-700'],
                    'ready'     => [$order->order_type === 'delivery' ? 'Your order is on its way! 🛵' : 'Your order is ready! Please collect from the counter. 🎉', 'text-orange-700'],
                    'delivered' => ['Your order has been delivered. Enjoy your meal! 😊', 'text-green-700'],
                ];
                [$msg, $color] = $messages[$order->status] ?? ['Order status updated.', 'text-gray-700'];
            @endphp
            <p class="text-center font-medium text-sm {{ $color }}">{{ $msg }}</p>
        </div>
    </div>
    @endif

    {{-- Order details --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-playfair font-bold text-gray-900">Order Details</h3>
        </div>
        <div class="p-6">
            <div class="space-y-3 mb-4">
                @foreach($order->orderItems as $oi)
                <div class="flex justify-between items-center text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 bg-orange-100 text-orange-700 rounded-full flex items-center justify-center text-xs font-bold">
                            {{ $oi->quantity }}
                        </span>
                        <span class="text-gray-700">{{ $oi->menuItem->name }}</span>
                    </div>
                    <span class="font-medium text-gray-800">₹{{ $oi->price * $oi->quantity }}</span>
                </div>
                @endforeach
            </div>
            <div class="border-t border-gray-100 pt-4 flex justify-between font-bold text-lg">
                <span class="font-playfair">Total</span>
                <span class="text-orange-600">₹{{ $order->total_amount }}</span>
            </div>
            <div class="grid grid-cols-2 gap-3 mt-4">
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-gray-400 text-xs mb-1">Order Type</p>
                    <p class="font-medium text-gray-800 text-sm capitalize">{{ str_replace('_', ' ', $order->order_type) }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-gray-400 text-xs mb-1">Payment</p>
                    <p class="font-medium text-gray-800 text-sm capitalize">{{ $order->payment_method ?? 'cash' }}</p>
                </div>
                @if($order->table_number)
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-gray-400 text-xs mb-1">Table</p>
                    <p class="font-medium text-gray-800 text-sm">{{ $order->table_number }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex gap-4">
        <a href="{{ route('menu') }}"
           class="flex-1 text-center bg-orange-600 text-white py-3 rounded-xl font-bold hover:bg-orange-700 transition text-sm">
            Order More
        </a>
        <a href="https://wa.me/{{ $whatsapp }}" target="_blank"
           class="flex-1 text-center border-2 border-green-500 text-green-600 py-3 rounded-xl font-bold hover:bg-green-500 hover:text-white transition text-sm">
            💬 Help
        </a>
    </div>

    <p class="text-center text-gray-300 text-xs mt-6">
        Share this page: <span class="text-gray-400">{{ url('/track/'.$order->tracking_token) }}</span>
    </p>
</div>

</body>
</html>