<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SchoolManager Pro — @yield('title', 'Tableau de bord')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }

        /* ─── Sidebar nav ────────────────────────── */
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 12px; border-radius: 7px;
            font-size: 13.5px; font-weight: 500; color: #6b8a7e;
            text-decoration: none; transition: all 0.15s ease;
            position: relative;
        }
        .nav-item:hover  { background: rgba(74,158,130,0.07); color: #cbd5d1; }
        .nav-item.active {
            background: rgba(74,158,130,0.11);
            color: #e2e8e5;
            box-shadow: inset 0 0 0 1px rgba(74,158,130,0.18);
        }
        .nav-item.active::before {
            content: '';
            position: absolute; left: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 16px;
            background: #4a9e82;
            border-radius: 0 3px 3px 0;
        }
        .nav-icon { width: 15px; height: 15px; flex-shrink: 0; opacity: 0.85; }
        .nav-section {
            font-size: 9.5px; font-weight: 700; letter-spacing: 0.12em;
            text-transform: uppercase; color: #374a44;
            padding: 0 12px; margin: 20px 0 5px;
        }

        /* ─── Misc ───────────────────────────────── */
        .card-hover { transition: box-shadow 0.2s, transform 0.15s; }
        .card-hover:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.06); transform: translateY(-1px); }

        .flash-in { animation: flashSlide 0.25s ease; }
        @keyframes flashSlide { from { opacity:0; transform:translateX(16px); } to { opacity:1; transform:translateX(0); } }
    </style>
</head>
<body style="background:#f6f8f7; color:#1e293b; margin:0;">

<div style="display:flex; height:100vh; overflow:hidden;">

{{-- ══════════════════ SIDEBAR ══════════════════ --}}
<aside style="width:240px; flex-shrink:0; display:flex; flex-direction:column; background:#1f2937; box-shadow:1px 0 0 rgba(0,0,0,0.15), 4px 0 24px rgba(0,0,0,0.12);">

    {{-- Logo --}}
    <div style="padding:18px 16px 16px; border-bottom:1px solid rgba(255,255,255,0.05);">
        <div style="display:flex; align-items:center; gap:11px;">

            {{-- Logo mark --}}
            <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" style="flex-shrink:0;">
                <rect width="36" height="36" rx="10" fill="#111827"/>
                {{-- Livre ouvert --}}
                <path d="M8 10.5 C8 9.7 8.7 9 9.5 9 L16 9 L16 26 L9.5 26 C8.7 26 8 25.3 8 24.5 Z" fill="#4a9e82" opacity="0.85"/>
                <path d="M28 10.5 C28 9.7 27.3 9 26.5 9 L20 9 L20 26 L26.5 26 C27.3 26 28 25.3 28 24.5 Z" fill="#4a9e82" opacity="0.45"/>
                <line x1="18" y1="9" x2="18" y2="26" stroke="#111827" stroke-width="1.2"/>
                {{-- Lignes texte gauche --}}
                <line x1="10.5" y1="13" x2="14" y2="13" stroke="#111827" stroke-width="1" stroke-linecap="round" opacity="0.6"/>
                <line x1="10.5" y1="16" x2="14" y2="16" stroke="#111827" stroke-width="1" stroke-linecap="round" opacity="0.6"/>
                {{-- Badge diplôme --}}
                <circle cx="26.5" cy="26.5" r="6" fill="#1f2937" stroke="#4a9e82" stroke-width="1.5"/>
                <path d="M26.5 23.8 L27.1 25.7 L29.1 25.7 L27.5 26.9 L28.1 28.8 L26.5 27.6 L24.9 28.8 L25.5 26.9 L23.9 25.7 L25.9 25.7 Z" fill="#4a9e82"/>
            </svg>

            <div>
                <div style="font-size:14px; font-weight:800; color:#f1f5f2; letter-spacing:-0.02em; line-height:1.1;">SchoolManager</div>
                <div style="font-size:9.5px; font-weight:700; color:#4a9e82; letter-spacing:0.1em; text-transform:uppercase; margin-top:2px;">Pro Edition</div>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav style="flex:1; padding:10px 8px; overflow-y:auto;">

        <div class="nav-section">Principal</div>

        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                <rect x="14" y="14" width="7" height="7" rx="1.5"/>
            </svg>
            Tableau de bord
        </a>

        <a href="{{ route('students.index') }}" class="nav-item {{ request()->routeIs('students.*') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            Élèves
        </a>

        <div class="nav-section">Notes & Résultats</div>

        <a href="{{ route('grades.index') }}" class="nav-item {{ request()->routeIs('grades.index') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M12 20h9"/>
                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
            </svg>
            Saisie des notes
        </a>

        <a href="{{ route('grades.results') }}" class="nav-item {{ request()->routeIs('grades.results') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
            Classement
        </a>

        <a href="{{ route('grades.export') }}" class="nav-item {{ request()->routeIs('grades.export') ? 'active' : '' }}">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            Export Excel
        </a>

        {{-- Année scolaire --}}
        <div style="margin:20px 4px 0; padding:12px 14px; background:rgba(255,255,255,0.03); border:1px solid rgba(74,158,130,0.12); border-radius:9px;">
            <div style="font-size:9.5px; color:#4a9e82; font-weight:700; text-transform:uppercase; letter-spacing:0.1em;">Année scolaire</div>
            <div style="font-size:15px; font-weight:800; color:#e2e8e5; margin-top:5px; letter-spacing:-0.01em;">2024 – 2025</div>
        </div>

    </nav>

    {{-- User bar --}}
    <div style="padding:12px 14px; border-top:1px solid rgba(255,255,255,0.05); background:rgba(0,0,0,0.15);">
        <div style="display:flex; align-items:center; gap:10px;">
            <div style="width:32px; height:32px; border-radius:8px; background:#374151; border:1px solid rgba(74,158,130,0.3); display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; color:#a7c4b9; flex-shrink:0;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div style="flex:1; min-width:0;">
                <div style="font-size:12.5px; font-weight:600; color:#d1d9d5; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ auth()->user()->name }}</div>
                <div style="font-size:10.5px; color:#4a9e82; margin-top:1px; font-weight:500;">{{ ucfirst(auth()->user()->role ?? 'Administrateur') }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Déconnexion"
                    style="width:28px; height:28px; border-radius:6px; border:none; background:transparent; color:#374a44; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all 0.15s;"
                    onmouseover="this.style.background='rgba(74,158,130,0.1)'; this.style.color='#6b8a7e'"
                    onmouseout="this.style.background='transparent'; this.style.color='#374a44'">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

</aside>

{{-- ══════════════════ MAIN ══════════════════ --}}
<div style="flex:1; display:flex; flex-direction:column; overflow:hidden;">

    {{-- Topbar --}}
    <header style="background:white; border-bottom:1px solid #e8ede9; padding:0 30px; height:54px; display:flex; align-items:center; justify-content:space-between; flex-shrink:0;">
        <div style="display:flex; align-items:center; gap:8px;">
            <span style="font-size:14px; font-weight:600; color:#0f172a;">@yield('title', 'Tableau de bord')</span>
        </div>
        <div style="display:flex; align-items:center; gap:14px;">
            <div style="display:flex; align-items:center; gap:5px; font-size:12px; color:#9ca3af; font-weight:400;">
                <span style="width:5px; height:5px; border-radius:50%; background:#4a9e82; display:inline-block;"></span>
                Système actif
            </div>
            <div style="width:1px; height:16px; background:#e5e7eb;"></div>
            <div style="font-size:12px; color:#b0b9b5; font-weight:500;">{{ now()->isoFormat('D MMM YYYY') }}</div>
        </div>
    </header>

    {{-- Flash messages --}}
    @if(session('success') || session('error'))
    <div id="flash-wrap" style="position:fixed; top:14px; right:18px; z-index:9999; min-width:290px;" class="flash-in">
        @if(session('success'))
        <div style="background:white; border:1px solid #d1fae5; border-left:3px solid #4a9e82; border-radius:9px; padding:11px 14px; display:flex; align-items:center; gap:10px; box-shadow:0 4px 18px rgba(0,0,0,0.08);">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#4a9e82" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span style="font-size:12.5px; font-weight:500; color:#065f46; flex:1;">{{ session('success') }}</span>
            <button onclick="document.getElementById('flash-wrap').remove()" style="border:none;background:none;cursor:pointer;color:#9ca3af;font-size:16px;line-height:1;padding:0;">×</button>
        </div>
        @endif
        @if(session('error'))
        <div style="background:white; border:1px solid #fecaca; border-left:3px solid #f87171; border-radius:9px; padding:11px 14px; display:flex; align-items:center; gap:10px; box-shadow:0 4px 18px rgba(0,0,0,0.08);">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#f87171" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span style="font-size:12.5px; font-weight:500; color:#991b1b; flex:1;">{{ session('error') }}</span>
            <button onclick="document.getElementById('flash-wrap').remove()" style="border:none;background:none;cursor:pointer;color:#9ca3af;font-size:16px;line-height:1;padding:0;">×</button>
        </div>
        @endif
    </div>
    <script>setTimeout(()=>{const e=document.getElementById('flash-wrap');if(e){e.style.transition='opacity 0.3s';e.style.opacity='0';setTimeout(()=>e.remove(),300);}},4500);</script>
    @endif

    {{-- Contenu page --}}
    <main style="flex:1; overflow-y:auto; padding:26px 30px;">
        @yield('content')
    </main>
</div>

</div>
</body>
</html>
