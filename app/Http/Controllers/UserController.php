<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('dashboard.users', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role' => $request->role,
        ]);

        return redirect()->route('dashboard.users')->with('success', 'Utente creato con successo');
    }

    public function update(Request $request, User $user)
    {
        // Validazione dei dati in arrivo
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        // Aggiorna i campi
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role = $request->input('role');

        // Se viene fornita una nuova password, la cripta
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        // Salva le modifiche nel database
        $user->save();

        // Redirect alla lista degli utenti con un messaggio di successo
        return redirect()->route('dashboard.users')->with('success', 'Utente aggiornato con successo!');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('dashboard.users')->with('success', 'Utente eliminato con successo!');
    }
}
