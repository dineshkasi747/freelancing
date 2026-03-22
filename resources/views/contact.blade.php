@php use App\Models\Setting; @endphp
@extends('layouts.app')
@section('content')

<div class="bg-white border-b border-gray-100 py-12">
    <div class="max-w-6xl mx-auto px-6 text-center">
        <p class="text-orange-600 text-sm font-medium uppercase tracking-widest mb-3">Get In Touch</p>
        <h1 class="font-playfair text-4xl font-bold text-gray-900 mb-4">Contact Us</h1>
        <div class="w-16 h-1 bg-orange-600 mx-auto rounded"></div>
    </div>
</div>

<div class="max-w-5xl mx-auto px-6 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <div class="space-y-6">
            @foreach([
                ['📞','Call Us',$phone,'tel:'.$phone,$phone],
                ['💬','WhatsApp',null,'https://wa.me/'.$whatsapp,'Chat on WhatsApp'],
                ['📍','Find Us',$address,null,null],
                ['🕐','Opening Hours',$timings,null,null],
            ] as [$icon,$title,$text,$link,$linkText])
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-start gap-5 hover:shadow-md transition">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">{{ $icon }}</div>
                <div>
                    <h3 class="font-playfair font-bold text-gray-900 mb-1">{{ $title }}</h3>
                    @if($text)<p class="text-gray-500 text-sm">{{ $text }}</p>@endif
                    @if($link)<a href="{{ $link }}" class="text-orange-600 hover:text-orange-700 font-medium text-sm transition">{{ $linkText }}</a>@endif
                </div>
            </div>
            @endforeach

            <div class="bg-orange-600 rounded-2xl p-6 text-white">
                <h3 class="font-playfair text-xl font-bold mb-2">Order Now</h3>
                <p class="text-orange-100 text-sm mb-4">Ready to eat? Browse our menu and order online!</p>
                <a href="{{ route('menu') }}" class="bg-white text-orange-600 px-6 py-2 rounded-full font-semibold text-sm hover:bg-orange-50 transition inline-block">
                    View Menu →
                </a>
            </div>
        </div>

        <div class="rounded-2xl overflow-hidden shadow-sm border border-gray-100 h-[500px]">
            <iframe
                src="https://maps.google.com/maps?q={{ urlencode($address) }}&output=embed"
                width="100%" height="100%" frameborder="0" allowfullscreen loading="lazy">
            </iframe>
        </div>
    </div>
</div>
@endsection