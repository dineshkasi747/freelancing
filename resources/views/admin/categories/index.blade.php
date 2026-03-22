@extends('layouts.admin')
@section('page-title', 'Categories')
@section('content')

<div class="flex justify-between items-center mb-6">
    <p class="text-gray-500 text-sm">{{ $categories->count() }} categories</p>
    <a href="{{ route('admin.categories.create') }}"
       class="bg-orange-600 text-white px-5 py-2.5 rounded-xl font-medium hover:bg-orange-700 transition text-sm">
        + Add Category
    </a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
    @forelse($categories as $cat)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center text-2xl">
                    {{ $cat->icon ?? '🍽️' }}
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">{{ $cat->name }}</h3>
                    <p class="text-gray-400 text-xs">{{ $cat->menu_items_count }} items</p>
                </div>
            </div>
            <span class="text-xs px-2 py-1 rounded-full {{ $cat->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                {{ $cat->is_active ? 'Active' : 'Hidden' }}
            </span>
        </div>
        <div class="flex items-center gap-2 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.categories.edit', $cat) }}"
               class="flex-1 text-center text-blue-600 border border-blue-200 py-2 rounded-lg text-xs font-medium hover:bg-blue-50 transition">
                Edit
            </a>
            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST"
                  onsubmit="return confirm('Delete {{ $cat->name }}?')">
                @csrf @method('DELETE')
                <button type="submit"
                    class="text-red-500 border border-red-200 py-2 px-4 rounded-lg text-xs font-medium hover:bg-red-50 transition">
                    Delete
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-3 text-center py-12 text-gray-400">
        No categories yet.
        <a href="{{ route('admin.categories.create') }}" class="text-orange-600 hover:underline ml-1">Add one</a>
    </div>
    @endforelse
</div>
@endsection