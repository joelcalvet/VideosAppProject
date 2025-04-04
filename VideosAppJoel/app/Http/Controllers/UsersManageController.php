<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersManageController extends Controller
{
    public function index()
    {
        $users = User::paginate(10); // Paginació per navegar entre pàgines
        return view('users.manage.index', compact('users'));
    }

    public function create()
    {
        return view('users.manage.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        User::create(array_merge($validated, ['password' => bcrypt($validated['password'])]));

        return redirect()->route('users.manage.index')
            ->with('success', 'Usuari creat correctament.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.manage.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
        ]);

        $user = User::findOrFail($id);
        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
        $user->update($validated);

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
