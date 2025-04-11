<?php

namespace App\Http\Controllers;

use App\Models\Serie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SeriesManageController extends Controller
{
    public function testedBy()
    {
        return 'Tests\\Feature\\Series\\SeriesManageControllerTest';
    }

    public function index()
    {
        $series = Serie::paginate(10);
        return view('series.manage.index', compact('series'));
    }

    public function create()
    {
        return view('series.manage.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'user_name' => 'required|string',
            'user_photo_url' => 'nullable|string',
            'published_at' => 'nullable|date',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('series', 'public');
        }

        Serie::create($validated);

        return redirect()->route('series.manage.index')
            ->with('success', 'Sèrie creada correctament.');
    }

    public function edit($id)
    {
        $serie = Serie::findOrFail($id);
        return view('series.manage.edit', compact('serie'));
    }

    public function update(Request $request, $id)
    {
        $serie = Serie::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'user_name' => 'required|string',
            'user_photo_url' => 'nullable|string',
            'published_at' => 'nullable|date',
        ]);

        if ($request->hasFile('image')) {
            // Eliminar la imatge antiga si existeix
            if ($serie->image) {
                Storage::disk('public')->delete($serie->image);
            }
            $validated['image'] = $request->file('image')->store('series', 'public');
        }

        $serie->update($validated);

        return redirect()->route('series.manage.index')
            ->with('success', 'Sèrie actualitzada correctament.');
    }

    public function delete($id)
    {
        $serie = Serie::findOrFail($id);
        return view('series.manage.delete', compact('serie'));
    }

    public function destroy($id)
    {
        $serie = Serie::findOrFail($id);
        // Eliminar la imatge si existeix
        if ($serie->image) {
            Storage::disk('public')->delete($serie->image);
        }
        $serie->delete();

        return redirect()->route('series.manage.index')
            ->with('success', 'Sèrie eliminada correctament.');
    }
}
