<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Convocation — {{ $convocation->eleve->nom }} {{ $convocation->eleve->prenom }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .doc-page { box-shadow: none !important; margin: 0 !important; border-radius: 0 !important; width: 100% !important; max-width: 100% !important; }
            @page { size: A4 portrait; margin: 18mm 18mm; }
        }
        @media screen {
            body { background: #e5e7eb; min-height: 100vh; padding: 2rem; }
            .doc-page { max-width: 210mm; margin: 0 auto; background: white; box-shadow: 0 4px 24px rgba(0,0,0,0.12); border-radius: 4px; padding: 24mm 22mm; }
        }
        body { font-family: 'Figtree', sans-serif; font-size: 11pt; color: #1a1a1a; line-height: 1.6; }
        h1 { font-size: 16pt; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin: 0; }
        .label { font-weight: 600; color: #374151; }
        .sig-box { border-top: 1px solid #9ca3af; margin-top: 56px; padding-top: 6px; min-height: 50px; width: 220px; }
    </style>
</head>
<body>

<div class="no-print" style="max-width:210mm;margin:0 auto 1rem;display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
    <button onclick="window.print()"
            style="padding:8px 20px;background:#1d4ed8;color:white;border:none;border-radius:6px;font-size:13px;cursor:pointer;font-weight:600;">
        🖨 Imprimer / Télécharger PDF
    </button>
    <a href="{{ url()->previous() }}"
       style="padding:8px 16px;background:white;color:#374151;border:1px solid #d1d5db;border-radius:6px;font-size:13px;text-decoration:none;">
        ← Retour
    </a>
</div>

<div class="doc-page">

    {{-- En-tête --}}
    <table style="width:100%;border:none;margin-bottom:8px;">
        <tr>
            <td style="border:none;vertical-align:top;padding:0;">
                <h1>HN-School</h1>
                <div style="font-size:9pt;color:#4b5563;">École crèche · Maternelle · Primaire</div>
            </td>
            <td style="border:none;text-align:right;vertical-align:top;padding:0;font-size:9pt;color:#6b7280;">
                Le {{ $convocation->created_at?->isoFormat('D MMMM YYYY') }}
            </td>
        </tr>
    </table>

    <hr style="border:2px solid #1e3a5f;margin:10px 0 28px;">

    <div style="text-align:center;font-size:18pt;font-weight:700;text-transform:uppercase;letter-spacing:2px;color:#1e3a5f;margin-bottom:32px;">
        Convocation
    </div>

    <p style="margin-bottom:18px;">
        <span class="label">Aux parents / tuteurs de l'élève :</span><br>
        <strong style="font-size:12.5pt;">{{ strtoupper($convocation->eleve->nom) }} {{ $convocation->eleve->prenom }}</strong>
        — Matricule {{ str_pad($convocation->eleve->matricule, 5, '0', STR_PAD_LEFT) }}
    </p>

    <p style="margin-bottom:18px;">
        <span class="label">Objet :</span> {{ $convocation->objet }}
    </p>

    <p style="margin-bottom:18px;">
        Vous êtes prié(e) de bien vouloir vous présenter à l'établissement à la date et au lieu indiqués
        ci-dessous :
    </p>

    <table style="width:100%;border-collapse:collapse;margin-bottom:22px;">
        <tr>
            <td style="border:1px solid #d1d5db;padding:8px 12px;background:#f3f4f6;font-weight:600;width:35%;">Date et heure</td>
            <td style="border:1px solid #d1d5db;padding:8px 12px;">{{ $convocation->dateRdv->isoFormat('dddd D MMMM YYYY [à] HH[h]mm') }}</td>
        </tr>
        <tr>
            <td style="border:1px solid #d1d5db;padding:8px 12px;background:#f3f4f6;font-weight:600;">Lieu</td>
            <td style="border:1px solid #d1d5db;padding:8px 12px;">{{ $convocation->lieu ?? 'Secrétariat de l\'établissement' }}</td>
        </tr>
    </table>

    @if ($convocation->motif)
        <p style="margin-bottom:6px;" class="label">Motif détaillé :</p>
        <div style="border:1px solid #e5e7eb;background:#fafafa;padding:12px 14px;border-radius:4px;margin-bottom:24px;white-space:pre-line;">{{ $convocation->motif }}</div>
    @endif

    <p style="margin-bottom:8px;">
        Comptant sur votre présence, nous vous prions d'agréer nos salutations distinguées.
    </p>

    {{-- Signature --}}
    <div style="display:flex;justify-content:flex-end;">
        <div class="sig-box">
            <div style="font-size:9.5pt;font-weight:600;color:#374151;">La Direction</div>
            <div style="font-size:9pt;color:#6b7280;margin-top:3px;">
                {{ $convocation->auteur?->name ?? 'HN-School' }}
            </div>
        </div>
    </div>

    <hr style="border:1px solid #e5e7eb;margin-top:28px;">
    <div style="text-align:center;font-size:8pt;color:#9ca3af;margin-top:6px;">
        Document généré le {{ now()->isoFormat('D MMMM YYYY') }} · HN-School — Système de gestion scolaire
    </div>

</div>
</body>
</html>
