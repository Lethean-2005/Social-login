<x-dashboard-layout title="All Orders">
    <div class="max-w-6xl mx-auto space-y-6">

        @if (session('status'))
            <div class="rounded-md border border-green-200 bg-green-50 p-3 text-sm text-green-800">{{ session('status') }}</div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg shadow p-4"><div class="text-xs uppercase text-gray-500">{{ __('Total orders') }}</div><div class="mt-1 text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div></div>
            <div class="bg-white rounded-lg shadow p-4"><div class="text-xs uppercase text-gray-500">{{ __('Pending') }}</div><div class="mt-1 text-2xl font-bold text-amber-600">{{ $stats['pending'] }}</div></div>
            <div class="bg-white rounded-lg shadow p-4"><div class="text-xs uppercase text-gray-500">{{ __('Revenue') }}</div><div class="mt-1 text-2xl font-bold text-green-600">${{ number_format($stats['revenue'] / 100, 2) }}</div></div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-900">{{ __('Recent orders') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium">#</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('Customer') }}</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('Items') }}</th>
                            <th class="px-6 py-3 text-right font-medium">{{ __('Total') }}</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('Status') }}</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('Placed') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($orders as $o)
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('admin.orders.show', $o) }}'">
                                <td class="px-6 py-3 font-mono text-xs text-gray-700">#{{ $o->id }}</td>
                                <td class="px-6 py-3">
                                    <div class="font-medium text-gray-900">{{ $o->customer_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $o->customer_email }}</div>
                                </td>
                                <td class="px-6 py-3 text-gray-700">{{ $o->items->count() }} × {{ __('items') }}</td>
                                <td class="px-6 py-3 text-right font-semibold">{{ $o->formatted_total }}</td>
                                <td class="px-6 py-3"><span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $o->status_color }}">{{ ucfirst($o->status) }}</span></td>
                                <td class="px-6 py-3 text-gray-600 text-xs">{{ $o->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-10 text-center text-gray-500">{{ __('No orders yet.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($orders->hasPages())<div class="px-6 py-3 border-t border-gray-200">{{ $orders->links() }}</div>@endif
        </div>
    </div>
</x-dashboard-layout>
