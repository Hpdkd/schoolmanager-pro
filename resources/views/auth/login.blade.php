<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — SchoolManager Pro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; display: flex; height: 100vh; overflow: hidden; background: #f6f8f7; }

        /* ── Left panel ───────────────────────────── */
        .left {
            width: 42%;
            background: #1f2937;
            display: flex;
            flex-direction: column;
            padding: 48px 52px;
            position: relative;
            overflow: hidden;
        }
        .left::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 380px; height: 380px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(74,158,130,0.12) 0%, transparent 70%);
            pointer-events: none;
        }
        .left::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -60px;
            width: 280px; height: 280px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(74,158,130,0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        /* ── Right panel ──────────────────────────── */
        .right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }
        .form-card {
            width: 100%;
            max-width: 400px;
        }

        /* ── Form elements ────────────────────────── */
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
            letter-spacing: 0.02em;
        }
        .form-input {
            width: 100%;
            height: 42px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            padding: 0 14px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: #0f172a;
            background: white;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-input:focus {
            border-color: #4a9e82;
            box-shadow: 0 0 0 3px rgba(74,158,130,0.1);
        }
        .form-input::placeholder { color: #c4c9c7; }

        .btn-login {
            width: 100%;
            height: 44px;
            background: #1f2937;
            color: white;
            font-family: 'Inter', sans-serif;
            font-size: 13.5px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            letter-spacing: 0.01em;
            transition: background 0.2s, transform 0.1s;
            margin-top: 6px;
        }
        .btn-login:hover  { background: #111827; }
        .btn-login:active { transform: scale(0.99); }

        /* ── Feature items ────────────────────────── */
        .feature { display: flex; align-items: flex-start; gap: 13px; }
        .feature-icon {
            width: 34px; height: 34px;
            border-radius: 8px;
            background: rgba(74,158,130,0.12);
            border: 1px solid rgba(74,158,130,0.2);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .feature-title  { font-size: 13px; font-weight: 600; color: #e2e8e5; margin-bottom: 2px; }
        .feature-desc   { font-size: 11.5px; color: #6b8a7e; line-height: 1.5; }

        .error-box {
            background: #fff5f5;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 12.5px;
            color: #b91c1c;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ── Divider ──────────────────────────────── */
        .divider {
            display: flex; align-items: center; gap: 10px;
            font-size: 11px; color: #d1d5db; margin: 18px 0;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1; height: 1px; background: #f0f2f1;
        }

        @media (max-width: 860px) {
            .left { display: none; }
        }
    </style>
</head>
<body>

{{-- ═══════════ PANNEAU GAUCHE ═══════════ --}}
<div class="left">

    {{-- Logo --}}
    <div style="display:flex; align-items:center; gap:11px; margin-bottom:auto;">
        <svg width="38" height="38" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="36" height="36" rx="10" fill="#111827"/>
            <path d="M8 10.5 C8 9.7 8.7 9 9.5 9 L16 9 L16 26 L9.5 26 C8.7 26 8 25.3 8 24.5 Z" fill="#4a9e82" opacity="0.85"/>
            <path d="M28 10.5 C28 9.7 27.3 9 26.5 9 L20 9 L20 26 L26.5 26 C27.3 26 28 25.3 28 24.5 Z" fill="#4a9e82" opacity="0.45"/>
            <line x1="18" y1="9" x2="18" y2="26" stroke="#111827" stroke-width="1.2"/>
            <circle cx="26.5" cy="26.5" r="6" fill="#1f2937" stroke="#4a9e82" stroke-width="1.5"/>
            <path d="M26.5 23.8 L27.1 25.7 L29.1 25.7 L27.5 26.9 L28.1 28.8 L26.5 27.6 L24.9 28.8 L25.5 26.9 L23.9 25.7 L25.9 25.7 Z" fill="#4a9e82"/>
        </svg>
        <div>
            <div style="font-size:15px; font-weight:800; color:#f1f5f2; letter-spacing:-0.02em;">SchoolManager</div>
            <div style="font-size:9px; font-weight:700; color:#4a9e82; letter-spacing:0.14em; text-transform:uppercase; margin-top:1px;">Pro Edition</div>
        </div>
    </div>

    {{-- Heading --}}
    <div style="padding:52px 0 48px;">
        <div style="font-size:11px; font-weight:700; color:#4a9e82; text-transform:uppercase; letter-spacing:0.12em; margin-bottom:14px;">Plateforme éducative</div>
        <h1 style="font-size:30px; font-weight:800; color:#f1f5f2; letter-spacing:-0.025em; line-height:1.2; margin-bottom:14px;">
            Gérez votre établissement<br>
            <span style="color:#4a9e82;">avec précision.</span>
        </h1>
        <p style="font-size:13.5px; color:#6b8a7e; line-height:1.7; max-width:320px;">
            Suivi des élèves, saisie des notes, génération de bulletins PDF et tableaux de bord analytiques — tout en un.
        </p>
    </div>

    {{-- Features --}}
    <div style="display:flex; flex-direction:column; gap:18px; margin-bottom:auto;">

        <div class="feature">
            <div class="feature-icon">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#4a9e82" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <div>
                <div class="feature-title">Gestion des élèves</div>
                <div class="feature-desc">Fiches complètes, inscriptions, historique académique</div>
            </div>
        </div>

        <div class="feature">
            <div class="feature-icon">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#4a9e82" stroke-width="2">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                </svg>
            </div>
            <div>
                <div class="feature-title">Bulletins & Classements</div>
                <div class="feature-desc">PDF automatiques, moyennes pondérées, export Excel</div>
            </div>
        </div>

        <div class="feature">
            <div class="feature-icon">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#4a9e82" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                </svg>
            </div>
            <div>
                <div class="feature-title">Tableau de bord analytique</div>
                <div class="feature-desc">Statistiques temps réel, graphiques de performance</div>
            </div>
        </div>

    </div>

    {{-- Footer --}}
    <div style="padding-top:32px; border-top:1px solid rgba(255,255,255,0.06);">
        <div style="font-size:11px; color:#374a44;">© 2024 SchoolManager Pro — Tous droits réservés</div>
    </div>

</div>

{{-- ═══════════ PANNEAU DROITE : FORMULAIRE ═══════════ --}}
<div class="right">
    <div class="form-card">

        {{-- Header --}}
        <div style="margin-bottom:32px;">
            <h2 style="font-size:22px; font-weight:800; color:#0f172a; letter-spacing:-0.02em; margin-bottom:6px;">Connexion</h2>
            <p style="font-size:13px; color:#9ca3af; font-weight:400;">Accédez à votre espace d'administration</p>
        </div>

        {{-- Erreurs --}}
        @if ($errors->any())
        <div class="error-box">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ $errors->first() }}
        </div>
        @endif

        {{-- Formulaire --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div style="margin-bottom:18px;">
                <label for="email" class="form-label">Adresse e-mail</label>
                <input id="email" name="email" type="email" autocomplete="email" required
                    class="form-input" placeholder="admin@ecole.fr"
                    value="{{ old('email') }}">
            </div>

            <div style="margin-bottom:22px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                    <label for="password" class="form-label" style="margin-bottom:0;">Mot de passe</label>
                    @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size:11.5px; color:#4a9e82; text-decoration:none; font-weight:500;">Mot de passe oublié ?</a>
                    @endif
                </div>
                <input id="password" name="password" type="password" autocomplete="current-password" required
                    class="form-input" placeholder="••••••••">
            </div>

            {{-- Se souvenir --}}
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:22px;">
                <input id="remember" name="remember" type="checkbox" style="width:15px; height:15px; accent-color:#4a9e82; cursor:pointer;">
                <label for="remember" style="font-size:12.5px; color:#6b7280; cursor:pointer; user-select:none;">Rester connecté</label>
            </div>

            <button type="submit" class="btn-login">
                Se connecter
            </button>

        </form>

        {{-- Crédentiels de démo --}}
        <div style="margin-top:28px; padding:14px 16px; background:#f8faf9; border:1px solid #e8edea; border-radius:9px;">
            <div style="font-size:10.5px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:8px;">Accès démonstration</div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <div style="font-size:11.5px; color:#6b7280; background:white; border:1px solid #e5e7eb; border-radius:6px; padding:5px 10px; cursor:pointer; transition:border-color 0.15s;"
                     onclick="document.getElementById('email').value='admin@school.com'; document.getElementById('password').value='password';"
                     onmouseover="this.style.borderColor='#4a9e82'"
                     onmouseout="this.style.borderColor='#e5e7eb'">
                    Admin
                </div>
                <div style="font-size:11.5px; color:#6b7280; background:white; border:1px solid #e5e7eb; border-radius:6px; padding:5px 10px; cursor:pointer; transition:border-color 0.15s;"
                     onclick="document.getElementById('email').value='teacher@school.com'; document.getElementById('password').value='password';"
                     onmouseover="this.style.borderColor='#4a9e82'"
                     onmouseout="this.style.borderColor='#e5e7eb'">
                    Enseignant
                </div>
            </div>
            <div style="font-size:11px; color:#b0b9b5; margin-top:6px;">Cliquez sur un rôle pour pré-remplir le formulaire</div>
        </div>

        <div style="text-align:center; margin-top:24px; font-size:11.5px; color:#d1d5db;">
            Plateforme sécurisée · Accès restreint au personnel autorisé
        </div>

    </div>
</div>

</body>
</html>
