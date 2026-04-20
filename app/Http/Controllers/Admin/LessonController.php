<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function index(): View
    {
        return view('admin.lessons.index', [
            'lessons' => Lesson::with('author')->latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.lessons.form', [
            'lesson' => new Lesson(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['author_id'] = $request->user()->id;
        $data['slug'] = Lesson::makeUniqueSlug($data['title']);
        $data['published_at'] = $data['is_published'] ? now() : null;

        $lesson = Lesson::create($data);

        return redirect()->route('admin.lessons.index')->with('status', "Created \"{$lesson->title}\".");
    }

    public function edit(Lesson $lesson): View
    {
        return view('admin.lessons.form', ['lesson' => $lesson]);
    }

    public function update(Request $request, Lesson $lesson): RedirectResponse
    {
        $data = $this->validated($request);

        if ($data['title'] !== $lesson->title) {
            $data['slug'] = Lesson::makeUniqueSlug($data['title'], $lesson->id);
        }

        if ($data['is_published'] && ! $lesson->is_published) {
            $data['published_at'] = now();
        } elseif (! $data['is_published']) {
            $data['published_at'] = null;
        }

        $lesson->update($data);

        return redirect()->route('admin.lessons.index')->with('status', "Updated \"{$lesson->title}\".");
    }

    public function destroy(Lesson $lesson): RedirectResponse
    {
        $title = $lesson->title;
        $lesson->delete();

        return redirect()->route('admin.lessons.index')->with('status', "Deleted \"{$title}\".");
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'category' => ['nullable', 'string', 'max:80'],
            'excerpt' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'is_published' => ['sometimes', 'boolean'],
        ]) + ['is_published' => (bool) $request->boolean('is_published')];
    }
}
