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
- Tailwind CSS 4 (with native dark mode)
- Alpine.js for interactivity, Chart.js for analytics, Swiper for galleries
- Spatie Permission for RBAC
- Intervention Image for image processing
- barryvdh/laravel-dompdf for PDF generation
- simplesoftwareio/simple-qrcode for QR codes
- SQLite by default (swappable for MySQL/Postgres in `.env`)

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
npm install
npm run dev
```

Then visit http://localhost:8000 after `php artisan serve`.

## Project layout

```
app/
  Actions/         single-purpose application actions
  Enums/           role / status / type enums
  Events, Listeners, Jobs, Notifications, Policies
  Services/        business logic that does not fit a single action
  Http/Controllers thin controllers
  Http/Requests    form-request validation
resources/
  views/layouts    base layout, navbar, footer
  views/components reusable blade components
  views/auth       login / register / password flows
  views/property   listing, detail, create, edit
  views/admin      admin dashboard, analytics, approvals
  views/pages      static and demo info pages
```

## Demo credentials

Demo accounts are seeded via `DatabaseSeeder`. After running the seed:

| Role  | Email                | Password   |
|-------|----------------------|------------|
| Admin | admin@estatify.test  | password   |
| Agent | agent@estatify.test  | password   |
| User  | user@estatify.test   | password   |

## Feature stages

The build is split into 11 stages. Each is one or more commits with humanised
messages — search `git log` for the full breakdown.

1. Authentication and roles
2. Property management
3. Property browsing
4. Smart demo features
5. Maps and visualisation
6. Communication (chat, appointments, notifications)
7. Financial calculators (EMI + investment returns)
8. Verification (QR, approval workflow, demo signature)
9. Admin dashboard, analytics, approvals
10. Auto PDF receipts and property reports
11. UI / UX polish (dark mode, responsive, glassmorphism, animation)

## Demo / simulated systems

These features are *not* authoritative — they are educational demos:

- AI property description generator
- AI recommended properties
- Demo price prediction
- Demo legal verification status
- Demo digital signature
- Demo 360° viewer (image-based fallback)
- Demo property documents

Each is also labelled in the UI with a small "Demo only" tag.
