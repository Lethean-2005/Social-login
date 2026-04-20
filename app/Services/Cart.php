<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class Cart
{
    private const KEY = 'cart';

    public static function all(): array
    {
        return Session::get(self::KEY, []);
    }

    public static function add(Product $product, int $quantity = 1): void
    {
        $cart = self::all();
        $id = (string) $product->id;
        $existing = (int) ($cart[$id]['quantity'] ?? 0);
        $newQty = min($product->stock, max(1, $existing + $quantity));

        $cart[$id] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price_cents' => $product->price_cents,
            'image_url' => $product->image_url,
            'quantity' => $newQty,
        ];

        Session::put(self::KEY, $cart);
    }

    public static function update(int $productId, int $quantity): void
    {
        $cart = self::all();
        $id = (string) $productId;

        if (! isset($cart[$id])) return;

        if ($quantity <= 0) {
            unset($cart[$id]);
        } else {
            $product = Product::find($productId);
            $maxQty = $product ? $product->stock : $quantity;
            $cart[$id]['quantity'] = max(1, min($maxQty, $quantity));
        }

        Session::put(self::KEY, $cart);
    }

    public static function remove(int $productId): void
    {
        $cart = self::all();
        unset($cart[(string) $productId]);
        Session::put(self::KEY, $cart);
    }

    public static function clear(): void
    {
        Session::forget(self::KEY);
    }

    public static function count(): int
    {
        return array_sum(array_column(self::all(), 'quantity'));
    }

    public static function subtotalCents(): int
    {
        $sum = 0;
        foreach (self::all() as $row) {
            $sum += ((int) $row['price_cents']) * ((int) $row['quantity']);
        }
        return $sum;
    }

    public static function formattedSubtotal(): string
    {
        return '$'.number_format(self::subtotalCents() / 100, 2);
    }

    public static function isEmpty(): bool
    {
        return empty(self::all());
    }
}
