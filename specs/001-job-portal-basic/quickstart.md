# Quickstart: Job Portal MVP

This MVP is for learning PHP, TYPO3, Symfony, TypeScript, Bootstrap, SCSS, Webpack, and jQuery. Keep it simple.

## Prerequisites

- macOS (dev)
- PHP 8.2+
- Composer
- Node.js 18+
- Git

## 1) Backend (Symfony)

```bash
# Create Symfony skeleton (inside repo root)
composer create-project symfony/skeleton backend

# From backend/, add Doctrine, Security, and Encore (Webpack)
cd backend
composer require orm symfony/security-bundle symfony/asset
composer require --dev symfony/webpack-encore-bundle

# Install frontend deps for bundling
npm init -y
npm install --save-dev @symfony/webpack-encore webpack webpack-cli typescript ts-loader sass sass-loader css-loader mini-css-extract-plugin
npm install bootstrap jquery

# Add TS/SCSS entry files
mkdir -p assets/{ts,styles}
printf "import 'bootstrap';\nimport '../styles/main.scss';\n" > assets/ts/main.ts
printf "@import 'bootstrap/scss/bootstrap';\n" > assets/styles/main.scss

# Build assets
./node_modules/.bin/encore dev

# Run backend
symfony server:start -d || php -S 127.0.0.1:8000 -t public
```

## 2) Frontend (TypeScript, optional separate SPA)

If you prefer a separate frontend folder:

```bash
# From repo root
mkdir -p frontend/src/{pages,components,api,styles}
# Use simple HTML with Bootstrap classes and include built bundle from backend or configure standalone Webpack here.
```

## 3) TYPO3 (basic)

```bash
# From repo root
mkdir -p cms/typo3
cd cms/typo3
composer create-project typo3/cms-base-distribution .
# Follow TYPO3 install wizard in browser to set up DB and create pages
```

## 4) Minimal Endpoints to Implement

- GET /api/jobs (approved only)
- GET /api/jobs/{id}
- POST /api/auth/signup
- POST /api/auth/login
- POST /api/auth/logout
- POST /api/jobs (creates pending)
- GET /api/admin/jobs/pending (admin)
- POST /api/admin/jobs/{id}/approve (admin)

See OpenAPI: [/specs/001-job-portal-basic/contracts/openapi.yaml](/specs/001-job-portal-basic/contracts/openapi.yaml)

## 5) Pages

- Home: List + search
- Detail: Full job info + visible email
- Login/Signup: Basic forms
- Post Job: Form for fields
- Admin: Pending jobs list + approve
- About: Static page (TYPO3)

## 6) Notes

- Use SQLite for MVP; keep validations minimal.
- Prefer Symfony Encore to bundle TypeScript, SCSS, Bootstrap, and jQuery.
- Keep commits small and descriptive.
