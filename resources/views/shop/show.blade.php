<x-dashboard-layout :title="$product->name">
    <div class="max-w-5xl mx-auto space-y-8">

        <nav class="text-xs text-gray-500">
            <a href="{{ route('shop.index') }}" class="hover:text-indigo-600 underline">{{ __('Shop') }}</a>
            <span class="mx-1">›</span>
            @if ($product->category)
                <a href="{{ route('shop.index', ['category' => $product->category]) }}" class="hover:text-indigo-600">{{ $product->category }}</a>
                <span class="mx-1">›</span>
            @endif
            <span class="text-gray-700">{{ $product->name }}</span>
        </nav>

        <div class="bg-white rounded-lg shadow overflow-hidden grid grid-cols-1 md:grid-cols-2 gap-0">
            <div class="aspect-square bg-gray-100 overflow-hidden">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
            </div>
            <div class="p-8 flex flex-col">
                @if ($product->category)
                    <span class="self-start inline-flex rounded-full bg-indigo-50 px-2 py-0.5 text-xs font-medium text-indigo-700 mb-2">{{ $product->category }}</span>
                @endif
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $product->name }}</h1>

                @if ($product->review_count > 0)
                    <a href="#reviews" class="mt-2 inline-flex items-center gap-2 text-sm text-gray-700 hover:text-indigo-600">
                        <x-stars :rating="$product->average_rating" size="h-5 w-5" />
                        <span class="font-medium">{{ $product->average_rating }}</span>
                        <span class="text-gray-500">({{ $product->review_count }} {{ Str::plural('review', $product->review_count) }})</span>
                    </a>
                @else
                    <a href="#reviews" class="mt-2 inline-flex items-center gap-2 text-sm text-indigo-600 hover:underline">
                        {{ __('Be the first to review') }}
                    </a>
                @endif

                <div class="mt-3 text-3xl font-bold text-gray-900">{{ $product->formatted_price }}</div>

                <div class="mt-4 text-sm {{ $product->stock > 0 ? 'text-green-700' : 'text-red-600' }}">
                    @if ($product->stock > 0)
                        <span class="inline-flex items-center gap-1">
                            <span class="h-2 w-2 rounded-full bg-green-500"></span>
                            {{ __('In stock') }} ({{ $product->stock }} {{ __('available') }})
                        </span>
                    @else
                        {{ __('Out of stock') }}
                    @endif
                </div>

                @if ($product->description)
                    <p class="mt-5 text-sm text-gray-700 whitespace-pre-line">{{ $product->description }}</p>
                @endif

                @if ($product->stock > 0)
                    <form method="POST" action="{{ route('cart.add', $product) }}" class="mt-6 flex items-end gap-3">
                        @csrf
                        <div>
                            <label for="quantity" class="block text-xs uppercase text-gray-500 mb-1">{{ __('Qty') }}</label>
                            <input id="quantity" type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                                   class="block w-20 border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                            {{ __('Add to cart') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Reviews -->
        <section id="reviews" class="bg-white rounded-lg shadow p-8">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-xl font-semibold text-gray-900">{{ __('Customer reviews') }}</h2>
                @if ($product->review_count > 0)
                    <div class="flex items-center gap-2">
                        <x-stars :rating="$product->average_rating" size="h-5 w-5" />
                        <span class="font-semibold text-gray-900">{{ $product->average_rating }}</span>
                        <span class="text-sm text-gray-500">/ 5</span>
                    </div>
                @endif
            </div>

            @if (session('status'))
                <div class="mb-4 rounded-md border border-green-200 bg-green-50 p-3 text-sm text-green-800">{{ session('status') }}</div>
            @endif

            <!-- Review form -->
            <div class="border border-gray-200 rounded-lg p-5 mb-6 bg-gray-50">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">
                    {{ $myReview ? __('Update your review') : __('Write a review') }}
                </h3>
                <form method="POST" action="{{ route('reviews.store', $product) }}" x-data="{ rating: {{ $myReview->rating ?? 5 }}, hover: 0 }">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-xs uppercase text-gray-500 mb-1">{{ __('Your rating') }}</label>
                        <div class="flex items-center gap-1" @mouseleave="hover = 0">
                            <template x-for="i in 5">
                                <button type="button"
                                        @click="rating = i"
                                        @mouseenter="hover = i"
                                        class="focus:outline-none">
                                    <svg class="h-7 w-7 transition"
                                         :class="(hover || rating) >= i ? 'text-amber-400' : 'text-gray-300'"
                                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.955c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.175 0l-3.37 2.448c-.784.57-1.838-.197-1.539-1.118l1.287-3.955a1 1 0 00-.364-1.118L2.05 9.382c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.955z"/>
                                    </svg>
                                </button>
                            </template>
                            <span class="ml-2 text-sm text-gray-600" x-text="rating + ' / 5'"></span>
                        </div>
                        <input type="hidden" name="rating" :value="rating">
                        <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                    </div>
                    <div>
                        <label for="comment" class="block text-xs uppercase text-gray-500 mb-1">{{ __('Your review (optional)') }}</label>
                        <textarea id="comment" name="comment" rows="3"
                                  class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="What did you think?">{{ old('comment', $myReview->comment ?? '') }}</textarea>
                        <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        @if ($myReview)
                            <form method="POST" action="{{ route('reviews.destroy', $myReview) }}" class="inline"
                                  onsubmit="return confirm('Delete your review?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-600 hover:text-red-800 underline">{{ __('Delete my review') }}</button>
                            </form>
                        @else<span></span>@endif
                        <x-primary-button>{{ $myReview ? __('Update review') : __('Submit review') }}</x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Reviews list -->
            @if ($product->reviews->count())
                <ul class="space-y-5">
                    @foreach ($product->reviews as $r)
                        <li class="flex gap-4">
                            @if ($r->user?->avatar)
                                <img src="{{ $r->user->avatar }}" referrerpolicy="no-referrer" class="h-10 w-10 rounded-full shrink-0" alt="">
                            @else
                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-semibold shrink-0">
                                    {{ strtoupper(substr($r->user?->name ?? '?', 0, 1)) }}
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-medium text-gray-900">{{ $r->user?->name ?? __('Customer') }}</span>
                                    <x-stars :rating="$r->rating" />
                                    <span class="text-xs text-gray-500">· {{ $r->created_at->diffForHumans() }}</span>
                                </div>
                                @if ($r->comment)
                                    <p class="mt-1 text-sm text-gray-700 whitespace-pre-line">{{ $r->comment }}</p>
                                @endif
                                @if (auth()->id() === $r->user_id || auth()->user()?->is_admin)
                                    <form method="POST" action="{{ route('reviews.destroy', $r) }}" class="inline mt-1"
                                          onsubmit="return confirm('Delete this review?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:text-red-800 underline">{{ __('Delete') }}</button>
                                    </form>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-500">{{ __('No reviews yet. Be the first!') }}</p>
            @endif
        </section>

        @if ($related->count())
            <section>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ __('Related products') }}</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach ($related as $r)
                        <a href="{{ route('shop.show', $r) }}" class="group bg-white rounded-lg shadow hover:shadow-md transition overflow-hidden">
                            <div class="aspect-square bg-gray-100">
                                <img src="{{ $r->image_url }}" class="h-full w-full object-cover" alt="">
                            </div>
                            <div class="p-3">
                                <h4 class="text-xs font-semibold text-gray-900 group-hover:text-indigo-600 line-clamp-2">{{ $r->name }}</h4>
                                <p class="text-sm font-bold mt-1">{{ $r->formatted_price }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-dashboard-layout>
