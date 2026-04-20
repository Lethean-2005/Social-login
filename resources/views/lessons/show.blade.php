<x-dashboard-layout :title="$lesson->title">
    <div class="max-w-3xl mx-auto space-y-8">

        <nav class="text-xs text-gray-500">
            <a href="{{ route('lessons.index') }}" class="hover:text-indigo-600 underline">{{ __('Lessons') }}</a>
            <span class="mx-1">›</span>
            @if ($lesson->category)
                <a href="{{ route('lessons.index', ['category' => $lesson->category]) }}" class="hover:text-indigo-600">{{ $lesson->category }}</a>
                <span class="mx-1">›</span>
            @endif
            <span class="text-gray-700">{{ $lesson->title }}</span>
        </nav>

        <article class="bg-white rounded-lg shadow overflow-hidden">
            <div class="h-2 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
            <div class="p-8">
                @if ($lesson->category)
                    <span class="inline-flex rounded-full bg-indigo-50 px-2 py-0.5 text-xs font-medium text-indigo-700">{{ $lesson->category }}</span>
                @endif
                <h1 class="mt-3 text-3xl font-bold text-gray-900 leading-tight">{{ $lesson->title }}</h1>

                <div class="mt-4 flex items-center gap-3 text-sm text-gray-600">
                    @if ($lesson->author?->avatar)
                        <img src="{{ $lesson->author->avatar }}" alt="" referrerpolicy="no-referrer" class="h-8 w-8 rounded-full">
                    @else
                        <div class="h-8 w-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-semibold">
                            {{ strtoupper(substr($lesson->author?->name ?? '?', 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <div class="font-medium text-gray-900">{{ $lesson->author?->name }}</div>
                        <div class="text-xs text-gray-500">
                            {{ $lesson->published_at?->format('M j, Y') }} · {{ $lesson->reading_time }} {{ __('min read') }}
                        </div>
                    </div>
                </div>

                @if ($lesson->excerpt)
                    <p class="mt-6 text-lg text-gray-700 italic border-l-4 border-indigo-200 pl-4">{{ $lesson->excerpt }}</p>
                @endif

                <div class="prose prose-indigo max-w-none mt-8 text-gray-800 leading-relaxed">
                    {!! $lesson->rendered_body !!}
                </div>
            </div>
        </article>

        @if ($more->count())
            <section>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ __('Continue learning') }}</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach ($more as $m)
                        <a href="{{ route('lessons.show', $m) }}"
                           class="group bg-white rounded-lg shadow hover:shadow-md transition p-4">
                            @if ($m->category)
                                <span class="text-xs font-medium text-indigo-600">{{ $m->category }}</span>
                            @endif
                            <h4 class="mt-1 text-sm font-semibold text-gray-900 group-hover:text-indigo-600 line-clamp-2">{{ $m->title }}</h4>
                            <p class="mt-2 text-xs text-gray-500">{{ $m->reading_time }} {{ __('min read') }}</p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-dashboard-layout>
