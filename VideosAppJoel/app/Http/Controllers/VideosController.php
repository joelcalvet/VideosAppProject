<?php

namespace App\Http\Controllers;

use App\Events\VideoCreated;
use App\Models\Serie;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VideosController extends Controller
{
    public function index()
    {
        $videos = Video::paginate(10);
        return view('videos.index', compact('videos'));
    }

    public function create()
    {
        $series = Serie::all(); // Carreguem totes les sèries
        return view('videos.create', compact('series'));
    }

    public function store(Request $request)
    {
        try {
            Log::info('Iniciant creació de vídeo', ['request_data' => $request->all()]);
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'url' => 'required|url',
                'published_at' => 'nullable|date',
                'serie_id' => 'nullable|exists:series,id',
            ]);

            Log::info('Dades validades', ['validated' => $validated]);

            $video = Video::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'url' => $validated['url'],
                'published_at' => $validated['published_at'],
                'user_id' => auth()->id(),
            ]);

            Log::info('Vídeo creat', ['video_id' => $video->id]);

            event(new VideoCreated($video));

            Log::info('Esdeveniment VideoCreated disparat', ['video_id' => $video->id]);

            if (!empty($validated['serie_id'])) {
                $video->series()->syncWithoutDetaching([$validated['serie_id']]);
                Log::info('Sèrie associada', ['serie_id' => $validated['serie_id']]);
            }

            return redirect()->route('videos.index')
                ->with('success', 'Vídeo creat correctament.');
        } catch (\Exception $e) {
            Log::error('Error en creació de vídeo', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e; // Per mantenir l'error visible als tests
        }
    }

    public function show(Video $video)
    {
        return view('videos.show', compact('video'));
    }

    public function edit($id)
    {
        $video = Video::findOrFail($id);
        $series = Serie::all(); // Carreguem totes les sèries
        return view('videos.edit', compact('video', 'series'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|url',
            'published_at' => 'nullable|date',
            'serie_id' => 'nullable|exists:series,id',
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
