<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Eleve;
use App\Models\Evaluation;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index()
    {
        $evaluations = Evaluation::with('eleve', 'cours.classe')->latest('idEval')->paginate(15);
        return view('admin.evaluations.index', compact('evaluations'));
    }

    public function create()
    {
        return view('admin.evaluations.create', [
            'eleves' => Eleve::orderBy('nom')->get(),
            'cours' => Cours::orderBy('libelle')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'matricule' => ['required', 'exists:eleves,matricule'],
            'idCours' => ['required', 'exists:cours,idCours'],
            'note' => ['required', 'numeric', 'min:0'],
            'appreciation' => ['nullable', 'string', 'max:255'],
        ]);
        Evaluation::create($data);
        return redirect()->route('admin.evaluations.index')->with('success', 'Évaluation enregistrée.');
    }

    public function destroy(Evaluation $evaluation)
    {
        $evaluation->delete();
        return back()->with('success', 'Évaluation supprimée.');
    }
}
