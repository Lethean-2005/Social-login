<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        Review::updateOrCreate(
            ['user_id' => $request->user()->id, 'product_id' => $product->id],
            ['rating' => $data['rating'], 'comment' => $data['comment'] ?? null],
        );

        return redirect()->route('shop.show', $product)->with('status', 'Thanks for your review!');
    }

    public function destroy(Request $request, Review $review): RedirectResponse
    {
        abort_unless($review->user_id === $request->user()->id || $request->user()->is_admin, 403);

        $slug = $review->product?->slug;
        $review->delete();

        return redirect()->route('shop.show', $slug)->with('status', 'Review removed.');
    }
}
