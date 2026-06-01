<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnneeAcademique;
use App\Models\Message;
use App\Models\ParentEleve;
use App\Models\Personne;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::with('destinataire')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.messages.index', compact('messages'));
    }

    public function create()
    {
        // Parents : personnes ayant au moins un enfant dans parent_eleves
        $parents = Personne::whereIn(
            'idPers',
            ParentEleve::select('idPers')->distinct()
        )->orderBy('nom')->get();

        return view('admin.messages.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'destinataires'  => ['required', 'array', 'min:1'],
            'destinataires.*'=> ['exists:personnes,idPers'],
            'objet'          => ['required', 'string', 'max:255'],
            'information'    => ['required', 'string'],
            'type_message'   => ['required', 'in:1,2,3'],
        ]);

        $annee     = AnneeAcademique::where('actif', true)->first();
        $idExpPers = auth()->user()->idPers;

        foreach ($data['destinataires'] as $idPers) {
            Message::create([
                'idExp_Pers'   => $idExpPers,
                'idParent'     => $idPers,
                'objet'        => $data['objet'],
                'information'  => $data['information'],
                'type_message' => $data['type_message'],
                'AnneeAcade'   => $annee?->libelle,
                'valider'      => false,
            ]);
        }

        $nb = count($data['destinataires']);
        return redirect()->route('admin.messages.index')
            ->with('success', "Message envoyé à {$nb} parent(s).");
    }

    public function show(Message $message)
    {
        $message->load('destinataire', 'expediteur');
        return view('admin.messages.show', compact('message'));
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return redirect()->route('admin.messages.index')
            ->with('success', 'Message supprimé.');
    }
}
