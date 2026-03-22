@extends('layouts.admin')
@section('page-title', 'Add Menu Item')
@section('content')

<div class="max-w-2xl">
    <a href="{{ route('admin.menu-items.index') }}" class="text-gray-400 hover:text-gray-600 text-sm flex items-center gap-1 mb-6">
        ← Back to Menu Items
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('admin.menu-items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-2 gap-5 mb-5">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Item Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 transition"
                        placeholder="e.g. Masala Dosa">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Category *</label>
                    <select name="category_id" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 transition bg-white">
                        <option value="">Select category</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->icon }} {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Price (₹) *</label>
                    <input type="number" name="price" value="{{ old('price') }}" required min="0" step="0.01"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 transition"
                        placeholder="0.00">
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 transition"
                        placeholder="Short description of the item">{{ old('description') }}</textarea>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Item Photo</label>
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-orange-400 transition cursor-pointer"
                         onclick="document.getElementById('image').click()">
                        <div class="text-3xl mb-2">📷</div>
                        <p class="text-gray-400 text-sm">Click to upload photo</p>
                        <p class="text-gray-300 text-xs mt-1">JPG, PNG up to 2MB</p>
                        <input type="file" id="image" name="image" accept="image/*" class="hidden"
                               onchange="previewImage(this)">
                    </div>
                    <img id="preview" class="hidden mt-3 h-32 rounded-xl object-cover">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 mb-6">
                <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:border-orange-400 transition">
                    <input type="checkbox" name="is_veg" value="1" {{ old('is_veg', '1') ? 'checked' : '' }}
                           class="w-4 h-4 accent-green-600">
                    <span class="text-sm font-medium text-gray-700">🟢 Vegetarian</span>
                </label>
                <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:border-orange-400 transition">
                    <input type="checkbox" name="is_available" value="1" {{ old('is_available', '1') ? 'checked' : '' }}
                           class="w-4 h-4 accent-orange-600">
                    <span class="text-sm font-medium text-gray-700">✓ Available</span>
                </label>
                <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:border-orange-400 transition">
                    <input type="checkbox" name="is_special" value="1" {{ old('is_special') ? 'checked' : '' }}
                           class="w-4 h-4 accent-yellow-500">
                    <span class="text-sm font-medium text-gray-700">⭐ Special</span>
                </label>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="flex-1 bg-orange-600 text-white py-3 rounded-xl font-bold hover:bg-orange-700 transition">
                    Add Item
                </button>
                <a href="{{ route('admin.menu-items.index') }}"
                   class="px-6 py-3 border border-gray-200 text-gray-600 rounded-xl font-medium hover:bg-gray-50 transition text-sm">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('preview');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection