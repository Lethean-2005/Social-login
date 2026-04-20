<x-dashboard-layout title="Shop">
    <div class="max-w-6xl mx-auto space-y-6">

        <div>
            <h2 class="text-2xl font-semibold text-gray-900">{{ __('Shop all products') }}</h2>
            <p class="mt-1 text-sm text-gray-600">{{ __('Find what you need.') }}</p>
        </div>

        <form method="GET" class="bg-white rounded-lg shadow p-4 flex flex-col sm:flex-row gap-3 items-end">
            <div class="flex-1">
                <label class="block text-xs uppercase text-gray-500 mb-1">{{ __('Search') }}</label>
                <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="{{ __('e.g. headphones') }}"
                       class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="flex-1">
                <label class="block text-xs uppercase text-gray-500 mb-1">{{ __('Category') }}</label>
                <select name="category" class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">{{ __('All categories') }}</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" @selected($filters['category'] === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">{{ __('Filter') }}</button>
                <a href="{{ route('shop.index') }}" class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">{{ __('Reset') }}</a>
            </div>
        </form>

        @if ($products->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($products as $product)
                    <a href="{{ route('shop.show', $product) }}" class="group bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden flex flex-col">
                        <div class="aspect-square bg-gray-100 overflow-hidden">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                 class="h-full w-full object-cover group-hover:scale-105 transition">
                        </div>
                        <div class="p-4 flex-1 flex flex-col">
                            @if ($product->category)
                                <span class="self-start inline-flex rounded-full bg-indigo-50 px-2 py-0.5 text-xs font-medium text-indigo-700 mb-1">{{ $product->category }}</span>
                            @endif
                            <h3 class="text-sm font-semibold text-gray-900 group-hover:text-indigo-600 line-clamp-2">{{ $product->name }}</h3>
                            <div class="mt-auto pt-3 flex items-center justify-between">
                                <span class="text-lg font-bold text-gray-900">{{ $product->formatted_price }}</span>
                                @if ($product->stock <= 0)
                                    <span class="text-xs text-red-600 font-medium">{{ __('Out of stock') }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            @if ($products->hasPages())<div>{{ $products->links() }}</div>@endif
        @else
            <div class="bg-white rounded-lg shadow p-10 text-center text-gray-500">
                {{ __('No products match your filter yet.') }}
            </div>
        @endif
    </div>
</x-dashboard-layout>
