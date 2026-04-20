<x-dashboard-layout title="Cart">
    <div class="max-w-4xl mx-auto space-y-6">

        @if (session('status'))
            <div class="rounded-md border border-green-200 bg-green-50 p-3 text-sm text-green-800">{{ session('status') }}</div>
        @endif

        <h2 class="text-2xl font-semibold text-gray-900">{{ __('Your cart') }}</h2>

        @if ($count > 0)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <ul class="divide-y divide-gray-100">
                    @foreach ($items as $pid => $item)
                        <li class="p-4 sm:p-6 flex flex-col sm:flex-row sm:items-center gap-4">
                            <img src="{{ $item['image_url'] }}" alt="" class="h-20 w-20 rounded object-cover shrink-0">
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('shop.show', ['product' => $item['slug']]) }}" class="text-sm font-semibold text-gray-900 hover:text-indigo-600">{{ $item['name'] }}</a>
                                <div class="text-xs text-gray-500 mt-0.5">${{ number_format($item['price_cents'] / 100, 2) }} {{ __('each') }}</div>
                            </div>
                            <form method="POST" action="{{ route('cart.update', $pid) }}" class="flex items-center gap-2">
                                @csrf @method('PATCH')
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0"
                                       class="w-20 border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       onchange="this.form.submit()">
                                <noscript><button type="submit" class="text-xs text-indigo-600 underline">Update</button></noscript>
                            </form>
                            <div class="text-base font-semibold text-gray-900 min-w-[90px] text-right">
                                ${{ number_format(($item['price_cents'] * $item['quantity']) / 100, 2) }}
                            </div>
                            <form method="POST" action="{{ route('cart.remove', $pid) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-600 hover:text-red-800 underline">{{ __('Remove') }}</button>
                            </form>
                        </li>
                    @endforeach
                </ul>

                <div class="border-t border-gray-200 p-6 bg-gray-50 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <form method="POST" action="{{ route('cart.clear') }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-gray-600 hover:text-gray-900 underline">{{ __('Clear cart') }}</button>
                    </form>
                    <div class="flex items-center gap-6">
                        <div class="text-right">
                            <div class="text-xs uppercase text-gray-500">{{ __('Subtotal') }}</div>
                            <div class="text-2xl font-bold text-gray-900">{{ $subtotal }}</div>
                        </div>
                        <a href="{{ route('checkout.create') }}" class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500">
                            {{ __('Checkout') }}
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-10 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272"/></svg>
                <h3 class="mt-3 text-base font-semibold text-gray-900">{{ __('Your cart is empty') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('Add something from the shop to get started.') }}</p>
                <a href="{{ route('shop.index') }}" class="mt-4 inline-flex rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">{{ __('Browse shop') }}</a>
            </div>
        @endif
    </div>
</x-dashboard-layout>
