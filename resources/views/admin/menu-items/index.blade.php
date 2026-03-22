@extends('layouts.admin')
@section('page-title', 'Menu Items')
@section('content')

<div class="flex justify-between items-center mb-6">
    <div>
        <p class="text-gray-500 text-sm">{{ $items->total() }} items total</p>
    </div>
    <a href="{{ route('admin.menu-items.create') }}"
       class="bg-orange-600 text-white px-5 py-2.5 rounded-xl font-medium hover:bg-orange-700 transition text-sm flex items-center gap-2">
        + Add New Item
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-6 py-4 text-gray-500 font-medium">Item</th>
                <th class="text-left px-6 py-4 text-gray-500 font-medium">Category</th>
                <th class="text-left px-6 py-4 text-gray-500 font-medium">Price</th>
                <th class="text-left px-6 py-4 text-gray-500 font-medium">Type</th>
                <th class="text-left px-6 py-4 text-gray-500 font-medium">Status</th>
                <th class="text-left px-6 py-4 text-gray-500 font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($items as $item)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        @if($item->image)
                            <img src="{{ asset('storage/'.$item->image) }}"
                                 class="w-10 h-10 rounded-xl object-cover flex-shrink-0">
                        @else
                            <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center text-xl flex-shrink-0">
                                🍽️
                            </div>
                        @endif
                        <div>
                            <p class="font-semibold text-gray-800">{{ $item->name }}</p>
                            @if($item->is_special)
                            <span class="text-xs text-orange-600 font-medium">⭐ Special</span>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-600">{{ $item->category->icon }} {{ $item->category->name }}</td>
                <td class="px-6 py-4 font-bold text-gray-800">₹{{ $item->price }}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-1 rounded-full {{ $item->is_veg ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $item->is_veg ? 'bg-green-600' : 'bg-red-600' }}"></span>
                        {{ $item->is_veg ? 'Veg' : 'Non-Veg' }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <span class="text-xs font-medium px-2 py-1 rounded-full {{ $item->is_available ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $item->is_available ? '✓ Available' : '✗ Unavailable' }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.menu-items.edit', $item) }}"
                           class="text-blue-600 hover:text-blue-700 font-medium text-xs px-3 py-1.5 border border-blue-200 rounded-lg hover:bg-blue-50 transition">
                            Edit
                        </a>
                        <form action="{{ route('admin.menu-items.destroy', $item) }}" method="POST"
                              onsubmit="return confirm('Delete {{ $item->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="text-red-500 hover:text-red-600 font-medium text-xs px-3 py-1.5 border border-red-200 rounded-lg hover:bg-red-50 transition">
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                    <div class="text-4xl mb-2">🍽️</div>
                    No menu items yet.
                    <a href="{{ route('admin.menu-items.create') }}" class="text-orange-600 hover:underline ml-1">Add one now</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($items->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $items->links() }}
    </div>
    @endif
</div>
@endsection