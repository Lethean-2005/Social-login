@php
    $user = auth()->user();
    $recentOrders = \App\Models\Order::where('user_id', $user->id)->latest()->take(5)->get();
    $totalSpentCents = \App\Models\Order::where('user_id', $user->id)->whereIn('status', ['paid','shipped','completed'])->sum('total_cents');
    $totalProducts = \App\Models\Product::published()->count();
    $cartCount = \App\Services\Cart::count();
@endphp

<x-dashboard-layout title="Dashboard">

    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg shadow p-6 flex flex-col sm:flex-row items-start sm:items-center gap-4">
        @if ($user->avatar)
            <img src="{{ $user->avatar }}" referrerpolicy="no-referrer" alt="avatar" class="h-16 w-16 rounded-full ring-2 ring-white/40">
        @else
            <div class="h-16 w-16 rounded-full bg-white/20 flex items-center justify-center text-2xl font-semibold">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        @endif
        <div class="flex-1">
            <h2 class="text-xl sm:text-2xl font-semibold">{{ __('Welcome,') }} {{ $user->name }} 🛍️</h2>
            <p class="text-sm text-indigo-100 mt-1">
                @if ($user->is_admin)
                    {{ __('Manage products and orders from the Admin section.') }}
                @else
                    {{ __('Browse the shop, add to cart, and track your orders.') }}
                @endif
            </p>
        </div>
        <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 rounded-md bg-white/90 px-3 py-2 text-sm font-medium text-indigo-700 hover:bg-white">
            {{ __('Shop now') }} →
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
        <div class="bg-white rounded-lg shadow p-5">
            <div class="text-xs uppercase text-gray-500">{{ __('Products in shop') }}</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">{{ $totalProducts }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-5">
            <div class="text-xs uppercase text-gray-500">{{ __('In your cart') }}</div>
            <div class="mt-2 text-3xl font-bold text-indigo-600">{{ $cartCount }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-5">
            <div class="text-xs uppercase text-gray-500">{{ __('Your orders') }}</div>
            <div class="mt-2 text-3xl font-bold text-amber-600">{{ $recentOrders->count() }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-5">
            <div class="text-xs uppercase text-gray-500">{{ __('Total spent') }}</div>
            <div class="mt-2 text-3xl font-bold text-green-600">${{ number_format($totalSpentCents / 100, 2) }}</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow mt-6">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-base font-semibold text-gray-900">{{ __('Your recent orders') }}</h3>
            <a href="{{ route('orders.index') }}" class="text-xs text-indigo-600 hover:underline">{{ __('See all') }}</a>
        </div>
        <ul class="divide-y divide-gray-100">
            @forelse ($recentOrders as $order)
                <li>
                    <a href="{{ route('orders.show', $order) }}" class="flex items-center justify-between p-5 hover:bg-gray-50">
                        <div>
                            <div class="font-semibold text-gray-900">#{{ $order->id }}</div>
                            <div class="text-xs text-gray-500">{{ $order->created_at->diffForHumans() }} · {{ $order->items->count() }} {{ __('items') }}</div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $order->status_color }}">{{ ucfirst($order->status) }}</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $order->formatted_total }}</span>
                        </div>
                    </a>
                </li>
            @empty
                <li class="p-8 text-center text-sm text-gray-500">
                    {{ __('No orders yet.') }}
                    <a href="{{ route('shop.index') }}" class="text-indigo-600 underline">{{ __('Start shopping') }}</a>.
                </li>
            @endforelse
        </ul>
    </div>

    @if ($user->is_admin)
        <div class="mt-6 bg-indigo-50 border border-indigo-100 rounded-lg p-5 flex items-center justify-between">
            <div>
                <h4 class="font-semibold text-indigo-900">{{ __('Add a new product') }}</h4>
                <p class="text-sm text-indigo-800 mt-1">{{ __('Upload an image, set a price, publish to the shop.') }}</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">{{ __('New product') }}</a>
        </div>
    @endif
</x-dashboard-layout>
