@php $isEdit = $lesson->exists; @endphp

<x-dashboard-layout :title="$isEdit ? 'Edit Lesson' : 'New Lesson'">
    <div class="max-w-3xl mx-auto">
        <form method="POST"
              action="{{ $isEdit ? route('admin.lessons.update', $lesson) : route('admin.lessons.store') }}"
              class="bg-white rounded-lg shadow p-6 space-y-5">
            @csrf
            @if ($isEdit) @method('PATCH') @endif

            <div>
                <x-input-label for="title" :value="__('Title')" />
                <x-text-input id="title" name="title" class="block mt-1 w-full"
                              :value="old('title', $lesson->title)" required autofocus maxlength="200" />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="category" :value="__('Category (optional)')" />
                <x-text-input id="category" name="category" class="block mt-1 w-full"
                              :value="old('category', $lesson->category)" maxlength="80"
                              placeholder="e.g. Laravel, JavaScript, Design" />
                <x-input-error :messages="$errors->get('category')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="excerpt" :value="__('Short summary (optional)')" />
                <x-text-input id="excerpt" name="excerpt" class="block mt-1 w-full"
                              :value="old('excerpt', $lesson->excerpt)" maxlength="255"
                              placeholder="One line shown on cards" />
                <x-input-error :messages="$errors->get('excerpt')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="body" :value="__('Lesson body (Markdown supported)')" />
                <textarea id="body" name="body" rows="14" required
                          class="block mt-1 w-full border-gray-300 rounded-md shadow-sm font-mono text-sm focus:border-indigo-500 focus:ring-indigo-500"
                          placeholder="# Heading&#10;&#10;Write your lesson in Markdown. Headings, **bold**, lists, code blocks, links…">{{ old('body', $lesson->body) }}</textarea>
                <p class="mt-1 text-xs text-gray-500">{{ __('Tip: use # for headings, `code` for inline code, ``` for code blocks.') }}</p>
                <x-input-error :messages="$errors->get('body')" class="mt-2" />
            </div>

            <label class="inline-flex items-center gap-2 cursor-pointer">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1"
                       @checked(old('is_published', $lesson->is_published))
                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <span class="text-sm text-gray-700">{{ __('Publish this lesson (students can see it)') }}</span>
            </label>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <a href="{{ route('admin.lessons.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                    {{ __('Cancel') }}
                </a>
                <x-primary-button>
                    {{ $isEdit ? __('Save changes') : __('Create lesson') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-dashboard-layout>
