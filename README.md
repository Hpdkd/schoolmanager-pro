# 🎓 SchoolManager Pro

> Plateforme de gestion scolaire complète — Laravel 13 · PHP 8.3 · MySQL · Vite 8

**🌐 [DEMO LIVE](https://schoolmanager-pro-production.up.railway.app)**
**Identifiants démo :** `admin@school.com` / `password`

---

## ✨ Fonctionnalités

| Module | Description |
|--------|-------------|
| 🏠 **Dashboard analytique** | KPIs temps réel, graphiques de performance, top élèves |
| 👥 **Gestion des élèves** | Fiches complètes, inscriptions, historique académique |
| 📝 **Saisie des notes** | Saisie en masse par classe/matière/semestre |
| 📊 **Classements** | Classement automatique S1 & S2 avec moyennes pondérées |
| 📄 **Bulletins PDF** | Génération automatique de bulletins officiels |
| 📈 **Export Excel** | Export des résultats par classe en format .xlsx |

## 🛠️ Stack technique

- **Backend** : Laravel 13 · PHP 8.3 · MySQL 8
- **Frontend** : Blade · Tailwind CSS · Vite 8 · Chart.js
- **Auth** : Laravel Breeze (session-based)
- **PDF** : Laravel DomPDF
- **Deploy** : Railway (Docker · CI/CD GitHub)

## 🚀 Installation locale

```bash
git clone https://github.com/Hpdkd/schoolmanager-pro.git
cd schoolmanager-pro

composer install
npm install && npm run build

cp .env.example .env
php artisan key:generate

# Configurer DB dans .env puis :
php artisan migrate --seed
php artisan serve
```

Accès : `http://localhost:8000` · `admin@school.com` / `password`

## 🐳 Déploiement Railway

Le projet est prêt pour Railway avec :
- `Dockerfile` optimisé PHP 8.3 + Node 20
- `railway.json` avec healthcheck sur `/health`
- Variables d'environnement via Railway reference variables

## 📁 Structure

```
app/
  Http/Controllers/
    DashboardController.php   # KPIs & statistiques
    StudentController.php     # CRUD élèves + bulletins PDF
    GradeController.php       # Notes, classements, exports
resources/views/
  dashboard/     # Tableau de bord
  students/      # Gestion élèves
  grades/        # Notes & résultats
database/
  migrations/    # Schéma complet
  seeders/       # 100 élèves + 1400+ notes de démo
```

## 👨‍💻 Auteur

**Hp** · Développeur Laravel Full-Stack
- Upwork : [profil](https://upwork.com)
- Email : hyppolitedakodo@gmail.com

---
*Projet portfolio — déployé sur Railway · Code source disponible*
