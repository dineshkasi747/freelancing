@extends('layouts.admin')
@section('page-title', 'Edit Category')
@section('content')

<div class="max-w-lg">
    <a href="{{ route('admin.categories.index') }}" class="text-gray-400 hover:text-gray-600 text-sm mb-6 inline-block">
        ← Back to Categories
    </a>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Category Name *</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 transition">
            </div>
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Icon (Emoji)</label>
                <input type="text" name="icon" value="{{ old('icon', $category->icon) }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 transition">
            </div>
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 transition">
            </div>
            <div class="mb-6">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }} class="w-4 h-4 accent-orange-600">
                    <span class="text-sm font-medium text-gray-700">Show on menu (active)</span>
                </label>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-orange-600 text-white py-3 rounded-xl font-bold hover:bg-orange-700 transition">
                    Save Changes
                </button>
                <a href="{{ route('admin.categories.index') }}" class="px-6 py-3 border border-gray-200 text-gray-600 rounded-xl font-medium hover:bg-gray-50 transition text-sm">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection