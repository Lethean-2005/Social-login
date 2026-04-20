<x-dashboard-layout title="Manage Lessons">
    <div class="max-w-6xl mx-auto space-y-6">

        @if (session('status'))
            <div class="rounded-md border border-green-200 bg-green-50 p-3 text-sm text-green-800">
                {{ session('status') }}
            </div>
        @endif

        <div class="flex items-center justify-between">
            <h3 class="text-xl font-semibold text-gray-900">{{ __('Lessons') }}</h3>
            <a href="{{ route('admin.lessons.create') }}"
               class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                {{ __('New lesson') }}
            </a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium">{{ __('Title') }}</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('Category') }}</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('Status') }}</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('Author') }}</th>
                            <th class="px-6 py-3 text-left font-medium">{{ __('Updated') }}</th>
                            <th class="px-6 py-3 text-right font-medium">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($lessons as $lesson)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3">
                                    <div class="font-medium text-gray-900">{{ $lesson->title }}</div>
                                    <div class="text-xs text-gray-500 truncate max-w-md">{{ $lesson->excerpt }}</div>
                                </td>
                                <td class="px-6 py-3 text-gray-700">
                                    @if ($lesson->category)
                                        <span class="inline-flex rounded-full bg-indigo-50 px-2 py-0.5 text-xs font-medium text-indigo-700">{{ $lesson->category }}</span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3">
                                    @if ($lesson->is_published)
                                        <span class="inline-flex rounded-full bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700">{{ __('Published') }}</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700">{{ __('Draft') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-gray-700">{{ $lesson->author?->name }}</td>
                                <td class="px-6 py-3 text-gray-600">{{ $lesson->updated_at?->diffForHumans() }}</td>
                                <td class="px-6 py-3 text-right space-x-2 whitespace-nowrap">
                                    <a href="{{ route('admin.lessons.edit', $lesson) }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">{{ __('Edit') }}</a>
                                    <form method="POST" action="{{ route('admin.lessons.destroy', $lesson) }}" class="inline"
                                          onsubmit="return confirm('Delete this lesson?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-800">{{ __('Delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                {{ __('No lessons yet.') }}
                                <a href="{{ route('admin.lessons.create') }}" class="text-indigo-600 underline">{{ __('Write the first one') }}</a>.
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($lessons->hasPages())
                <div class="px-6 py-3 border-t border-gray-200">{{ $lessons->links() }}</div>
            @endif
        </div>
    </div>
</x-dashboard-layout>
