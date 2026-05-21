<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\ParentEleve;
use App\Models\Personne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ParentController extends Controller
{
    public function index()
    {
        $parents = ParentEleve::with('personne', 'eleve')->paginate(15);
        return view('admin.parents.index', compact('parents'));
    }

    public function create()
    {
        return view('admin.parents.create', [
            'eleves' => Eleve::orderBy('nom')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:15'],
            'username' => ['required', 'string', 'max:100', 'unique:personnes,username'],
            'password' => ['required', 'string', 'min:6'],
            'matricule' => ['required', 'exists:eleves,matricule'],
        ]);
        $pers = Personne::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'mobile' => $data['mobile'] ?? null,
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'typePersonne' => 3,
        ]);
        ParentEleve::create([
            'idPers' => $pers->idPers,
            'matricule' => $data['matricule'],
        ]);
        return redirect()->route('admin.parents.index')->with('success', 'Parent créé.');
    }

    public function destroy(ParentEleve $parent)
    {
        $parent->delete();
        return back()->with('success', 'Parent supprimé.');
    }
}
