<x-dashboard-layout :title="'Order #'.$order->id">
    <div class="max-w-4xl mx-auto space-y-6">

        @if (session('status'))
            <div class="rounded-md border border-green-200 bg-green-50 p-3 text-sm text-green-800">{{ session('status') }}</div>
        @endif

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900">{{ __('Order') }} #{{ $order->id }}</h2>
                <p class="text-sm text-gray-600 mt-1">{{ __('Placed on') }} {{ $order->created_at->format('M j, Y · g:i A') }}</p>
            </div>
            <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium {{ $order->status_color }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="divide-y divide-gray-100">
                @foreach ($order->items as $item)
                    <div class="p-4 sm:p-5 flex items-center gap-4">
                        <div class="h-16 w-16 bg-gray-100 rounded overflow-hidden shrink-0">
                            @if ($item->product?->image_path)
                                <img src="{{ $item->product->image_url }}" alt="" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full flex items-center justify-center text-gray-400 text-xs">—</div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-gray-900 truncate">{{ $item->product_name }}</div>
                            <div class="text-xs text-gray-500">{{ $item->formatted_unit_price }} × {{ $item->quantity }}</div>
                        </div>
                        <div class="text-sm font-semibold text-gray-900">{{ $item->formatted_line_total }}</div>
                    </div>
                @endforeach
            </div>

            <div class="p-5 bg-gray-50 border-t border-gray-200 space-y-1">
                <div class="flex justify-between text-sm"><span class="text-gray-600">{{ __('Subtotal') }}</span><span>{{ $order->formatted_subtotal }}</span></div>
                <div class="flex justify-between text-base font-bold pt-2 border-t border-gray-200"><span>{{ __('Total') }}</span><span>{{ $order->formatted_total }}</span></div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-white rounded-lg shadow p-5">
                <h4 class="text-sm font-semibold text-gray-900 mb-2">{{ __('Shipping to') }}</h4>
                <div class="text-sm text-gray-700">{{ $order->customer_name }}</div>
                <div class="text-xs text-gray-500">{{ $order->customer_email }}</div>
                @if ($order->customer_phone)<div class="text-xs text-gray-500">{{ $order->customer_phone }}</div>@endif
                <div class="mt-2 text-sm text-gray-700 whitespace-pre-line">{{ $order->shipping_address }}</div>
            </div>
            @if ($order->notes)
                <div class="bg-white rounded-lg shadow p-5">
                    <h4 class="text-sm font-semibold text-gray-900 mb-2">{{ __('Notes') }}</h4>
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $order->notes }}</p>
                </div>
            @endif
        </div>

        @if (auth()->user()->is_admin && request()->routeIs('admin.orders.show') === false)
            <!-- user view, no status control -->
        @endif

        @if (auth()->user()->is_admin)
            <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="bg-white rounded-lg shadow p-5 flex items-end gap-3">
                @csrf @method('PATCH')
                <div class="flex-1">
                    <label class="block text-xs uppercase text-gray-500 mb-1">{{ __('Admin: change status') }}</label>
                    <select name="status" class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach (\App\Models\Order::STATUSES as $s)
                            <option value="{{ $s }}" @selected($order->status === $s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">{{ __('Update') }}</button>
            </form>
        @endif
    </div>
</x-dashboard-layout>
