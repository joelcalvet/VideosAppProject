<?php

namespace App\Http\Controllers;

use App\Events\VideoCreated;
use Illuminate\Http\Request;
use App\Models\Video;

class VideosManageController extends Controller
{
    public function index()
    {
        $videos = Video::all();
        return view('videos.manage.index', compact('videos'));
    }

    public function create()
    {
        return view('videos.manage.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|url',
            'published_at' => 'nullable|date',
            'serie_id' => 'nullable|exists:series,id',
        ]);

        $video = Video::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'url' => $validated['url'],
            'published_at' => $validated['published_at'] ?? null,
            'user_id' => auth()->id(),
        ]);

        event(new \App\Events\VideoCreated($video));

        if (!empty($validated['serie_id'])) {
            $video->series()->syncWithoutDetaching([$validated['serie_id']]);
        }

        return redirect()->route('videos.manage.index')
            ->with('success', 'Vídeo creat correctament.');
    }

    public function edit($id)
    {
        $video = Video::findOrFail($id);
        return view('videos.manage.edit', compact('video'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|url',
            'published_at' => 'nullable|date',
        ]);

        $video = Video::findOrFail($id);
        $video->update($validated);

        return redirect()->route('videos.manage.index')
            ->with('success', 'Vídeo actualitzat correctament.');
    }

    public function delete($id)
    {
        $video = Video::findOrFail($id);
        return view('videos.manage.delete', compact('video'));
    }

    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        $video->delete();

        return redirect()->route('videos.manage.index')
            ->with('success', 'Vídeo eliminat correctament.');
    }
    public function show($id)
    {
        $video = Video::findOrFail($id);
        return view('videos.manage.show', compact('video'));
    }

    public function testedBy(Video $video)
    {
        return $video->testedBy();
    }
}
