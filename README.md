# Estatify — Laravel Real Estate Platform

A full-stack real-estate application built with Laravel 13, Tailwind v4 and
Alpine.js. The project is intentionally built feature-by-feature so each stage
sits in its own commit and the codebase reads as a real product, not a tutorial.

> Several systems in this app — AI description generation, the price
> prediction estimator, the legal verification badge, and the digital
> signature display — are clearly labelled simulated demos for learning
> purposes only.

## Stack

- PHP 8.3+, Laravel 13
- Tailwind CSS 4 with native dark mode
- Alpine.js for client-side interactivity
- Chart.js (CDN) for analytics
- Spatie laravel-permission for RBAC
- barryvdh/laravel-dompdf for PDFs
- simplesoftwareio/simple-qrcode for QR codes
- SQLite by default (swap to MySQL/Postgres in `.env`)

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
npm install
npm run build       # or `npm run dev`
php artisan serve
```

Then visit http://localhost:8000.

## Demo credentials

The `DemoUserSeeder` creates these accounts (all use password `password`):

| Role  | Name           | Email                  |
|-------|----------------|------------------------|
| Admin | Sona Patel     | admin@estatify.test    |
| Agent | Vikram Joshi   | agent@estatify.test    |
| Agent | Priya Mehta    | priya@estatify.test    |
| User  | Rohan Sharma   | user@estatify.test     |
| User  | Anita Kapoor   | anita@estatify.test    |

The seeder also creates ~20 demo properties across cities and categories.

## Feature map

| Stage | What's in it |
|------:|--------------|
| 1 | Auth, role-based access, separate admin login, password reset |
| 2 | Property submission, media + demo documents, categories, featured/recently added |
| 3 | Listing page, detail page, search, filters, sorting, wishlist, side-by-side compare |
| 4 | AI description generator, recommended properties, demo price prediction, duplicate survey detection, demo legal verification |
| 5 | Map embeds, location pinning, image gallery slider, demo 360° tour |
| 6 | Buyer↔seller chat, appointment booking, in-app notifications |
| 7 | EMI calculator with full amortisation, investment return projector |
| 8 | QR property verification, approval workflow, registration tracking, demo digital signature |
| 9 | Admin dashboard with KPI cards, user management, most-viewed insights, monthly registration charts |
| 10 | Auto PDF generation for property reports and appointment receipts |
| 11 | Dark mode, responsive layouts, glassmorphism panels, animated cards |

## Routes overview

- `GET /` — landing page with featured listings.
- `GET /properties` — public browsing with filters and sorting.
- `GET /properties/{slug}` — listing detail, gallery, map, demo widgets.
- `GET /verify/{slug}` — QR-reachable verification page with demo signature.
- `GET /tools/emi`, `GET /tools/investment` — calculators.
- `GET /dashboard` — user dashboard.
- `GET /admin` — admin dashboard (requires admin role).
- `GET /admin/properties` — approval queue.
- `GET /admin/users` — user management.

## Demo / simulated systems

The following are *educational demos only* — they are **not** legally,
financially, or appraisally authoritative. Every demo system is tagged in
the UI with a small "Demo only" badge.

- AI property description generator (`App\Services\Demo\AiDescriptionService`)
- AI recommendation engine (`App\Services\Demo\RecommendationService`)
- Price prediction estimator (`App\Services\Demo\PricePredictionService`)
- Duplicate survey number detection (`App\Services\Demo\DuplicateSurveyService`)
- Legal verification badge (`App\Services\Demo\LegalVerificationService`)
- Digital signature display (rendered on the verification page)
- 360° property tour (cycles through uploaded images)
- Sample property documents (uploaded files marked as demo)

Toggle the demos in `.env` via:

```
DEMO_AI_DESCRIPTIONS=true
DEMO_PRICE_PREDICTION=true
DEMO_LEGAL_VERIFICATION=true
DEMO_DIGITAL_SIGNATURE=true
```

## Tests

```bash
php artisan test
```

Covers auth flows, property management permissions, and the calculator
services.

## Project layout

```
app/
  Actions/         single-purpose use-cases (Auth, Property)
  Enums/           role / status / type
  Events, Listeners, Jobs, Notifications, Policies
  Services/        business logic (search, media, analytics, pdf, calculators)
  Services/Demo/   simulated subsystems (AI, price, legal, recommendations)
  Http/Controllers thin controllers
  Http/Requests    form-request validation
resources/
  views/layouts    base layout, navbar, footer
  views/components reusable blade components (button, card, badge, etc.)
  views/auth       login / register / password flows
  views/property   index / show / create / edit / mine
  views/admin      admin dashboard, approvals, users
  views/tools      EMI + investment calculators
  views/verify     public QR verification page
  views/pdf        DomPDF templates
```
