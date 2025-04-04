<?php

namespace App\Http\Controllers;

use App\Models\Multimedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApiMultimediaController extends Controller
{
    public function index()
    {
        // Retorna tots els arxius multimèdia
        return Multimedia::all();
    }

    public function store(Request $request)
    {

        $request->validate([
            //'file' => 'required|file|mimes:mp4,jpg,jpeg,png|max:10240', // 10MB màxim
            'file' => 'required|file|mimes:mp4,mov,avi,wmv,flv,png,jpg|max:50000', // 50MB
            'type' => 'required|in:video,photo',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Guarda l'arxiu al sistema de fitxers
        $file = $request->file('file');
        $path = $file->store('multimedia', 'public');

        // Crea el registre a la base de dades
        $multimedia = Multimedia::create([
            'user_id' => auth()->id(),
            'path' => $path,
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json($multimedia, 201);
    }

    public function show(Multimedia $multimedia)
    {
        // Retorna un arxiu específic
        return $multimedia;
    }

    public function update(Request $request, Multimedia $multimedia)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4|max:10240', // Permet fitxer opcional
        ]);

        // Si hi ha un fitxer nou, elimina l'antic i puja el nou
        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($multimedia->path); // Elimina el fitxer antic
            $path = $request->file('file')->store('multimedia', 'public');
            $multimedia->path = $path;
        }

        // Actualitza el títol si es proporciona
        if ($request->filled('title')) {
            $multimedia->title = $request->title;
        }

        $multimedia->save();

        return response()->json($multimedia);
    }

    public function destroy(Multimedia $multimedia)
    {
        // Elimina l'arxiu del sistema de fitxers i la base de dades
        Storage::disk('public')->delete($multimedia->path);
        $multimedia->delete();

        return response()->json(null, 204);
    }

    public function userMultimedia()
    {
        // Retorna els arxius de l'usuari autenticat
        return auth()->user()->multimedia;
    }
}
