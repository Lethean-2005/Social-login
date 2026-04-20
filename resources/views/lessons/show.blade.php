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

                @if ($lesson->video_url)
                    <div class="mt-6 relative rounded-lg overflow-hidden bg-black" style="aspect-ratio: 16/9;">
                        @if ($lesson->is_embeddable_video)
                            <iframe src="{{ $lesson->video_embed_url }}"
                                    class="absolute inset-0 h-full w-full"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        @else
                            <video controls class="absolute inset-0 h-full w-full" src="{{ $lesson->video_url }}"></video>
                        @endif
                    </div>
                @endif

                @if ($lesson->excerpt)
                    <p class="mt-6 text-lg text-gray-700 italic border-l-4 border-indigo-200 pl-4">{{ $lesson->excerpt }}</p>
                @endif

                <div class="prose prose-indigo max-w-none mt-8 text-gray-800 leading-relaxed">
                    {!! $lesson->rendered_body !!}
                </div>

                @if ($lesson->attachment_path)
                    <div class="mt-8 rounded-md border border-gray-200 bg-gray-50 p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="h-10 w-10 rounded-md bg-indigo-100 flex items-center justify-center shrink-0">
                                <svg class="h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-medium text-gray-900 truncate">{{ $lesson->attachment_name }}</div>
                                <div class="text-xs text-gray-500">{{ __('Course material') }}</div>
                            </div>
                        </div>
                        <a href="{{ $lesson->attachment_url }}" download="{{ $lesson->attachment_name }}"
                           class="inline-flex items-center gap-1.5 rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-indigo-500 whitespace-nowrap">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                            {{ __('Download') }}
                        </a>
                    </div>
                @endif
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
