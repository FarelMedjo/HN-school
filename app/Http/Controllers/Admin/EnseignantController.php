<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Enseignant;
use App\Models\Personne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EnseignantController extends Controller
{
    public function index()
    {
        $enseignants = Enseignant::with('personne', 'cours')->paginate(15);
        return view('admin.enseignants.index', compact('enseignants'));
    }

    public function create()
    {
        return view('admin.enseignants.create', [
            'cours' => Cours::orderBy('libelle')->get(),
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
            'idCours' => ['nullable', 'exists:cours,idCours'],
        ]);
        $pers = Personne::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'mobile' => $data['mobile'] ?? null,
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'typePersonne' => 2,
        ]);
        Enseignant::create([
            'idPers' => $pers->idPers,
            'idCours' => $data['idCours'] ?? null,
            'Actif' => true,
        ]);
        return redirect()->route('admin.enseignants.index')->with('success', 'Enseignant créé.');
    }

    public function destroy(Enseignant $enseignant)
    {
        $enseignant->delete();
        return back()->with('success', 'Enseignant supprimé.');
    }
}
