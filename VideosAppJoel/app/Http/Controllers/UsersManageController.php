<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class UsersManageController extends Controller
{
    public function index()
    {
        $users = User::paginate(10); // Paginació per navegar entre pàgines
        return view('users.manage.index', compact('users'));
    }

    public function create()
    {
        $permissions = Permission::all(); // Passem els permisos a la vista
        return view('users.manage.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'permissions' => 'nullable|array', // Afegim permisos com a camp opcional
        ]);

        // Crear l'usuari
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // Crear un equip personal per a l'usuari
        $team = Team::create([
            'user_id' => $user->id,
            'name' => $user->name . ' Team',
            'personal_team' => true,
        ]);

        // Assignar permisos si s'han enviat
        if ($request->has('permissions')) {
            $user->syncPermissions($validated['permissions']);
        }

        return redirect()->route('users.manage.index')
            ->with('success', 'Usuari creat correctament.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $permissions = Permission::all();
        return view('users.manage.edit', compact('user', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'permissions' => 'nullable|array',
        ]);

        $user = User::findOrFail($id);
        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
        $user->update($validated);

        if ($request->has('permissions')) {
            $user->syncPermissions($validated['permissions']);
        } else {
            $user->syncPermissions([]); // Eliminar tots els permisos si no s'envien
        }

        return redirect()->route('users.manage.index')
            ->with('success', 'Usuari actualitzat correctament.');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        return view('users.manage.delete', compact('user'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.manage.index')
            ->with('success', 'Usuari eliminat correctament.');
    }

    public function testedBy()
    {
        return 'Tests\\Feature\\Users\\UsersManageTest';
    }
}
