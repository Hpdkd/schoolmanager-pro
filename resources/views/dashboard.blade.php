@extends('layouts.app')
@section('title', 'Tableau de bord')

@section('content')

{{-- ─── KPI Cards ────────────────────────────────── --}}
@php
$kpis = [
    ['label'=>'Élèves inscrits',  'value'=>$stats['total_students'], 'sub'=>'Total cette année',       'color'=>'#4a9e82','bg'=>'#e8f5f0','icon'=>'<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>'],
    ['label'=>'Classes actives',  'value'=>$stats['total_classes'],  'sub'=>'Groupes en cours',         'color'=>'#3b82f6','bg'=>'#eff6ff','icon'=>'<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>'],
    ['label'=>'Matières',         'value'=>$stats['total_subjects'], 'sub'=>'Disciplines enseignées',   'color'=>'#8b5cf6','bg'=>'#f5f3ff','icon'=>'<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>'],
    ['label'=>'Notes enregistrées','value'=>$stats['total_grades'],  'sub'=>'Évaluations saisies',      'color'=>'#f59e0b','bg'=>'#fffbeb','icon'=>'<path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>'],
];
@endphp

<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:22px;">
@foreach($kpis as $k)
<div class="card-hover" style="background:white; border-radius:12px; padding:18px 20px; border:1px solid #eaeef0; position:relative; overflow:hidden;">
    <div style="position:absolute; top:0; left:0; right:0; height:2.5px; background:{{ $k['color'] }};"></div>
    <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-top:4px;">
        <div>
            <div style="font-size:10.5px; font-weight:600; color:#9ca3af; text-transform:uppercase; letter-spacing:0.07em;">{{ $k['label'] }}</div>
            <div style="font-size:28px; font-weight:800; color:#0f172a; margin:7px 0 3px; letter-spacing:-0.02em; line-height:1;">{{ $k['value'] }}</div>
            <div style="font-size:11.5px; color:#b0b9b5;">{{ $k['sub'] }}</div>
        </div>
        <div style="width:38px; height:38px; border-radius:10px; background:{{ $k['bg'] }}; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="{{ $k['color'] }}" stroke-width="1.8">{!! $k['icon'] !!}</svg>
        </div>
    </div>
</div>
@endforeach
</div>

{{-- ─── Ligne 2 : Remplissage + Résultats S1 ─── --}}
<div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:22px;">

    <div style="background:white; border-radius:12px; padding:20px 22px; border:1px solid #eaeef0;">
        <div style="font-size:12.5px; font-weight:700; color:#0f172a; margin-bottom:14px;">Taux de saisie des notes</div>
        <div style="display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:10px;">
            <div style="font-size:36px; font-weight:900; color:#0f172a; letter-spacing:-0.03em;">{{ $fillRate }}<span style="font-size:16px; font-weight:500; color:#9ca3af;">%</span></div>
            <div style="font-size:12px; color:#9ca3af; text-align:right; line-height:1.6;">{{ $stats['total_grades'] }} notes<br>enregistrées</div>
        </div>
        <div style="height:6px; background:#f1f5f1; border-radius:99px; overflow:hidden;">
            <div style="height:100%; width:{{ $fillRate }}%; background:linear-gradient(90deg,#4a9e82,#6bc4a8); border-radius:99px;"></div>
        </div>
        <div style="font-size:11px; color:#b0b9b5; margin-top:8px;">{{ 100 - $fillRate }}% restant à compléter</div>
    </div>

    <div style="background:white; border-radius:12px; padding:20px 22px; border:1px solid #eaeef0;">
        <div style="font-size:12.5px; font-weight:700; color:#0f172a; margin-bottom:14px;">Résultats — Semestre 1</div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            <div style="background:#f0faf5; border:1px solid #c9e8da; border-radius:10px; padding:14px; text-align:center;">
                <div style="font-size:26px; font-weight:800; color:#2d7a60; letter-spacing:-0.02em;">{{ $admitted }}</div>
                <div style="font-size:10.5px; font-weight:600; color:#4a9e82; margin-top:3px; text-transform:uppercase; letter-spacing:0.06em;">Admis</div>
            </div>
            <div style="background:#fff8f8; border:1px solid #f5c6c6; border-radius:10px; padding:14px; text-align:center;">
                <div style="font-size:26px; font-weight:800; color:#c0392b; letter-spacing:-0.02em;">{{ $rejected }}</div>
                <div style="font-size:10.5px; font-weight:600; color:#e57373; margin-top:3px; text-transform:uppercase; letter-spacing:0.06em;">Non admis</div>
            </div>
        </div>
    </div>
</div>

{{-- ─── Graphiques ─── --}}
<div style="display:grid; grid-template-columns:300px 1fr; gap:16px; margin-bottom:22px;">

    <div style="background:white; border-radius:12px; padding:20px 22px; border:1px solid #eaeef0;">
        <div style="font-size:12.5px; font-weight:700; color:#0f172a; margin-bottom:14px;">Répartition des notes</div>
        <canvas id="donutChart" height="190"></canvas>
        <div style="margin-top:14px; display:flex; flex-direction:column; gap:6px;">
            @foreach([['Excellent ≥ 16','#4a9e82'],['Bien 14–16','#68b89c'],['Assez bien 12–14','#a8d5c5'],['Moyen 10–12','#f59e0b'],['Insuffisant < 10','#e57373']] as $l)
            <div style="display:flex; align-items:center; gap:7px;">
                <div style="width:8px; height:8px; border-radius:2px; background:{{ $l[1] }}; flex-shrink:0;"></div>
                <span style="font-size:11px; color:#6b7280;">{{ $l[0] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <div style="background:white; border-radius:12px; padding:20px 22px; border:1px solid #eaeef0;">
        <div style="font-size:12.5px; font-weight:700; color:#0f172a; margin-bottom:14px;">Moyennes par matière</div>
        <canvas id="subjectChart" height="200"></canvas>
    </div>
</div>

{{-- ─── Classement + Moyennes classes ─── --}}
<div style="display:grid; grid-template-columns:1fr 1.5fr; gap:16px;">

    <div style="background:white; border-radius:12px; padding:20px 22px; border:1px solid #eaeef0;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px;">
            <div style="font-size:12.5px; font-weight:700; color:#0f172a;">Meilleurs élèves — S1</div>
            <a href="{{ route('grades.results') }}" style="font-size:11px; font-weight:600; color:#4a9e82; text-decoration:none;">Classement complet →</a>
        </div>
        @foreach($topStudents as $i => $item)
        @php
            $student = $item['student'];
            $rankColor = match($i) { 0=>'#d4a017', 1=>'#9ca3af', 2=>'#b87333', default=>'#d1d5db' };
        @endphp
        <div style="display:flex; align-items:center; gap:11px; padding:9px 0; {{ $i < count($topStudents)-1 ? 'border-bottom:1px solid #f3f6f4;' : '' }}">
            <div style="width:22px; text-align:center; font-size:12px; font-weight:800; color:{{ $rankColor }}; flex-shrink:0;">
                @if($i===0) 🥇 @elseif($i===1) 🥈 @elseif($i===2) 🥉 @else {{ $i+1 }} @endif
            </div>
            <div style="width:32px; height:32px; border-radius:8px; background:{{ ['#e8f5f0','#eff6ff','#f5f3ff','#fffbeb','#fff3f3'][$i] }}; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; color:#374151; flex-shrink:0;">
                {{ strtoupper(substr($student->first_name,0,1).substr($student->last_name,0,1)) }}
            </div>
            <div style="flex:1; min-width:0;">
                <div style="font-size:13px; font-weight:600; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $student->first_name }} {{ $student->last_name }}</div>
                <div style="font-size:11px; color:#9ca3af; margin-top:1px;">{{ $student->schoolClass->name ?? '—' }}</div>
            </div>
            <div style="font-size:15px; font-weight:700; color:#4a9e82; flex-shrink:0;">{{ $item['average'] }}<span style="font-size:10px; color:#b0b9b5; font-weight:400;">/20</span></div>
        </div>
        @endforeach
    </div>

    <div style="background:white; border-radius:12px; padding:20px 22px; border:1px solid #eaeef0;">
        <div style="font-size:12.5px; font-weight:700; color:#0f172a; margin-bottom:14px;">Moyennes par classe — S1</div>
        <canvas id="classChart" height="210"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font.family = 'Inter, sans-serif';
Chart.defaults.font.size   = 11;
Chart.defaults.color       = '#9ca3af';

new Chart(document.getElementById('donutChart'), {
    type: 'doughnut',
    data: {
        datasets: [{
            data: @json(array_values($gradeDistribution)),
            backgroundColor: ['#4a9e82','#68b89c','#a8d5c5','#f59e0b','#e57373'],
            borderWidth: 0,
            hoverOffset: 5,
        }]
    },
    options: { cutout:'70%', plugins:{legend:{display:false}}, animation:{duration:700} }
});

const sd = @json($subjectAverages);
new Chart(document.getElementById('subjectChart'), {
    type: 'bar',
    data: {
        labels: sd.map(s => s.name.length>13 ? s.name.slice(0,12)+'…' : s.name),
        datasets:[{
            data: sd.map(s => s.average),
            backgroundColor: sd.map(s => s.average>=12 ? '#4a9e82' : s.average>=10 ? '#f59e0b' : '#e57373'),
            borderRadius: 5,
            borderSkipped: false,
        }]
    },
    options:{
        responsive:true,
        scales:{
            y:{ beginAtZero:true, max:20, grid:{color:'#f3f6f4'}, ticks:{stepSize:4} },
            x:{ grid:{display:false} }
        },
        plugins:{ legend:{display:false}, tooltip:{ callbacks:{ label:c=>' Moy. '+c.raw+'/20' } } }
    }
});

const cd = @json($classAverages);
new Chart(document.getElementById('classChart'), {
    type:'bar',
    data:{
        labels: cd.map(c=>c.name),
        datasets:[{
            label:'Moyenne',
            data: cd.map(c=>c.average),
            backgroundColor:'rgba(74,158,130,0.14)',
            borderColor:'#4a9e82',
            borderWidth:1.5,
            borderRadius:6,
            borderSkipped:false,
        }]
    },
    options:{
        responsive:true,
        scales:{
            y:{ beginAtZero:true, max:20, grid:{color:'#f3f6f4'}, ticks:{stepSize:4} },
            x:{ grid:{display:false} }
        },
        plugins:{ legend:{display:false}, tooltip:{ callbacks:{ label:c=>' Moy. '+c.raw+'/20' } } }
    }
});
</script>

@endsection
