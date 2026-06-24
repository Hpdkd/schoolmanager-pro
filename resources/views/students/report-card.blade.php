<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletin — {{ $student->full_name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a1a2e; }

        .header { background: #3730a3; color: white; padding: 18px 24px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 18px; font-weight: bold; letter-spacing: 0.5px; }
        .header .subtitle { font-size: 11px; opacity: 0.8; margin-top: 2px; }
        .badge { background: rgba(255,255,255,0.2); border-radius: 4px; padding: 4px 10px; font-size: 12px; font-weight: bold; }

        .content { padding: 20px 24px; }

        .student-info { display: flex; gap: 16px; margin-bottom: 18px; }
        .info-box { flex: 1; border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px; }
        .info-box .label { color: #6b7280; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px; }
        .info-box .value { font-weight: bold; color: #1f2937; }

        .grades-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .grades-table thead tr { background: #4338ca; color: white; }
        .grades-table thead th { padding: 8px 10px; text-align: left; font-size: 10px; }
        .grades-table thead th.center { text-align: center; }
        .grades-table tbody tr:nth-child(even) { background: #f9fafb; }
        .grades-table tbody tr:hover { background: #f0f0ff; }
        .grades-table tbody td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; }
        .grades-table tbody td.center { text-align: center; }
        .grade-cell { font-weight: bold; }
        .grade-good { color: #15803d; }
        .grade-mid { color: #92400e; }
        .grade-bad { color: #991b1b; }
        .letter-badge { display: inline-block; padding: 1px 6px; border-radius: 3px; font-size: 9px; font-weight: bold; }
        .letter-a { background: #dcfce7; color: #15803d; }
        .letter-b { background: #dbeafe; color: #1d4ed8; }
        .letter-c { background: #fef9c3; color: #92400e; }
        .letter-d { background: #ffedd5; color: #c2410c; }
        .letter-f { background: #fee2e2; color: #991b1b; }

        .summary { display: flex; gap: 16px; margin-bottom: 18px; }
        .summary-card { flex: 1; border-radius: 8px; padding: 14px; text-align: center; }
        .summary-card .big { font-size: 24px; font-weight: bold; }
        .summary-card .small { font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px; }
        .card-green { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
        .card-blue  { background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; }
        .card-purple { background: #f5f3ff; border: 1px solid #ddd6fe; color: #4338ca; }
        .card-red { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }

        .comment-box { border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px; margin-bottom: 18px; }
        .comment-box .comment-title { font-weight: bold; color: #374151; margin-bottom: 6px; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        .comment-text { color: #6b7280; line-height: 1.5; }

        .footer { border-top: 2px solid #4338ca; padding-top: 14px; display: flex; justify-content: space-between; align-items: flex-end; }
        .signature-box { text-align: center; }
        .signature-line { width: 120px; border-bottom: 1px solid #374151; margin: 30px auto 4px; }
        .signature-label { font-size: 9px; color: #6b7280; }
        .footer-note { font-size: 9px; color: #9ca3af; text-align: right; }

        .watermark-pass { color: #15803d; font-size: 10px; font-weight: bold; }
        .watermark-fail { color: #991b1b; font-size: 10px; font-weight: bold; }
    </style>
</head>
<body>

<div class="header">
    <div>
        <div class="h1">🏫 SchoolManager Pro</div>
        <div class="subtitle">Bulletin de notes — Année scolaire {{ $academicYear }}</div>
    </div>
    <div class="badge">{{ $semester }}</div>
</div>

<div class="content">

    {{-- Infos élève --}}
    <div class="student-info">
        <div class="info-box">
            <div class="label">Nom complet</div>
            <div class="value">{{ $student->full_name }}</div>
        </div>
        <div class="info-box">
            <div class="label">Matricule</div>
            <div class="value">{{ $student->registration_number }}</div>
        </div>
        <div class="info-box">
            <div class="label">Classe</div>
            <div class="value">{{ $student->schoolClass->name }}</div>
        </div>
        <div class="info-box">
            <div class="label">Sexe</div>
            <div class="value">{{ $student->gender === 'M' ? 'Masculin' : 'Féminin' }}</div>
        </div>
        <div class="info-box">
            <div class="label">Date naissance</div>
            <div class="value">{{ \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') }}</div>
        </div>
    </div>

    {{-- Tableau des notes --}}
    <table class="grades-table">
        <thead>
            <tr>
                <th>Matière</th>
                <th class="center">Coeff.</th>
                <th class="center">Note /20</th>
                <th class="center">Points</th>
                <th class="center">Mention</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grades as $grade)
            @php
                $points = $grade->grade * $grade->subject->coefficient;
                $letter = $grade->letter_grade;
                $letterClass = in_array($letter, ['A+','A']) ? 'letter-a' : (in_array($letter, ['B']) ? 'letter-b' : (in_array($letter, ['C']) ? 'letter-c' : (in_array($letter, ['D']) ? 'letter-d' : 'letter-f')));
            @endphp
            <tr>
                <td><strong>{{ $grade->subject->name }}</strong></td>
                <td class="center">{{ $grade->subject->coefficient }}</td>
                <td class="center grade-cell {{ $grade->grade >= 10 ? 'grade-good' : 'grade-bad' }}">
                    {{ number_format($grade->grade, 2) }}
                </td>
                <td class="center">{{ number_format($points, 2) }}</td>
                <td class="center">
                    <span class="letter-badge {{ $letterClass }}">{{ $letter }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Récapitulatif --}}
    @php
        $passText = $average >= 10 ? 'ADMIS(E)' : 'NON ADMIS(E)';
        $cardClass = $average >= 16 ? 'card-green' : ($average >= 12 ? 'card-blue' : ($average >= 10 ? 'card-purple' : 'card-red'));
    @endphp
    <div class="summary">
        <div class="summary-card {{ $cardClass }}">
            <div class="big">{{ number_format($average, 2) }}/20</div>
            <div class="small">Moyenne générale</div>
        </div>
        <div class="summary-card {{ $cardClass }}">
            <div class="big">{{ $mention }}</div>
            <div class="small">Appréciation</div>
        </div>
        <div class="summary-card {{ $average >= 10 ? 'card-green' : 'card-red' }}">
            <div class="big {{ $average >= 10 ? 'watermark-pass' : 'watermark-fail' }}">{{ $passText }}</div>
            <div class="small">Décision</div>
        </div>
    </div>

    {{-- Appréciation --}}
    <div class="comment-box">
        <div class="comment-title">📝 Appréciation du conseil de classe</div>
        <div class="comment-text">
            @if($average >= 16)
                Excellent résultat. Élève très sérieux(se) et régulier(ère). À encourager à maintenir ce niveau remarquable.
            @elseif($average >= 14)
                Bon travail dans l'ensemble. Des efforts constants qui méritent d'être salués. Continuez ainsi.
            @elseif($average >= 12)
                Résultats satisfaisants. L'élève montre une bonne volonté. Quelques efforts supplémentaires permettraient d'atteindre l'excellence.
            @elseif($average >= 10)
                Résultats passables. L'élève est admis(e) mais doit fournir plus d'efforts pour consolider ses acquis.
            @else
                Résultats insuffisants. L'élève doit impérativement s'investir davantage et solliciter l'aide de ses professeurs. Des rattrapages sont fortement conseillés.
            @endif
        </div>
    </div>

    {{-- Signatures --}}
    <div class="footer">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-label">Le Directeur</div>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-label">Le Professeur Principal</div>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-label">Signature du Parent / Tuteur</div>
        </div>
    </div>

    <div class="footer-note" style="margin-top:10px;">
        Document généré le {{ now()->format('d/m/Y à H:i') }} — SchoolManager Pro
    </div>

</div>
</body>
</html>
