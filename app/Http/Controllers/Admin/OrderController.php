<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        return view('admin.orders.index', [
            'orders' => Order::with(['user', 'items'])->latest()->paginate(20),
            'stats' => [
                'total' => Order::count(),
                'pending' => Order::where('status', 'pending')->count(),
                'revenue' => Order::whereIn('status', ['paid', 'shipped', 'completed'])->sum('total_cents'),
            ],
        ]);
    }

    public function show(Order $order): View
    {
        $order->load('items.product', 'user');
        return view('admin.orders.show', ['order' => $order]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', Order::STATUSES)],
        ]);

        $order->update(['status' => $data['status']]);

        return back()->with('status', "Order #{$order->id} marked as {$data['status']}.");
    }
}
