@extends('layouts.admin')
@section('page-title', 'Dashboard')
@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <p class="text-gray-500 text-sm">Today's Orders</p>
        <p class="text-4xl font-bold text-orange-600 mt-1">{{ $stats['today_orders'] }}</p>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <p class="text-gray-500 text-sm">Today's Revenue</p>
        <p class="text-4xl font-bold text-green-600 mt-1">₹{{ $stats['today_revenue'] }}</p>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <p class="text-gray-500 text-sm">Pending Orders</p>
        <p class="text-4xl font-bold text-yellow-600 mt-1">{{ $stats['pending_orders'] }}</p>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <p class="text-gray-500 text-sm">Total Orders</p>
        <p class="text-4xl font-bold text-blue-600 mt-1">{{ $stats['total_orders'] }}</p>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <p class="text-gray-500 text-sm">Menu Items</p>
        <p class="text-4xl font-bold text-purple-600 mt-1">{{ $stats['total_items'] }}</p>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <p class="text-gray-500 text-sm">Categories</p>
        <p class="text-4xl font-bold text-pink-600 mt-1">{{ $stats['total_cats'] }}</p>
    </div>
</div>

{{-- Recent orders --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-bold text-gray-800">Recent Orders</h3>
        <a href="{{ route('admin.orders.index') }}" class="text-orange-600 text-sm hover:underline">View All →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">#</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Customer</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Type</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Amount</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Status</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Time</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($recentOrders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium">#{{ $order->id }}</td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800">{{ $order->customer_name }}</div>
                        <div class="text-gray-400">{{ $order->customer_phone }}</div>
                    </td>
                    <td class="px-6 py-4 capitalize">{{ str_replace('_', ' ', $order->order_type) }}</td>
                    <td class="px-6 py-4 font-bold text-gray-800">₹{{ $order->total_amount }}</td>
                    <td class="px-6 py-4">
                        @php
                            $colors = ['pending'=>'yellow','preparing'=>'blue','ready'=>'purple','delivered'=>'green','cancelled'=>'red'];
                            $c = $colors[$order->status] ?? 'gray';
                        @endphp
                        <span class="bg-{{ $c }}-100 text-{{ $c }}-700 px-2 py-1 rounded-full text-xs font-medium capitalize">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-400">{{ $order->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">No orders yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection