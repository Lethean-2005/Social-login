<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::published()->latest();

        if ($c = $request->string('category')->toString()) {
            $query->where('category', $c);
        }

        if ($q = $request->string('q')->toString()) {
            $query->where('name', 'like', "%{$q}%");
        }

        return view('shop.index', [
            'products' => $query->paginate(12)->withQueryString(),
            'categories' => Product::published()->whereNotNull('category')
                ->select('category')->distinct()->orderBy('category')->pluck('category'),
            'filters' => ['category' => $c ?? '', 'q' => $q ?? ''],
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_published, 404);

        return view('shop.show', [
            'product' => $product,
            'related' => Product::published()
                ->where('id', '!=', $product->id)
                ->when($product->category, fn ($q) => $q->where('category', $product->category))
                ->latest()->take(4)->get(),
        ]);
    }
}
