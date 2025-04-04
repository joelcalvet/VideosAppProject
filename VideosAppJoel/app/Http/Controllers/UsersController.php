<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::paginate(10); // Paginació per navegar entre pàgines
        return view('users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::with('videos')->findOrFail($id);
        return view('users.show', compact('user'));
    }
}
