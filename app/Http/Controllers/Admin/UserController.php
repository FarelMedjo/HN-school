<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles', 'personne', 'eleve')
            ->orderBy('name')
            ->paginate(20);

        $roles = Role::orderBy('name')->pluck('name');

        return view('admin.utilisateurs.index', compact('users', 'roles'));
    }

    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => ['required', 'string', 'exists:roles,name'],
        ]);

        if ($user->id === auth()->id()) {
            return back()->withErrors(['role' => 'Vous ne pouvez pas modifier votre propre rôle.']);
        }

        $user->syncRoles([$data['role']]);

        return back()->with('success', "Rôle de {$user->name} mis à jour : {$data['role']}.");
    }

    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['actif' => 'Vous ne pouvez pas désactiver votre propre compte.']);
        }

        $user->update(['actif' => ! $user->actif]);

        $etat = $user->actif ? 'activé' : 'désactivé';

        return back()->with('success', "Compte de {$user->name} {$etat}.");
    }
}
