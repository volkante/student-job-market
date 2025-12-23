# Student Job Market Constitution

## Core Principles

### I. Simplicity First

Build the smallest useful MVP: job list, job detail, apply to a job, and post a job. No authentication, no tests, no performance/security hardening for MVP. Prefer SQLite and minimal dependencies.

### II. Learn by Building

Use PHP with Symfony for the backend, TypeScript for a simple frontend, and TYPO3 for basic content pages. Prioritize readable, explicit code over abstractions. Keep file structure predictable.

### III. Clear Data Flow

Frontend calls backend JSON APIs; backend persists with Doctrine ORM; TYPO3 links users to the portal route. No background jobs, queues, or microservices—single repo, single app.

### IV. MVP-Only Scope

One endpoint per feature. Minimal validation (non-empty fields). Manual testing through the browser and curl/Postman. Defer enhancements until the MVP is demonstrated end-to-end.

### V. Consistency & Ownership

Single source of truth in this repo. Small, iterative commits. Document only what’s necessary to run and understand the app.

## Architecture & Tech Stack (Minimal)

- Backend (Symfony): PHP 8.2+, Symfony minimal skeleton, Doctrine ORM (SQLite), controllers for Jobs and Applications, YAML routing. CORS enabled for the frontend origin.
- Frontend (TypeScript): Simple Vite (or similar) setup, pages for Jobs List, Job Detail, Apply Form, and Post Job. A minimal router and fetch-based API client.
- CMS (TYPO3): Basic site with Home/About/Contact pages, a menu entry linking to the portal (`/portal`). No deep CMS integration in MVP.
- Database: SQLite (file-based) with two entities: `Job` and `Application`.
- Project Structure:
  - backend/
  - frontend/
  - cms/typo3/
  - docs/

## Minimal Data Model

- Job: `id`, `title`, `company`, `location`, `description`, `createdAt`
- Application: `id`, `jobId` (FK), `name`, `email`, `message`, `createdAt`

## Minimal Endpoints (JSON)

- GET `/api/jobs` → list jobs (id, title, company, location, createdAt)
- GET `/api/jobs/{id}` → job detail (includes description)
- POST `/api/applications` → submit an application (jobId, name, email, message)
- POST `/api/jobs` → create a job (title, company, location, description)

## Minimal Frontend Pages

- JobsList: shows list from GET `/api/jobs`; links to JobDetail.
- JobDetail: shows details from GET `/api/jobs/{id}`; link to ApplyForm.
- ApplyForm: posts to `/api/applications`; simple success message.
- PostJob: posts to `/api/jobs`; adds job to list.
- Main router/layout: route `/portal` renders JobsList.

## Development Workflow (Simple)

- Initialize folders and skeletons (Symfony in `backend/`, Vite TS in `frontend/`, TYPO3 in `cms/typo3/`).
- Use SQLite for ease; migrations optional—seed 3 sample jobs via fixtures or a simple seeder.
- Run backend and frontend locally; confirm endpoints with browser/curl.
- Commit after each task; keep messages clear (e.g., "US1: implement job list").
- Skip tests, auth, performance, and complex configs for MVP.

## Governance

This constitution guides the MVP and learning goals. Changes should remain minimal and be documented briefly in `docs/README.md`. Security, performance, and testing may be added after MVP.

**Version**: 0.1.0 | **Ratified**: 2025-12-23 | **Last Amended**: 2025-12-23

# [PROJECT_NAME] Constitution

<!-- Example: Spec Constitution, TaskFlow Constitution, etc. -->

## Core Principles

### [PRINCIPLE_1_NAME]

<!-- Example: I. Library-First -->

[PRINCIPLE_1_DESCRIPTION]

<!-- Example: Every feature starts as a standalone library; Libraries must be self-contained, independently testable, documented; Clear purpose required - no organizational-only libraries -->

### [PRINCIPLE_2_NAME]

<!-- Example: II. CLI Interface -->

[PRINCIPLE_2_DESCRIPTION]

<!-- Example: Every library exposes functionality via CLI; Text in/out protocol: stdin/args → stdout, errors → stderr; Support JSON + human-readable formats -->

### [PRINCIPLE_3_NAME]

<!-- Example: III. Test-First (NON-NEGOTIABLE) -->

[PRINCIPLE_3_DESCRIPTION]

<!-- Example: TDD mandatory: Tests written → User approved → Tests fail → Then implement; Red-Green-Refactor cycle strictly enforced -->

### [PRINCIPLE_4_NAME]

<!-- Example: IV. Integration Testing -->

[PRINCIPLE_4_DESCRIPTION]

<!-- Example: Focus areas requiring integration tests: New library contract tests, Contract changes, Inter-service communication, Shared schemas -->

### [PRINCIPLE_5_NAME]

<!-- Example: V. Observability, VI. Versioning & Breaking Changes, VII. Simplicity -->

[PRINCIPLE_5_DESCRIPTION]

<!-- Example: Text I/O ensures debuggability; Structured logging required; Or: MAJOR.MINOR.BUILD format; Or: Start simple, YAGNI principles -->

## [SECTION_2_NAME]

<!-- Example: Additional Constraints, Security Requirements, Performance Standards, etc. -->

[SECTION_2_CONTENT]

<!-- Example: Technology stack requirements, compliance standards, deployment policies, etc. -->

## [SECTION_3_NAME]

<!-- Example: Development Workflow, Review Process, Quality Gates, etc. -->

[SECTION_3_CONTENT]

<!-- Example: Code review requirements, testing gates, deployment approval process, etc. -->

## Governance

<!-- Example: Constitution supersedes all other practices; Amendments require documentation, approval, migration plan -->

[GOVERNANCE_RULES]

<!-- Example: All PRs/reviews must verify compliance; Complexity must be justified; Use [GUIDANCE_FILE] for runtime development guidance -->

**Version**: [CONSTITUTION_VERSION] | **Ratified**: [RATIFICATION_DATE] | **Last Amended**: [LAST_AMENDED_DATE]

<!-- Example: Version: 2.1.1 | Ratified: 2025-06-13 | Last Amended: 2025-07-16 -->
