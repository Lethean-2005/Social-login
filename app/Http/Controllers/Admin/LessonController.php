<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

        $attach = $this->handleAttachment($request);
        if ($attach) {
            $data['attachment_path'] = $attach['path'];
            $data['attachment_name'] = $attach['name'];
        }

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

        if ($request->boolean('remove_attachment') && $lesson->attachment_path) {
            Storage::disk('public')->delete($lesson->attachment_path);
            $data['attachment_path'] = null;
            $data['attachment_name'] = null;
        }

        $attach = $this->handleAttachment($request);
        if ($attach) {
            if ($lesson->attachment_path) {
                Storage::disk('public')->delete($lesson->attachment_path);
            }
            $data['attachment_path'] = $attach['path'];
            $data['attachment_name'] = $attach['name'];
        }

        $lesson->update($data);

        return redirect()->route('admin.lessons.index')->with('status', "Updated \"{$lesson->title}\".");
    }

    public function destroy(Lesson $lesson): RedirectResponse
    {
        if ($lesson->attachment_path) {
            Storage::disk('public')->delete($lesson->attachment_path);
        }

        $title = $lesson->title;
        $lesson->delete();

        return redirect()->route('admin.lessons.index')->with('status', "Deleted \"{$title}\".");
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'category' => ['nullable', 'string', 'max:80'],
            'excerpt' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'attachment' => ['nullable', 'file', 'max:10240'], // 10 MB
            'is_published' => ['sometimes', 'boolean'],
            'remove_attachment' => ['sometimes', 'boolean'],
        ]);

        return [
            'title' => $data['title'],
            'category' => $data['category'] ?? null,
            'excerpt' => $data['excerpt'] ?? null,
            'body' => $data['body'],
            'video_url' => $data['video_url'] ?? null,
            'is_published' => (bool) $request->boolean('is_published'),
        ];
    }

    private function handleAttachment(Request $request): ?array
    {
        if (! $request->hasFile('attachment')) {
            return null;
        }

        $file = $request->file('attachment');
        $path = $file->store('lesson-attachments', 'public');

        return [
            'path' => $path,
            'name' => $file->getClientOriginalName(),
        ];
    }
}
