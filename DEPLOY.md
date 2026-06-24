# 🚀 Déploiement SchoolManager Pro sur Railway

## Prérequis
- Compte GitHub avec le projet poussé
- Compte Railway : https://railway.app (gratuit, pas de carte bancaire)

---

## Étape 1 — Pousser le code sur GitHub

```bash
# Dans D:\PROJETS\SchoolManager
git init
git add .
git commit -m "feat: SchoolManager Pro v1.0"
git remote add origin https://github.com/TON_USERNAME/schoolmanager-pro.git
git push -u origin main
```

---

## Étape 2 — Créer le projet Railway

1. Aller sur https://railway.app → **New Project**
2. Choisir **Deploy from GitHub repo**
3. Sélectionner le repo `schoolmanager-pro`
4. Railway détecte automatiquement Laravel via `nixpacks.toml` ✅

---

## Étape 3 — Ajouter MySQL

1. Dans le projet Railway → **+ New Service** → **Database** → **MySQL**
2. Railway crée automatiquement les variables :
   - `MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, `MYSQLUSER`, `MYSQLPASSWORD`
3. Ces variables sont déjà référencées dans `railway.json` ✅

---

## Étape 4 — Configurer les variables d'environnement

Dans Railway → **Variables** du service Laravel, ajouter :

| Variable      | Valeur                                        |
|---------------|-----------------------------------------------|
| `APP_NAME`    | `SchoolManager Pro`                           |
| `APP_ENV`     | `production`                                  |
| `APP_DEBUG`   | `false`                                       |
| `APP_KEY`     | *(générer : `php artisan key:generate --show`)* |
| `APP_URL`     | *(l'URL Railway, ex: `https://xxx.up.railway.app`)* |
| `DB_CONNECTION` | `mysql`                                     |
| `DB_HOST`     | `${{MySQL.MYSQLHOST}}`                        |
| `DB_PORT`     | `${{MySQL.MYSQLPORT}}`                        |
| `DB_DATABASE` | `${{MySQL.MYSQLDATABASE}}`                    |
| `DB_USERNAME` | `${{MySQL.MYSQLUSER}}`                        |
| `DB_PASSWORD` | `${{MySQL.MYSQLPASSWORD}}`                    |
| `CACHE_STORE` | `file`                                        |
| `SESSION_DRIVER` | `file`                                     |
| `LOG_CHANNEL` | `stderr`                                      |

> **Tip Railway** : dans les variables, utiliser `${{MySQL.MYSQLHOST}}` lie automatiquement la variable du service MySQL.

---

## Étape 5 — Obtenir l'APP_KEY

Sur votre machine locale :

```bash
cd D:\PROJETS\SchoolManager
php artisan key:generate --show
# Copier la valeur base64:... dans Railway → APP_KEY
```

---

## Étape 6 — Déployer

1. Railway lance automatiquement le build après la config
2. Surveiller les logs dans l'onglet **Deployments**
3. Le script de démarrage exécute :
   ```
   php artisan migrate --seed --force
   php artisan serve --host=0.0.0.0 --port=$PORT
   ```
4. Après ~2 min → votre URL publique apparaît dans **Settings → Domains**

---

## Étape 7 — Générer un domaine personnalisé (optionnel)

Dans Railway → **Settings** → **Networking** → **Generate Domain**
→ Vous obtenez : `schoolmanager-pro.up.railway.app`

Pour un domaine custom (ex: `demo.monsite.com`) :
→ Ajouter un enregistrement CNAME chez votre hébergeur DNS

---

## Identifiants de démonstration

| Rôle         | Email                  | Mot de passe |
|--------------|------------------------|--------------|
| Administrateur | admin@school.com     | password     |
| Enseignant   | mensah@school.com      | password     |

---

## ⚠️ Points d'attention

- **Sessions** : configurées en `file` (OK pour démo, utiliser `database` en production réelle)
- **Storage** : `php artisan storage:link` est exécuté au build
- **Queue** : `sync` (pas de worker séparé nécessaire)
- **Re-seed** : si vous voulez réinitialiser la base → `php artisan migrate:fresh --seed --force` dans le terminal Railway

---

## Résolution de problèmes courants

| Erreur | Solution |
|--------|----------|
| `APP_KEY not set` | Ajouter `APP_KEY` dans les variables Railway |
| `SQLSTATE[HY000]` | Vérifier que les variables DB_* pointent vers le service MySQL |
| `Class not found` | `composer dump-autoload` puis redéployer |
| Page blanche | Passer `APP_DEBUG=true` temporairement pour voir l'erreur |
| CSS cassé | Vérifier que `npm run build` s'est bien exécuté dans les logs |
