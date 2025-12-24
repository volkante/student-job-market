# Research: Job Portal MVP

Date: 2025-12-23
Branch: 001-job-portal-basic
Spec: [/specs/001-job-portal-basic/spec.md](/specs/001-job-portal-basic/spec.md)

## Decisions

- Decision: Use Symfony + Doctrine (SQLite) for backend

  - Rationale: Fast to scaffold; Doctrine entities map directly to simple Job/User models; SQLite avoids DB setup for MVP
  - Alternatives considered: Laravel (different ecosystem), raw PHP (more boilerplate), MySQL (heavier setup)

- Decision: Bundle frontend with Symfony Encore (Webpack)

  - Rationale: Native Symfony integration; easy to add TypeScript, SCSS, Bootstrap, jQuery in one pipeline
  - Alternatives considered: Vite (simple but extra integration), Webpack from scratch (more config work)

- Decision: Use TypeScript + jQuery + Bootstrap for UI

  - Rationale: Keep UI simple; Bootstrap provides layout and components; jQuery helps quick DOM interactions; TS adds type safety
  - Alternatives considered: React/Vue (overkill for MVP), plain JS (less structure)

- Decision: TYPO3 for content (Home/About/Contact) and linking to portal route

  - Rationale: Practice TYPO3 basics without deep integration; maintain content separately
  - Alternatives considered: Static pages in frontend (doesn’t exercise TYPO3 learning goal)

- Decision: Self-registration + admin approval flow
  - Rationale: Matches clarified requirements: Q1 (self-registration), Q2 (manual approval), Q3 (public email)
  - Alternatives considered: Immediate publish (no moderation), invite-only accounts

## Best Practices (kept minimal)

- Symfony Security: Use simple form login with in-DB users; password hashing via default encoder; skip advanced roles beyond `admin` and `user`.
- Doctrine: Basic migrations optional for SQLite; alternatively load 3 sample jobs via fixtures/seed script.
- Encore/Webpack: Add entries for `frontend/src/main.ts` and SCSS; include Bootstrap, jQuery via npm; enable source maps for learning.
- CORS: Allow frontend origin for JSON APIs; keep permissive for local dev.

## Patterns

- API pattern: REST JSON endpoints under `/api/*` for Jobs, Auth, and Admin approval.
- State transitions: Job `status`: `pending` → `approved` (via admin endpoint); `approved` jobs visible on homepage.

## Open Questions (none)

All clarifications resolved per Q1–Q3.
