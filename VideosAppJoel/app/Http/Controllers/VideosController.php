<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideosController extends Controller
{
    public function index()
    {
        $videos = Video::paginate(10);
        return view('videos.index', compact('videos'));
    }

    public function create()
    {
        return view('videos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|url',
            'published_at' => 'nullable|date',
        ]);

        Video::create(array_merge($validated, [
            'user_id' => auth()->user()->id,
        ]));

        return redirect()->route('videos.index')
            ->with('success', 'Vídeo creat correctament.');
    }

    public function show(Video $video)
    {
        return view('videos.show', compact('video'));
    }

    public function edit($id)
    {
        $video = Video::findOrFail($id);
        return view('videos.edit', compact('video'));
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

        return redirect()->route('videos.index')
            ->with('success', 'Vídeo actualitzat correctament.');
    }

    public function delete($id)
    {
        $video = Video::findOrFail($id);
        return view('videos.delete', compact('video'));
    }

    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        $video->delete();

        return redirect()->route('videos.index')
            ->with('success', 'Vídeo eliminat correctament.');
    }

    public function testedBy(Video $video)
    {
        return $video->testedBy();
    }
}
