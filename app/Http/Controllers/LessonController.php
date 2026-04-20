<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function index(Request $request): View
    {
        $query = Lesson::published()->with('author')->latest('published_at');

        if ($category = $request->string('category')->toString()) {
            $query->where('category', $category);
        }

        if ($search = $request->string('q')->toString()) {
            $query->where('title', 'like', "%{$search}%");
        }

        return view('lessons.index', [
            'lessons' => $query->paginate(12)->withQueryString(),
            'categories' => Lesson::published()
                ->whereNotNull('category')
                ->select('category')
                ->distinct()
                ->orderBy('category')
                ->pluck('category'),
            'filters' => [
                'category' => $category,
                'q' => $search,
            ],
        ]);
    }

    public function show(Lesson $lesson): View
    {
        abort_unless($lesson->is_published, 404);

        $more = Lesson::published()
            ->where('id', '!=', $lesson->id)
            ->when($lesson->category, fn ($q) => $q->where('category', $lesson->category))
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('lessons.show', [
            'lesson' => $lesson,
            'more' => $more,
        ]);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
