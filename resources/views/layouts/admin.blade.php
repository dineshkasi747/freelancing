@php use App\Models\Setting; @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — {{ Setting::get('shop_name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; } .font-playfair { font-family: 'Playfair Display', serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen">
<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-64 bg-gray-900 text-white flex flex-col flex-shrink-0 fixed h-full z-40">
        <div class="p-6 border-b border-gray-800">
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xl">🍱</span>
                <span class="font-playfair font-bold text-orange-400">Admin Panel</span>
            </div>
            <p class="text-gray-500 text-xs">{{ Setting::get('shop_name') }}</p>
        </div>

        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('admin.dashboard') ? 'bg-orange-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <span>📊</span> Dashboard
            </a>
            <a href="{{ route('admin.orders.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('admin.orders.*') ? 'bg-orange-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <span>📋</span> Orders
                @php $pending = \App\Models\Order::where('status','pending')->count(); @endphp
                @if($pending > 0)
                <span class="ml-auto bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ $pending }}</span>
                @endif
            </a>
            <a href="{{ route('admin.menu-items.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('admin.menu-items.*') ? 'bg-orange-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <span>🍽️</span> Menu Items
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
               {{ request()->routeIs('admin.categories.*') ? 'bg-orange-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <span>📁</span> Categories
            </a>
        </nav>

        <div class="p-4 border-t border-gray-800 space-y-2">
            <a href="{{ route('home') }}" target="_blank"
               class="flex items-center gap-2 text-gray-400 hover:text-white text-sm transition px-4 py-2 rounded-xl hover:bg-gray-800">
                <span>🌐</span> View Site
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex items-center gap-2 text-gray-400 hover:text-red-400 text-sm transition px-4 py-2 rounded-xl hover:bg-gray-800 w-full">
                    <span>🚪</span> Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex-1 ml-64 flex flex-col min-h-screen">
        <header class="bg-white border-b border-gray-100 px-8 py-4 flex justify-between items-center sticky top-0 z-30">
            <h2 class="font-playfair text-xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 font-bold text-sm">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <span class="text-gray-600 text-sm">{{ auth()->user()->name ?? 'Admin' }}</span>
            </div>
        </header>

        @if(session('success'))
        <div class="mx-8 mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            ✅ {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mx-8 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
            ❌ {{ session('error') }}
        </div>
        @endif

        <main class="flex-1 p-8">
            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>