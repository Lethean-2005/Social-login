<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('admin.products.index', [
            'products' => Product::latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.form', ['product' => new Product()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Product::makeUniqueSlug($data['name']);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('product-images', 'public');
        }

        $product = Product::create($data);

        return redirect()->route('admin.products.index')->with('status', "Created \"{$product->name}\".");
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', ['product' => $product]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validated($request);

        if ($data['name'] !== $product->name) {
            $data['slug'] = Product::makeUniqueSlug($data['name'], $product->id);
        }

        if ($request->boolean('remove_image') && $product->image_path) {
            Storage::disk('public')->delete($product->image_path);
            $data['image_path'] = null;
        }

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $request->file('image')->store('product-images', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('status', "Updated \"{$product->name}\".");
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $name = $product->name;
        $product->delete();

        return redirect()->route('admin.products.index')->with('status', "Deleted \"{$name}\".");
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'category' => ['nullable', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:5120'], // 5 MB
            'is_published' => ['sometimes', 'boolean'],
            'remove_image' => ['sometimes', 'boolean'],
        ]);

        return [
            'name' => $data['name'],
            'category' => $data['category'] ?? null,
            'description' => $data['description'] ?? null,
            'price_cents' => (int) round($data['price'] * 100),
            'stock' => (int) $data['stock'],
            'is_published' => (bool) $request->boolean('is_published', true),
        ];
    }
}
