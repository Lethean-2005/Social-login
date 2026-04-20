<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if (Cart::isEmpty()) {
            return redirect()->route('shop.index')->with('status', 'Your cart is empty.');
        }

        return view('checkout.create', [
            'items' => Cart::all(),
            'subtotal' => Cart::formattedSubtotal(),
            'user' => $request->user(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (Cart::isEmpty()) {
            return redirect()->route('shop.index');
        }

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_email' => ['required', 'email', 'max:200'],
            'customer_phone' => ['nullable', 'string', 'max:40'],
            'shipping_address' => ['required', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $order = DB::transaction(function () use ($data, $request) {
            $subtotal = Cart::subtotalCents();

            $order = Order::create([
                'user_id' => $request->user()->id,
                'status' => 'pending',
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'] ?? null,
                'shipping_address' => $data['shipping_address'],
                'notes' => $data['notes'] ?? null,
                'subtotal_cents' => $subtotal,
                'total_cents' => $subtotal,
            ]);

            foreach (Cart::all() as $row) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $row['product_id'] ?? null,
                    'product_name' => $row['name'],
                    'unit_price_cents' => $row['price_cents'],
                    'quantity' => $row['quantity'],
                    'line_total_cents' => $row['price_cents'] * $row['quantity'],
                ]);

                if (! empty($row['product_id'])) {
                    Product::where('id', $row['product_id'])
                        ->where('stock', '>=', $row['quantity'])
                        ->decrement('stock', $row['quantity']);
                }
            }

            return $order;
        });

        Cart::clear();

        return redirect()->route('orders.show', $order)->with('status', 'Thanks! Your order has been placed.');
    }
}
