@php $isEdit = $product->exists; @endphp

<x-dashboard-layout :title="$isEdit ? 'Edit Product' : 'New Product'">
    <div class="max-w-3xl mx-auto">
        <form method="POST"
              action="{{ $isEdit ? route('admin.products.update', $product) : route('admin.products.store') }}"
              enctype="multipart/form-data"
              class="bg-white rounded-lg shadow p-6 space-y-5">
            @csrf
            @if ($isEdit) @method('PATCH') @endif

            <div>
                <x-input-label for="name" :value="__('Product name')" />
                <x-text-input id="name" name="name" class="block mt-1 w-full"
                              :value="old('name', $product->name)" required autofocus maxlength="200" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <x-input-label for="category" :value="__('Category')" />
                    <x-text-input id="category" name="category" class="block mt-1 w-full"
                                  :value="old('category', $product->category)" maxlength="80"
                                  placeholder="e.g. Electronics" />
                </div>
                <div>
                    <x-input-label for="price" :value="__('Price ($)')" />
                    <x-text-input id="price" name="price" type="number" step="0.01" min="0" class="block mt-1 w-full"
                                  :value="old('price', $isEdit ? number_format($product->price_cents / 100, 2, '.', '') : '')" required />
                    <x-input-error :messages="$errors->get('price')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="stock" :value="__('Stock')" />
                    <x-text-input id="stock" name="stock" type="number" min="0" class="block mt-1 w-full"
                                  :value="old('stock', $product->stock ?? 0)" required />
                    <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="description" :value="__('Description')" />
                <textarea id="description" name="description" rows="6"
                          class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                          placeholder="What is this product? Who is it for?">{{ old('description', $product->description) }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <!-- Image -->
            <div class="border-t border-gray-200 pt-5">
                <x-input-label :value="__('Product image')" />
                @if ($isEdit && $product->image_path)
                    <div class="mt-2 flex items-center gap-3">
                        <img src="{{ $product->image_url }}" alt="" class="h-20 w-20 rounded object-cover">
                        <label class="inline-flex items-center gap-1 text-xs text-red-600 cursor-pointer">
                            <input type="checkbox" name="remove_image" value="1" class="rounded border-gray-300 text-red-600">
                            {{ __('Remove current image') }}
                        </label>
                    </div>
                @endif
                <input type="file" name="image" accept="image/*"
                       class="mt-2 block w-full text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="mt-1 text-xs text-gray-500">{{ __('JPG, PNG, WebP. Max 5 MB.') }}</p>
                <x-input-error :messages="$errors->get('image')" class="mt-2" />
            </div>

            <label class="inline-flex items-center gap-2 cursor-pointer">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1"
                       @checked(old('is_published', $isEdit ? $product->is_published : true))
                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <span class="text-sm text-gray-700">{{ __('Show on the shop') }}</span>
            </label>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">{{ __('Cancel') }}</a>
                <x-primary-button>{{ $isEdit ? __('Save changes') : __('Create product') }}</x-primary-button>
            </div>
        </form>
    </div>
</x-dashboard-layout>
