<x-dashboard-layout :title="$product->name">
    <div class="max-w-5xl mx-auto space-y-8">

        <nav class="text-xs text-gray-500">
            <a href="{{ route('shop.index') }}" class="hover:text-indigo-600 underline">{{ __('Shop') }}</a>
            <span class="mx-1">›</span>
            @if ($product->category)
                <a href="{{ route('shop.index', ['category' => $product->category]) }}" class="hover:text-indigo-600">{{ $product->category }}</a>
                <span class="mx-1">›</span>
            @endif
            <span class="text-gray-700">{{ $product->name }}</span>
        </nav>

        <div class="bg-white rounded-lg shadow overflow-hidden grid grid-cols-1 md:grid-cols-2 gap-0">
            <div class="aspect-square bg-gray-100 overflow-hidden">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
            </div>
            <div class="p-8 flex flex-col">
                @if ($product->category)
                    <span class="self-start inline-flex rounded-full bg-indigo-50 px-2 py-0.5 text-xs font-medium text-indigo-700 mb-2">{{ $product->category }}</span>
                @endif
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $product->name }}</h1>

                <div class="mt-3 text-3xl font-bold text-gray-900">{{ $product->formatted_price }}</div>

                <div class="mt-4 text-sm {{ $product->stock > 0 ? 'text-green-700' : 'text-red-600' }}">
                    @if ($product->stock > 0)
                        <span class="inline-flex items-center gap-1">
                            <span class="h-2 w-2 rounded-full bg-green-500"></span>
                            {{ __('In stock') }} ({{ $product->stock }} {{ __('available') }})
                        </span>
                    @else
                        {{ __('Out of stock') }}
                    @endif
                </div>

                @if ($product->description)
                    <p class="mt-5 text-sm text-gray-700 whitespace-pre-line">{{ $product->description }}</p>
                @endif

                @if ($product->stock > 0)
                    <form method="POST" action="{{ route('cart.add', $product) }}" class="mt-6 flex items-end gap-3">
                        @csrf
                        <div>
                            <label for="quantity" class="block text-xs uppercase text-gray-500 mb-1">{{ __('Qty') }}</label>
                            <input id="quantity" type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                                   class="block w-20 border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                            {{ __('Add to cart') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if ($related->count())
            <section>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ __('Related products') }}</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach ($related as $r)
                        <a href="{{ route('shop.show', $r) }}" class="group bg-white rounded-lg shadow hover:shadow-md transition overflow-hidden">
                            <div class="aspect-square bg-gray-100">
                                <img src="{{ $r->image_url }}" class="h-full w-full object-cover" alt="">
                            </div>
                            <div class="p-3">
                                <h4 class="text-xs font-semibold text-gray-900 group-hover:text-indigo-600 line-clamp-2">{{ $r->name }}</h4>
                                <p class="text-sm font-bold mt-1">{{ $r->formatted_price }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-dashboard-layout>
