<x-dashboard-layout title="Lessons">
    <div class="max-w-6xl mx-auto space-y-6">

        <div>
            <h2 class="text-2xl font-semibold text-gray-900">{{ __('Browse lessons') }}</h2>
            <p class="mt-1 text-sm text-gray-600">{{ __('Knowledge shared by your instructors. Pick one to start reading.') }}</p>
        </div>

        <!-- Filters -->
        <form method="GET" class="bg-white rounded-lg shadow p-4 flex flex-col sm:flex-row gap-3 items-end">
            <div class="flex-1">
                <label class="block text-xs uppercase text-gray-500 mb-1">{{ __('Search title') }}</label>
                <input type="text" name="q" value="{{ $filters['q'] }}"
                       placeholder="{{ __('e.g. Laravel routing') }}"
                       class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>
            <div class="flex-1">
                <label class="block text-xs uppercase text-gray-500 mb-1">{{ __('Category') }}</label>
                <select name="category" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <option value="">{{ __('All categories') }}</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" @selected($filters['category'] === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">{{ __('Filter') }}</button>
                <a href="{{ route('lessons.index') }}" class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">{{ __('Reset') }}</a>
            </div>
        </form>

        <!-- Lesson cards grid -->
        @if ($lessons->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($lessons as $lesson)
                    <a href="{{ route('lessons.show', $lesson) }}"
                       class="group bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden flex flex-col">
                        <div class="h-2 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
                        <div class="p-5 flex-1 flex flex-col">
                            @if ($lesson->category)
                                <span class="self-start inline-flex rounded-full bg-indigo-50 px-2 py-0.5 text-xs font-medium text-indigo-700 mb-2">{{ $lesson->category }}</span>
                            @endif
                            <h3 class="text-base font-semibold text-gray-900 group-hover:text-indigo-600 line-clamp-2">{{ $lesson->title }}</h3>
                            @if ($lesson->excerpt)
                                <p class="mt-2 text-sm text-gray-600 line-clamp-3">{{ $lesson->excerpt }}</p>
                            @endif
                            <div class="mt-auto pt-4 flex items-center justify-between text-xs text-gray-500 border-t border-gray-100">
                                <span>{{ $lesson->author?->name }}</span>
                                <span>{{ $lesson->reading_time }} {{ __('min read') }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            @if ($lessons->hasPages())
                <div>{{ $lessons->links() }}</div>
            @endif
        @else
            <div class="bg-white rounded-lg shadow p-10 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                <p class="mt-3 text-sm text-gray-600">{{ __('No lessons match your filter yet.') }}</p>
            </div>
        @endif
    </div>
</x-dashboard-layout>
