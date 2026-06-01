<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Convocation;
use App\Models\Eleve;
use Illuminate\Http\Request;

class ConvocationController extends Controller
{
    public function index(Request $request)
    {
        $matricule = $request->integer('matricule') ?: null;

        $convocations = Convocation::with(['eleve', 'auteur'])
            ->when($matricule, fn ($q) => $q->where('matricule', $matricule))
            ->orderByDesc('dateRdv')
            ->paginate(20)
            ->withQueryString();

        $eleves = Eleve::orderBy('nom')->orderBy('prenom')->get();

        return view('admin.convocations.index', compact('convocations', 'eleves', 'matricule'));
    }

    public function create()
    {
        $eleves = Eleve::orderBy('nom')->orderBy('prenom')->get();

        return view('admin.convocations.create', compact('eleves'));
    }

    public function store(Request $request)
    {
        $data = $this->validateConvocation($request);
        $data['idAuteur'] = auth()->id();

        $convocation = Convocation::create($data);

        return redirect()
            ->route('admin.convocations.show', $convocation)
            ->with('success', 'Convocation enregistrée.');
    }

    public function show(Convocation $convocation)
    {
        $convocation->load(['eleve', 'auteur']);

        return view('convocations.show', compact('convocation'));
    }

    public function destroy(Convocation $convocation)
    {
        $convocation->delete();

        return redirect()
            ->route('admin.convocations.index')
            ->with('success', 'Convocation supprimée.');
    }

    private function validateConvocation(Request $request): array
    {
        return $request->validate([
            'matricule' => ['required', 'exists:eleves,matricule'],
            'objet'     => ['required', 'string', 'max:255'],
            'motif'     => ['nullable', 'string'],
            'dateRdv'   => ['required', 'date'],
            'lieu'      => ['nullable', 'string', 'max:255'],
        ]);
    }
}
