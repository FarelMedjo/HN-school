<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bulletin — {{ $eleve->nom }} {{ $eleve->prenom }} — {{ $trimestre->libelle }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ── Print & screen layout ─────────────────────────── */
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .bulletin-page {
                box-shadow: none !important;
                margin: 0 !important;
                border-radius: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            @page {
                size: A4 portrait;
                margin: 12mm 14mm;
            }
        }
        @media screen {
            body { background: #e5e7eb; min-height: 100vh; padding: 2rem; }
            .bulletin-page {
                max-width: 210mm;
                margin: 0 auto;
                background: white;
                box-shadow: 0 4px 24px rgba(0,0,0,0.12);
                border-radius: 4px;
                padding: 20mm 18mm;
            }
        }

        /* ── Typography ────────────────────────────────────── */
        body { font-family: 'Figtree', sans-serif; font-size: 10pt; color: #1a1a1a; }
        h1 { font-size: 15pt; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
        h2 { font-size: 10pt; font-weight: 600; }

        /* ── Tables ─────────────────────────────────────────── */
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d1d5db; padding: 5px 8px; }
        th { background: #f3f4f6; font-weight: 600; text-transform: uppercase; font-size: 8pt; letter-spacing: 0.5px; }
        td { font-size: 9.5pt; }
        tfoot td { font-weight: 700; background: #f9fafb; }

        /* ── Mention badge ──────────────────────────────────── */
        .badge { display: inline-block; padding: 1px 6px; border-radius: 3px; font-size: 8pt; font-weight: 700; }
        .badge-emerald { background: #d1fae5; color: #065f46; }
        .badge-green   { background: #dcfce7; color: #166534; }
        .badge-sky     { background: #e0f2fe; color: #0c4a6e; }
        .badge-indigo  { background: #e0e7ff; color: #3730a3; }
        .badge-amber   { background: #fef3c7; color: #92400e; }
        .badge-orange  { background: #ffedd5; color: #7c2d12; }
        .badge-rose    { background: #ffe4e6; color: #9f1239; }
        .badge-gray    { background: #f3f4f6; color: #374151; }

        /* ── Signature zone ─────────────────────────────────── */
        .sig-box { border-top: 1px solid #9ca3af; margin-top: 40px; padding-top: 6px; min-height: 50px; }
    </style>
</head>
<body>

{{-- Barre d'action (masquée à l'impression) --}}
<div class="no-print" style="max-width:210mm;margin:0 auto 1rem;display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
    <button onclick="window.print()"
            style="padding:8px 20px;background:#1d4ed8;color:white;border:none;border-radius:6px;font-size:13px;cursor:pointer;font-weight:600;">
        🖨 Imprimer / Télécharger PDF
    </button>
    <a href="{{ url()->previous() }}"
       style="padding:8px 16px;background:white;color:#374151;border:1px solid #d1d5db;border-radius:6px;font-size:13px;text-decoration:none;">
        ← Retour
    </a>
    <span style="font-size:12px;color:#6b7280;">
        Utilisez « Enregistrer en PDF » dans la boîte de dialogue d'impression
    </span>
</div>

{{-- ── BULLETIN PAGE ──────────────────────────────────────────── --}}
<div class="bulletin-page">

    {{-- En-tête --}}
    <table style="border:none;margin-bottom:16px;">
        <tr>
            <td style="border:none;width:60%;vertical-align:top;padding:0;">
                <h1 style="margin:0 0 2px;">HN-School</h1>
                <div style="font-size:8.5pt;color:#4b5563;">
                    École crèche · Maternelle · Primaire
                </div>
                <div style="font-size:8.5pt;color:#4b5563;margin-top:2px;">
                    Année académique : <strong>{{ $annee?->libelle ?? '—' }}</strong>
                </div>
            </td>
            <td style="border:none;text-align:right;vertical-align:top;padding:0;">
                <div style="font-size:14pt;font-weight:700;color:#1e3a5f;text-transform:uppercase;letter-spacing:1px;">
                    Bulletin de Notes
                </div>
                <div style="font-size:9pt;color:#6b7280;margin-top:2px;">
                    {{ $trimestre->libelle }}
                    @if($trimestre->periode) · {{ $trimestre->periode }} @endif
                </div>
            </td>
        </tr>
    </table>

    <hr style="border:2px solid #1e3a5f;margin-bottom:14px;">

    {{-- Infos élève --}}
    <table style="border:none;margin-bottom:16px;">
        <tr>
            <td style="border:none;padding:0;width:50%;">
                <table style="border-collapse:collapse;">
                    <tr>
                        <td style="border:none;font-weight:600;color:#374151;padding:2px 8px 2px 0;font-size:9pt;">Nom & Prénom</td>
                        <td style="border:none;padding:2px 0;font-size:9pt;">
                            <strong>{{ strtoupper($eleve->nom) }}</strong> {{ $eleve->prenom }}
                        </td>
                    </tr>
                    <tr>
                        <td style="border:none;font-weight:600;color:#374151;padding:2px 8px 2px 0;font-size:9pt;">Matricule</td>
                        <td style="border:none;padding:2px 0;font-size:9pt;">{{ str_pad($eleve->matricule, 5, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <td style="border:none;font-weight:600;color:#374151;padding:2px 8px 2px 0;font-size:9pt;">Date de naissance</td>
                        <td style="border:none;padding:2px 0;font-size:9pt;">
                            {{ optional($eleve->dateNaissance)->format('d/m/Y') ?? '—' }}
                        </td>
                    </tr>
                </table>
            </td>
            <td style="border:none;padding:0;width:50%;vertical-align:top;">
                <table style="border-collapse:collapse;">
                    <tr>
                        <td style="border:none;font-weight:600;color:#374151;padding:2px 8px 2px 0;font-size:9pt;">Classe</td>
                        <td style="border:none;padding:2px 0;font-size:9pt;">{{ optional($classe)->libelle ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td style="border:none;font-weight:600;color:#374151;padding:2px 8px 2px 0;font-size:9pt;">Section</td>
                        <td style="border:none;padding:2px 0;font-size:9pt;">{{ ucfirst($section) }}</td>
                    </tr>
                    <tr>
                        <td style="border:none;font-weight:600;color:#374151;padding:2px 8px 2px 0;font-size:9pt;">Effectif / Rang</td>
                        <td style="border:none;padding:2px 0;font-size:9pt;">
                            {{ $effectif ?? '—' }} élève(s)
                            @if ($rang) · <strong>{{ $rang }}{{ $rang == 1 ? 'er' : 'ème' }}</strong> @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Tableau des notes --}}
    <table style="margin-bottom:14px;">
        <thead>
            <tr>
                <th style="text-align:left;width:35%;">Matière</th>
                <th style="text-align:center;width:8%;">Coeff.</th>
                <th style="text-align:center;width:14%;">Note / Max</th>
                <th style="text-align:center;width:14%;">/ 20</th>
                <th style="text-align:center;width:14%;">Mention</th>
                <th style="text-align:left;">Appréciation</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($cours as $c)
                @php
                    $eval    = $evaluations->get($c->idCours);
                    $noteStr = $eval ? number_format($eval->note, 2) : null;
                    $sur20   = ($eval && $c->note > 0) ? number_format($eval->note / $c->note * 20, 2) : null;
                    $mention = ($eval && $sur20 !== null)
                        ? \App\Support\Notation::mention((float)$sur20, $section)
                        : null;
                @endphp
                <tr>
                    <td>{{ $c->libelle }}</td>
                    <td style="text-align:center;">{{ $c->coefficient }}</td>
                    <td style="text-align:center;">
                        {{ $noteStr ? $noteStr . ' / ' . (int)$c->note : '—' }}
                    </td>
                    <td style="text-align:center;font-weight:{{ $eval ? '600' : '400' }};">
                        {{ $sur20 ?? '—' }}
                    </td>
                    <td style="text-align:center;">
                        @if ($mention)
                            <span class="badge badge-{{ $mention['color'] }}">
                                {{ $mention['code'] }}
                            </span>
                        @else
                            <span style="color:#9ca3af;">—</span>
                        @endif
                    </td>
                    <td style="color:#4b5563;font-size:8.5pt;">{{ $eval?->appreciation ?? '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;color:#9ca3af;">
                        Aucune matière enregistrée pour cette classe.
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align:right;">Moyenne générale /20 :</td>
                <td style="text-align:center;font-size:11pt;">
                    {{ $moyenne !== null ? number_format($moyenne, 2) : '—' }}
                </td>
                <td style="text-align:center;">
                    @if ($moyenne !== null)
                        @php $m = \App\Support\Notation::mention($moyenne, $section); @endphp
                        <span class="badge badge-{{ $m['color'] }}">{{ $m['code'] }}</span>
                    @endif
                </td>
                <td style="font-size:8.5pt;color:#374151;">
                    @if ($moyenne !== null)
                        {{ \App\Support\Notation::mention($moyenne, $section)['libelle'] }}
                    @endif
                </td>
            </tr>
        </tfoot>
    </table>

    {{-- Assiduité --}}
    <table style="margin-bottom:16px;">
        <thead>
            <tr>
                <th colspan="4" style="text-align:left;background:#f0f9ff;color:#0c4a6e;">
                    Assiduité (année en cours)
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width:25%;">Absences</td>
                <td style="width:25%;font-weight:600;color:#be123c;">{{ $absences }}</td>
                <td style="width:25%;">Retards</td>
                <td style="width:25%;font-weight:600;color:#b45309;">{{ $retards }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Appréciation générale / Rapport --}}
    @php
        $appreciationText = ($appreciation ?? null)?->contenu ?? optional($rapport)->commentaire;
    @endphp
    <table style="margin-bottom:16px;">
        <thead>
            <tr>
                <th style="text-align:left;background:#f0fdf4;color:#14532d;">
                    Appréciation générale du trimestre
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                @if ($appreciationText)
                    <td style="min-height:36px;white-space:pre-line;">{{ $appreciationText }}</td>
                @else
                    <td style="min-height:36px;color:#9ca3af;font-style:italic;">&nbsp;</td>
                @endif
            </tr>
        </tbody>
    </table>

    {{-- Signatures --}}
    <table style="border:none;margin-top:24px;">
        <tr>
            <td style="border:none;width:33%;vertical-align:top;padding:0 12px 0 0;">
                <div class="sig-box">
                    <div style="font-size:8.5pt;font-weight:600;color:#374151;">Le Titulaire de classe</div>
                    <div style="font-size:8pt;color:#6b7280;margin-top:3px;">
                        {{ optional(optional($titulaire)->personne)->nom ?? '—' }}
                        {{ optional(optional($titulaire)->personne)->prenom ?? '' }}
                    </div>
                </div>
            </td>
            <td style="border:none;width:33%;vertical-align:top;padding:0 6px;">
                <div class="sig-box">
                    <div style="font-size:8.5pt;font-weight:600;color:#374151;">Signature du parent</div>
                </div>
            </td>
            <td style="border:none;width:33%;vertical-align:top;padding:0 0 0 12px;">
                <div style="position:relative;min-height:92px;">
                    @if ($signatureUrl)
                        <img src="{{ $signatureUrl }}" alt="Signature"
                             style="position:absolute;left:6px;top:24px;max-height:42px;max-width:150px;object-fit:contain;-webkit-print-color-adjust:exact;print-color-adjust:exact;">
                    @endif
                    @if ($cachetUrl)
                        <img src="{{ $cachetUrl }}" alt="Cachet"
                             style="position:absolute;right:0;top:4px;width:76px;height:76px;object-fit:contain;opacity:0.82;transform:rotate(-9deg);-webkit-print-color-adjust:exact;print-color-adjust:exact;">
                    @endif
                    <div class="sig-box">
                        <div style="font-size:8.5pt;font-weight:600;color:#374151;">La Direction</div>
                        <div style="font-size:8pt;color:#6b7280;margin-top:3px;">{{ $directeurNom ?? 'HN-School' }}</div>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    {{-- Pied de page --}}
    <hr style="border:1px solid #e5e7eb;margin-top:20px;">
    <div style="text-align:center;font-size:7.5pt;color:#9ca3af;margin-top:6px;">
        Document généré le {{ now()->isoFormat('D MMMM YYYY') }} · HN-School — Système de gestion scolaire
    </div>

</div>
</body>
</html>
