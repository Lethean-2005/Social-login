<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — Shop</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-white text-gray-900 antialiased min-h-screen">

@php
    $featured = \App\Models\Product::published()->inStock()->latest()->take(8)->get();
    $categories = \App\Models\Product::published()->whereNotNull('category')
        ->selectRaw('category, COUNT(*) as cnt')->groupBy('category')
        ->orderByDesc('cnt')->take(6)->get();
@endphp

<!-- Navbar -->
<header class="sticky top-0 z-20 bg-white/80 backdrop-blur border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
        <a href="{{ url('/') }}" class="flex items-center gap-2 font-semibold">
            <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-500"></div>
            <span class="text-gray-900">{{ config('app.name') }}</span>
        </a>
        <nav class="hidden md:flex items-center gap-6 text-sm text-gray-700">
            <a href="{{ route('shop.index') }}" class="hover:text-indigo-600">{{ __('Shop') }}</a>
            <a href="#categories" class="hover:text-indigo-600">{{ __('Categories') }}</a>
            <a href="#features" class="hover:text-indigo-600">{{ __('Why us') }}</a>
        </nav>
        <div class="flex items-center gap-3 text-sm">
            @auth
                <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-indigo-600">{{ __('Cart') }}</a>
                <a href="{{ route('dashboard') }}" class="rounded-full bg-indigo-600 px-4 py-2 text-white font-medium hover:bg-indigo-500">{{ __('Dashboard') }}</a>
            @else
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600">{{ __('Sign in') }}</a>
                <a href="{{ route('register') }}" class="rounded-full bg-indigo-600 px-4 py-2 text-white font-medium hover:bg-indigo-500">{{ __('Sign up') }}</a>
            @endauth
        </div>
    </div>
</header>

<!-- Hero -->
<section class="bg-gradient-to-b from-indigo-50 via-white to-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 sm:py-24 grid grid-cols-1 md:grid-cols-2 items-center gap-10">
        <div>
            <span class="inline-flex rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">{{ __('New arrivals every week') }}</span>
            <h1 class="mt-4 text-4xl sm:text-5xl font-bold text-gray-900 leading-tight">
                {{ __('Shop better.') }} <span class="text-indigo-600">{{ __('Pay less.') }}</span>
            </h1>
            <p class="mt-4 text-lg text-gray-600">{{ __('A curated store built with Laravel. Secure sign-in, real cart, real orders — fully demo-ready.') }}</p>
            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('shop.index') }}" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-6 py-3 text-white font-semibold hover:bg-indigo-500 shadow">{{ __('Browse shop') }}</a>
                @guest
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-6 py-3 text-gray-700 font-semibold hover:bg-gray-50">{{ __('Create account') }}</a>
                @endguest
            </div>
        </div>
        <div class="relative">
            <div class="aspect-square bg-gradient-to-br from-indigo-500 to-purple-600 rounded-3xl shadow-2xl flex items-center justify-center text-white text-8xl font-bold">
                🛍
            </div>
            <div class="absolute -top-3 -right-3 h-20 w-20 bg-amber-300 rounded-full -z-10"></div>
            <div class="absolute -bottom-4 -left-4 h-14 w-14 bg-pink-300 rounded-full -z-10"></div>
        </div>
    </div>
</section>

<!-- Categories -->
@if ($categories->count())
    <section id="categories" class="py-12 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Shop by category') }}</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                @foreach ($categories as $i => $cat)
                    @php $gradients = ['from-indigo-500 to-purple-500', 'from-pink-500 to-rose-500', 'from-amber-400 to-orange-500', 'from-emerald-500 to-teal-500', 'from-sky-500 to-blue-500', 'from-purple-500 to-fuchsia-500']; @endphp
                    <a href="{{ route('shop.index', ['category' => $cat->category]) }}"
                       class="group rounded-lg p-5 bg-gradient-to-br {{ $gradients[$i % count($gradients)] }} text-white hover:shadow-lg transition">
                        <div class="text-sm font-semibold">{{ $cat->category }}</div>
                        <div class="text-xs opacity-80 mt-1">{{ $cat->cnt }} {{ __('items') }}</div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

<!-- Featured products -->
<section class="py-12 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex items-end justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('Featured products') }}</h2>
                <p class="mt-1 text-sm text-gray-600">{{ __('Hand-picked favourites.') }}</p>
            </div>
            <a href="{{ route('shop.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">{{ __('Shop all') }} →</a>
        </div>

        @if ($featured->count())
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                @foreach ($featured as $product)
                    <a href="{{ auth()->check() ? route('shop.show', $product) : route('login') }}"
                       class="group bg-white rounded-lg shadow-sm hover:shadow-lg transition overflow-hidden border border-gray-100">
                        <div class="aspect-square bg-gray-100 overflow-hidden">
                            <img src="{{ $product->image_url }}" alt="" class="h-full w-full object-cover group-hover:scale-105 transition">
                        </div>
                        <div class="p-4">
                            @if ($product->category)<span class="text-xs font-medium text-indigo-600">{{ $product->category }}</span>@endif
                            <h3 class="text-sm font-semibold text-gray-900 group-hover:text-indigo-600 line-clamp-2">{{ $product->name }}</h3>
                            <div class="mt-2 text-lg font-bold text-gray-900">{{ $product->formatted_price }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg p-10 text-center text-gray-500">{{ __('No products yet. Admin — sign in to add the first one.') }}</div>
        @endif
    </div>
</section>

<!-- Features -->
<section id="features" class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 grid grid-cols-1 sm:grid-cols-3 gap-8 text-center">
        <div>
            <div class="mx-auto h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 mb-3">🚚</div>
            <h4 class="font-semibold text-gray-900">{{ __('Fast delivery') }}</h4>
            <p class="mt-1 text-sm text-gray-600">{{ __('Orders are queued and tracked from the admin dashboard.') }}</p>
        </div>
        <div>
            <div class="mx-auto h-12 w-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 mb-3">🔒</div>
            <h4 class="font-semibold text-gray-900">{{ __('Secure sign-in') }}</h4>
            <p class="mt-1 text-sm text-gray-600">{{ __('Google OAuth + email 6-digit verification code.') }}</p>
        </div>
        <div>
            <div class="mx-auto h-12 w-12 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 mb-3">💳</div>
            <h4 class="font-semibold text-gray-900">{{ __('Real cart, real orders') }}</h4>
            <p class="mt-1 text-sm text-gray-600">{{ __('Session-based cart, order records with status tracking.') }}</p>
        </div>
    </div>
</section>

<footer class="bg-gray-900 text-gray-400 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm">
        <div>© {{ date('Y') }} {{ config('app.name') }} — {{ __('All rights reserved') }}</div>
        <div class="flex items-center gap-4">
            <a href="{{ route('shop.index') }}" class="hover:text-white">{{ __('Shop') }}</a>
            <a href="{{ route('login') }}" class="hover:text-white">{{ __('Sign in') }}</a>
            <a href="{{ route('register') }}" class="hover:text-white">{{ __('Register') }}</a>
        </div>
    </div>
</footer>

</body>
</html>
