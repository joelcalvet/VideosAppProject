<?php

namespace App\Http\Controllers;

use App\Models\Serie;
use App\Models\Video;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    public function addVideo(Request $request, $id)
    {
        $serie = Serie::findOrFail($id);
        if ($serie->user_id !== auth()->id() && !auth()->user()->can('manage series')) {
            abort(403, 'No tens permís per afegir vídeos a aquesta sèrie.');
        }

        $validated = $request->validate([
            'video_id' => 'required|exists:videos,id',
        ]);

        $serie->videos()->syncWithoutDetaching($validated['video_id']);
        return redirect()->back()->with('success', 'Vídeo afegit correctament.');
    }

    public function index()
    {
        // Mostrar totes les sèries a tots els usuaris
        $series = Serie::paginate(10);
        return view('series.index', compact('series'));
    }

    public function create()
    {
        return view('series.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'published_at' => 'nullable|date',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('series', 'public');
        }

        // Afegim user_id i user_name
        $validated['user_id'] = auth()->id(); // Afegim aquesta línia
        $validated['user_name'] = auth()->user()->name;
        Serie::create($validated);

        return redirect()->route('series.index')
            ->with('success', 'Sèrie creada correctament.');
    }

    public function show($id)
    {
        // Tots els usuaris poden veure qualsevol sèrie
        $serie = Serie::with('videos')->findOrFail($id);
        return view('series.show', compact('serie'));
    }

    public function edit($id)
    {
        $serie = Serie::with('videos')->findOrFail($id);

        // Comprovar permisos
        if ($serie->user_id !== auth()->id() && !auth()->user()->can('manage series')) {
            abort(403, 'No tens permís per editar aquesta sèrie.');
        }

        // Carregar TOTS els vídeos disponibles, no només els de l'usuari
        $availableVideos = Video::all();

        return view('series.edit', compact('serie', 'availableVideos'));
    }

    public function update(Request $request, $id)
    {
        $serie = Serie::findOrFail($id);

        // Només el propietari pot actualitzar la sèrie
        if ($serie->user_id !== auth()->id() && !auth()->user()->can('manage series')) {
            abort(403, 'No tens permís per actualitzar aquesta sèrie.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'published_at' => 'nullable|date',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('series', 'public');
        }

        $validated['user_name'] = auth()->user()->name;
        $serie->update($validated);

        return redirect()->route('series.index')
            ->with('success', 'Sèrie actualitzada correctament.');
    }

    public function delete($id)
    {
        $serie = Serie::findOrFail($id);

        // Només el propietari pot eliminar la sèrie
        if ($serie->user_id !== auth()->id() && !auth()->user()->can('manage series')) {
            abort(403, 'No tens permís per eliminar aquesta sèrie.');
        }

        return view('series.delete', compact('serie'));
    }

    public function destroy($id)
    {
        $serie = Serie::findOrFail($id);

        // Només el propietari pot eliminar la sèrie
        if ($serie->user_id !== auth()->id() && !auth()->user()->can('manage series')) {
            abort(403, 'No tens permís per eliminar aquesta sèrie.');
        }

        $serie->delete();

        return redirect()->route('series.index')
            ->with('success', 'Sèrie eliminada correctament.');
    }
}
