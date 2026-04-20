<x-dashboard-layout title="My Orders">
    <div class="max-w-4xl mx-auto space-y-6">
        <h2 class="text-2xl font-semibold text-gray-900">{{ __('My orders') }}</h2>

        @if ($orders->count())
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <ul class="divide-y divide-gray-100">
                    @foreach ($orders as $order)
                        <li>
                            <a href="{{ route('orders.show', $order) }}" class="block p-5 hover:bg-gray-50">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="font-semibold text-gray-900">{{ __('Order') }} #{{ $order->id }}</span>
                                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $order->status_color }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">{{ $order->created_at->format('M j, Y') }} · {{ $order->items->count() }} {{ __('items') }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-bold text-gray-900">{{ $order->formatted_total }}</div>
                                        <div class="text-xs text-indigo-600 group-hover:underline">{{ __('View details') }} →</div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
                @if ($orders->hasPages())<div class="px-6 py-3 border-t border-gray-200">{{ $orders->links() }}</div>@endif
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-10 text-center">
                <p class="text-gray-600">{{ __('You haven\'t placed any orders yet.') }}</p>
                <a href="{{ route('shop.index') }}" class="mt-4 inline-flex rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">{{ __('Browse shop') }}</a>
            </div>
        @endif
    </div>
</x-dashboard-layout>
