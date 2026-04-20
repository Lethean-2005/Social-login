<x-dashboard-layout title="Manage Products">
    <div class="max-w-6xl mx-auto space-y-6">

        @if (session('status'))
            <div class="rounded-md border border-green-200 bg-green-50 p-3 text-sm text-green-800">{{ session('status') }}</div>
        @endif

        <div class="flex items-center justify-between">
            <h3 class="text-xl font-semibold text-gray-900">{{ __('Products') }}</h3>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                {{ __('New product') }}
            </a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium">{{ __('Product') }}</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('Category') }}</th>
                            <th class="px-6 py-3 text-right font-medium">{{ __('Price') }}</th>
                            <th class="px-6 py-3 text-right font-medium">{{ __('Stock') }}</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('Status') }}</th>
                            <th class="px-6 py-3 text-right font-medium">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($products as $p)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $p->image_url }}" class="h-10 w-10 rounded object-cover" alt="">
                                        <div class="min-w-0">
                                            <div class="font-medium text-gray-900 truncate">{{ $p->name }}</div>
                                            <div class="text-xs text-gray-500 truncate max-w-xs">{{ Str::limit($p->description, 60) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3">
                                    @if ($p->category)
                                        <span class="inline-flex rounded-full bg-indigo-50 px-2 py-0.5 text-xs font-medium text-indigo-700">{{ $p->category }}</span>
                                    @else<span class="text-gray-400">—</span>@endif
                                </td>
                                <td class="px-6 py-3 text-right font-medium text-gray-900">{{ $p->formatted_price }}</td>
                                <td class="px-6 py-3 text-right {{ $p->stock > 0 ? 'text-gray-700' : 'text-red-600 font-medium' }}">{{ $p->stock }}</td>
                                <td class="px-6 py-3">
                                    @if ($p->is_published)
                                        <span class="inline-flex rounded-full bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700">{{ __('Live') }}</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700">{{ __('Hidden') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-right space-x-2 whitespace-nowrap">
                                    <a href="{{ route('admin.products.edit', $p) }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">{{ __('Edit') }}</a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $p) }}" class="inline" onsubmit="return confirm('Delete this product?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-800">{{ __('Delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                {{ __('No products yet.') }}
                                <a href="{{ route('admin.products.create') }}" class="text-indigo-600 underline">{{ __('Add your first one') }}</a>.
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($products->hasPages())<div class="px-6 py-3 border-t border-gray-200">{{ $products->links() }}</div>@endif
        </div>
    </div>
</x-dashboard-layout>
