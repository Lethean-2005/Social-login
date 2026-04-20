<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        return view('cart.index', [
            'items' => Cart::all(),
            'subtotal' => Cart::formattedSubtotal(),
            'subtotalCents' => Cart::subtotalCents(),
            'count' => Cart::count(),
        ]);
    }

    public function add(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_published && $product->stock > 0, 404);

        $qty = max(1, (int) $request->input('quantity', 1));
        Cart::add($product, $qty);

        return redirect()->route('cart.index')->with('status', "Added \"{$product->name}\" to cart.");
    }

    public function update(Request $request, int $productId): RedirectResponse
    {
        Cart::update($productId, (int) $request->input('quantity', 1));
        return redirect()->route('cart.index');
    }

    public function remove(int $productId): RedirectResponse
    {
        Cart::remove($productId);
        return redirect()->route('cart.index')->with('status', 'Item removed.');
    }

    public function clear(): RedirectResponse
    {
        Cart::clear();
        return redirect()->route('cart.index');
    }
}
