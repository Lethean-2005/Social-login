@php $isEdit = $lesson->exists; @endphp

<x-dashboard-layout :title="$isEdit ? 'Edit Lesson' : 'New Lesson'">
    <div class="max-w-3xl mx-auto">
        <form method="POST"
              action="{{ $isEdit ? route('admin.lessons.update', $lesson) : route('admin.lessons.store') }}"
              enctype="multipart/form-data"
              class="bg-white rounded-lg shadow p-6 space-y-5">
            @csrf
            @if ($isEdit) @method('PATCH') @endif

            <div>
                <x-input-label for="title" :value="__('Title')" />
                <x-text-input id="title" name="title" class="block mt-1 w-full"
                              :value="old('title', $lesson->title)" required autofocus maxlength="200" />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="category" :value="__('Category (optional)')" />
                    <x-text-input id="category" name="category" class="block mt-1 w-full"
                                  :value="old('category', $lesson->category)" maxlength="80"
                                  placeholder="e.g. Laravel, Python" />
                    <x-input-error :messages="$errors->get('category')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="video_url" :value="__('Video URL (optional)')" />
                    <x-text-input id="video_url" name="video_url" type="url" class="block mt-1 w-full"
                                  :value="old('video_url', $lesson->video_url)" maxlength="500"
                                  placeholder="https://youtube.com/watch?v=…" />
                    <p class="mt-1 text-xs text-gray-500">{{ __('YouTube, Vimeo, or any direct video URL.') }}</p>
                    <x-input-error :messages="$errors->get('video_url')" class="mt-2" />
                </div>
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
                <textarea id="body" name="body" rows="12" required
                          class="block mt-1 w-full border-gray-300 rounded-md shadow-sm font-mono text-sm focus:border-indigo-500 focus:ring-indigo-500"
                          placeholder="# Heading&#10;&#10;Write your lesson in Markdown…">{{ old('body', $lesson->body) }}</textarea>
                <p class="mt-1 text-xs text-gray-500">{{ __('# headings, **bold**, - lists, `code`, ```code blocks```') }}</p>
                <x-input-error :messages="$errors->get('body')" class="mt-2" />
            </div>

            <!-- Attachment -->
            <div class="border-t border-gray-200 pt-5">
                <x-input-label :value="__('Attachment (optional)')" />
                @if ($isEdit && $lesson->attachment_path)
                    <div class="mt-2 rounded-md border border-gray-200 bg-gray-50 p-3 flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm text-gray-700 min-w-0">
                            <svg class="h-5 w-5 shrink-0 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                            <a href="{{ $lesson->attachment_url }}" target="_blank" class="truncate underline text-indigo-600">{{ $lesson->attachment_name }}</a>
                        </div>
                        <label class="inline-flex items-center gap-1 text-xs text-red-600 cursor-pointer">
                            <input type="checkbox" name="remove_attachment" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                            {{ __('Remove') }}
                        </label>
                    </div>
                @endif
                <input type="file" name="attachment" class="mt-2 block w-full text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="mt-1 text-xs text-gray-500">{{ __('PDF, DOCX, images, etc. Max 10 MB.') }}</p>
                <x-input-error :messages="$errors->get('attachment')" class="mt-2" />
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
