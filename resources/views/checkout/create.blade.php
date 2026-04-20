<x-dashboard-layout title="Checkout">
    <div class="max-w-4xl mx-auto">
        <form method="POST" action="{{ route('checkout.store') }}" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @csrf

            <div class="lg:col-span-2 bg-white rounded-lg shadow p-6 space-y-4">
                <h2 class="text-xl font-semibold text-gray-900">{{ __('Shipping details') }}</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="customer_name" :value="__('Full name')" />
                        <x-text-input id="customer_name" name="customer_name" class="block mt-1 w-full"
                                      :value="old('customer_name', $user->name)" required />
                        <x-input-error :messages="$errors->get('customer_name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="customer_email" :value="__('Email')" />
                        <x-text-input id="customer_email" name="customer_email" type="email" class="block mt-1 w-full"
                                      :value="old('customer_email', $user->email)" required />
                        <x-input-error :messages="$errors->get('customer_email')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-input-label for="customer_phone" :value="__('Phone (optional)')" />
                    <x-text-input id="customer_phone" name="customer_phone" type="tel" class="block mt-1 w-full"
                                  :value="old('customer_phone')" />
                </div>

                <div>
                    <x-input-label for="shipping_address" :value="__('Shipping address')" />
                    <textarea id="shipping_address" name="shipping_address" rows="3" required
                              class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('shipping_address') }}</textarea>
                    <x-input-error :messages="$errors->get('shipping_address')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="notes" :value="__('Order notes (optional)')" />
                    <textarea id="notes" name="notes" rows="2"
                              class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                              placeholder="Delivery preferences, etc.">{{ old('notes') }}</textarea>
                </div>
            </div>

            <aside class="bg-white rounded-lg shadow p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-3">{{ __('Order summary') }}</h3>
                <ul class="divide-y divide-gray-100 text-sm">
                    @foreach ($items as $item)
                        <li class="py-2 flex justify-between">
                            <span class="text-gray-700 truncate pr-2">{{ $item['name'] }} × {{ $item['quantity'] }}</span>
                            <span class="text-gray-900 font-medium">${{ number_format($item['price_cents'] * $item['quantity'] / 100, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between text-base font-bold">
                    <span>{{ __('Total') }}</span>
                    <span>{{ $subtotal }}</span>
                </div>
                <p class="mt-2 text-xs text-gray-500">{{ __('Demo: no real payment. Your order will be created as "pending".') }}</p>

                <button type="submit" class="mt-4 w-full rounded-md bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500">
                    {{ __('Place order') }}
                </button>
                <a href="{{ route('cart.index') }}" class="mt-2 block text-center text-xs text-gray-600 hover:text-gray-900 underline">{{ __('Back to cart') }}</a>
            </aside>
        </form>
    </div>
</x-dashboard-layout>
