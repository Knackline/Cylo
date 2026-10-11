# Cylo

**The engineering control plane for AI-assisted development.**

Specialized agents triage, plan, and implement from tickets, Slack, and errors — not a blank chat. You own the gates. You merge the draft PR.

[![CI](https://github.com/Knackline/Cylo/actions/workflows/tests.yml/badge.svg)](https://github.com/Knackline/Cylo/actions/workflows/tests.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-forest.svg)](LICENSE)

---

## Why Cylo

Most AI coding tools start from an empty prompt. Cylo starts from **signals** your team already has — a tagged ticket, a Slack report, a labeled story, or a production error group — then routes work to **specialist agents** with human oversight at every critical gate.

| Principle | What it means |
| --- | --- |
| Signals in, jobs out | Intake is structured. The router authenticates, classifies, and dispatches. |
| Thin router, fat specialists | Triage, Fix, Feature, and Metrics own the business logic. |
| Humans own the gates | Low confidence, plan approval, and merge stay with people. |
| Agents never merge | Draft PRs labeled `built` wait for a human. |
| Learn loop | Every run feeds outcomes and a diagnosis knowledge base. |

## Features

- **Landing + auth** — Product narrative, Fortify auth, 2FA, passkeys
- **Mock dashboard** — Sample runs, human gates, and agent desk for demos (`/dashboard`)
- **Agent lifecycle (ADLC)** — Bug path (triage → fix → draft PR → human merge) and feature path (plan → approve → implement → draft PR)
- **Observability hooks** — Horizon queues, Telescope, structured run events (in progress)
- **Self-hostable** — Docker/FrankenPHP image ready for deployment

> The authenticated dashboard currently ships with **mock data** so contributors and demos can explore the control-plane UX before live integrations land.

## Stack

| Layer | Choice |
| --- | --- |
| Backend | PHP 8.3+ · Laravel 13 |
| Frontend | Vue 3 · Inertia v3 · TypeScript · Tailwind CSS v4 |
| Auth | Laravel Fortify · optional 2FA / passkeys |
| Queues | Redis · Laravel Horizon |
| AI | Laravel AI SDK |
| Permissions | Spatie Laravel Permission |
| Tests | Pest |

## Requirements

- PHP 8.3+ with extensions used by Laravel (`bcmath`, `intl`, `pdo_mysql`, `redis`, `zip`, …)
- Composer 2
- Node.js 20+ and npm
- MySQL 8+
- Redis (queues / Horizon)

Or use the included Docker image (FrankenPHP).

## Quick start

```bash
git clone https://github.com/Knackline/Cylo.git
cd Cylo

composer setup
```

`composer setup` installs PHP/JS dependencies, copies `.env.example` → `.env`, generates an app key, runs migrations, and builds frontend assets.

Then:

```bash
# Terminal 1 — app + Vite + queue + logs (via composer script)
composer dev
```

Or separately:

```bash
php artisan serve
npm run dev
```

Open [http://localhost:8000](http://localhost:8000), register a user, and visit **Dashboard** for the mock control plane.

### Environment

Copy and adjust `.env` as needed. Important defaults:

| Variable | Purpose |
| --- | --- |
| `APP_NAME` | `Cylo` |
| `DB_*` | MySQL connection (`cylo` by default) |
| `QUEUE_CONNECTION` | `redis` |
| `REDIS_*` | Queue / cache broker |
| `VITE_APP_NAME` | Frontend title |
| `VITE_GITHUB_URL` | Optional GitHub link on the landing page |

```env
VITE_GITHUB_URL=https://github.com/Knackline/Cylo
```

## Docker

A multi-stage `Dockerfile` builds a FrankenPHP image that serves the app on port 80 and runs migrations on start.

```bash
docker build -t cylo .
docker run --rm -p 8080:80 \
  -e APP_KEY=base64:YOUR_KEY \
  -e DB_HOST=host.docker.internal \
  -e DB_DATABASE=cylo \
  -e DB_USERNAME=root \
  -e DB_PASSWORD=password \
  cylo
```

Point `APP_URL` and DB/Redis env vars at your environment. See `.env.example` for the full list.

## Project layout

```text
app/                  # Laravel application code
resources/js/pages/   # Inertia Vue pages (Welcome, Dashboard, auth, settings)
resources/js/components/landing/  # Marketing / product story
routes/web.php        # Web routes
database/migrations/  # Schema
tests/Feature/        # Pest / PHPUnit feature tests
Dockerfile            # Production FrankenPHP image
```

## Scripts

| Command | Description |
| --- | --- |
| `composer setup` | First-time install |
| `composer dev` | Local development processes |
| `composer test` / `php artisan test` | Test suite |
| `vendor/bin/pint` | PHP style (Pint) |
| `npm run build` | Production frontend build |
| `npm run check` | Frontend checks |

## Contributing

1. Fork and create a feature branch from `main`.
2. Prefer the smallest change that solves the problem; match existing patterns.
3. Add or update tests for behavior changes.
4. Run the focused suite: `php artisan test --compact --filter=YourTest`.
5. Format PHP with `vendor/bin/pint --dirty`.
6. Open a PR with a clear summary and test plan.

Agent / AI contributors: follow `AGENTS.md` and the skills under `.github/skills/`.

## Security

Please do not open public issues for security vulnerabilities. Email the maintainers privately (see the GitHub org / repository security advisories once enabled).

## License

Cylo is open source under the [MIT License](LICENSE).
