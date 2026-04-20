@php
    $user = auth()->user();
    $latest = \App\Models\Lesson::published()->with('author')->latest('published_at')->take(3)->get();
    $categories = \App\Models\Lesson::published()
        ->whereNotNull('category')
        ->selectRaw('category, COUNT(*) as cnt')
        ->groupBy('category')
        ->orderByDesc('cnt')
        ->take(8)
        ->get();
    $totalLessons = \App\Models\Lesson::published()->count();
    $minutesOfContent = \App\Models\Lesson::published()->get()->sum(fn ($l) => $l->reading_time);
@endphp

<x-dashboard-layout title="Dashboard">

    <!-- Welcome banner -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg shadow p-6 flex flex-col sm:flex-row items-start sm:items-center gap-4">
        @if ($user->avatar)
            <img src="{{ $user->avatar }}" alt="avatar" referrerpolicy="no-referrer" class="h-16 w-16 rounded-full ring-2 ring-white/40">
        @else
            <div class="h-16 w-16 rounded-full bg-white/20 flex items-center justify-center text-2xl font-semibold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        @endif
        <div class="flex-1">
            <h2 class="text-xl sm:text-2xl font-semibold">{{ __('Welcome back,') }} {{ $user->name }} 📚</h2>
            <p class="text-sm text-indigo-100 mt-1">
                @if ($user->is_admin)
                    {{ __('Share new lessons with your students from the Admin section.') }}
                @else
                    {{ __('Pick up where you left off, or browse new lessons below.') }}
                @endif
            </p>
        </div>
        <a href="{{ route('lessons.index') }}"
           class="inline-flex items-center gap-2 rounded-md bg-white/90 px-3 py-2 text-sm font-medium text-indigo-700 hover:bg-white">
            {{ __('Browse lessons') }}
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
        </a>
    </div>

    <!-- Stat strip -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
        <div class="bg-white rounded-lg shadow p-5">
            <div class="text-xs uppercase text-gray-500">{{ __('Published lessons') }}</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">{{ $totalLessons }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-5">
            <div class="text-xs uppercase text-gray-500">{{ __('Total reading time') }}</div>
            <div class="mt-2 text-3xl font-bold text-indigo-600">{{ $minutesOfContent }} min</div>
        </div>
        <div class="bg-white rounded-lg shadow p-5">
            <div class="text-xs uppercase text-gray-500">{{ __('Categories') }}</div>
            <div class="mt-2 text-3xl font-bold text-amber-600">{{ $categories->count() }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-5">
            <div class="text-xs uppercase text-gray-500">{{ __('Your role') }}</div>
            <div class="mt-2 text-3xl font-bold {{ $user->is_admin ? 'text-purple-600' : 'text-green-600' }}">
                {{ $user->is_admin ? __('Instructor') : __('Student') }}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

        <!-- Latest lessons -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900">{{ __('Latest lessons') }}</h3>
                <a href="{{ route('lessons.index') }}" class="text-xs text-indigo-600 hover:underline">{{ __('See all') }}</a>
            </div>
            <ul class="divide-y divide-gray-100">
                @forelse ($latest as $lesson)
                    <li>
                        <a href="{{ route('lessons.show', $lesson) }}" class="flex items-start gap-4 p-5 hover:bg-gray-50">
                            <div class="h-10 w-10 rounded-md bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr($lesson->title, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                @if ($lesson->category)
                                    <span class="text-xs font-medium text-indigo-600">{{ $lesson->category }}</span>
                                @endif
                                <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $lesson->title }}</h4>
                                <p class="text-xs text-gray-500 truncate">{{ $lesson->excerpt ?? Str::limit(strip_tags($lesson->rendered_body), 90) }}</p>
                            </div>
                            <div class="text-right text-xs text-gray-500 shrink-0">
                                <div>{{ $lesson->reading_time }} min</div>
                                <div>{{ $lesson->published_at?->diffForHumans() }}</div>
                            </div>
                        </a>
                    </li>
                @empty
                    <li class="p-8 text-center text-sm text-gray-500">
                        @if ($user->is_admin)
                            {{ __('No lessons published yet.') }}
                            <a href="{{ route('admin.lessons.create') }}" class="text-indigo-600 underline">{{ __('Create the first one') }}</a>.
                        @else
                            {{ __('Your instructor hasn\'t published any lessons yet. Check back soon!') }}
                        @endif
                    </li>
                @endforelse
            </ul>
        </div>

        <!-- Categories panel -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-900">{{ __('Topics') }}</h3>
            </div>
            <div class="p-5">
                @if ($categories->count())
                    <ul class="space-y-2">
                        @foreach ($categories as $cat)
                            <li>
                                <a href="{{ route('lessons.index', ['category' => $cat->category]) }}"
                                   class="flex items-center justify-between rounded-md px-3 py-2 text-sm hover:bg-gray-50">
                                    <span class="text-gray-800">{{ $cat->category }}</span>
                                    <span class="text-xs rounded-full bg-gray-100 px-2 py-0.5 text-gray-700">{{ $cat->cnt }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-500">{{ __('Topics will appear as lessons get tagged.') }}</p>
                @endif
            </div>
        </div>
    </div>

    @if ($user->is_admin)
        <div class="mt-6 bg-indigo-50 border border-indigo-100 rounded-lg p-5 flex items-center justify-between">
            <div>
                <h4 class="font-semibold text-indigo-900">{{ __('Share new knowledge') }}</h4>
                <p class="text-sm text-indigo-800 mt-1">{{ __('Publish a lesson for your students — Markdown supported.') }}</p>
            </div>
            <a href="{{ route('admin.lessons.create') }}"
               class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                {{ __('New lesson') }}
            </a>
        </div>
    @endif
</x-dashboard-layout>
