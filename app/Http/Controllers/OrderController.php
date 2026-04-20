<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        return view('orders.index', [
            'orders' => Order::where('user_id', $request->user()->id)
                ->with('items')
                ->latest()
                ->paginate(10),
        ]);
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id || $request->user()->is_admin, 403);
        $order->load('items.product');

        return view('orders.show', ['order' => $order]);
    }
}
