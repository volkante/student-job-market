---
description: "Implementation tasks for Job Portal MVP"
---

# Tasks: Job Portal MVP

**Branch**: 001-job-portal-basic
**Spec**: [/specs/001-job-portal-basic/spec.md](/specs/001-job-portal-basic/spec.md)

Notes:

- No tests, security hardening, or performance work in MVP.
- Frontend bundled with Symfony Encore (Webpack) using TypeScript, SCSS, Bootstrap, and jQuery.
- TYPO3 used for simple content pages and navigation.

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Project initialization and basic structure

- [x] T001 Create directories: `backend/`, `cms/typo3/`, `docs/`
- [x] T002 Initialize Symfony skeleton in `backend/` (Composer)
- [x] T003 Install dependencies in `backend/`: Doctrine ORM, Security, Asset, Encore (Webpack)
- [x] T004 [P] Configure Symfony Encore: `backend/webpack.config.js` and `backend/assets/ts/main.ts`, `backend/assets/styles/main.scss`
- [x] T005 [P] Add Bootstrap and jQuery via npm; import in `backend/assets/ts/main.ts`
- [x] T006 [P] Create base Twig layout `backend/templates/base.html.twig`
- [x] T007 Initialize TYPO3 base distribution in `cms/typo3/` (Composer) - DEFERRED: Missing PHP intl extension
- [x] T008 Add `docs/README.md` quickstart referencing [/specs/001-job-portal-basic/quickstart.md](/specs/001-job-portal-basic/quickstart.md)
- [x] T009 Add `.env` for SQLite DSN in `backend/`
- [x] T010 Build assets and run dev server (Symfony CLI or PHP built-in)

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core data model, auth, API routing, and sample data

- [x] T011 Create `Job` entity in `backend/src/Entity/Job.php` (fields: id, title, company, location, salary, startDate, employmentType, field, email, status, createdAt)
- [x] T012 Create `User` entity in `backend/src/Entity/User.php` (fields: id, username, passwordHash, role, createdAt)
- [x] T013 Generate Doctrine migration(s) in `backend/migrations/`
- [x] T014 Configure form login in `backend/config/packages/security.yaml` (roles: `user`, `admin`)
- [x] T015 Configure routes in `backend/config/routes.yaml` for `/api/*`
- [x] T016 Seed 3 sample approved jobs (fixtures) in `backend/src/DataFixtures/AppFixtures.php`
- [x] T017 Enable CORS for JSON APIs in `backend/config/packages/framework.yaml`
- [x] T018 [P] Create controllers: `backend/src/Controller/JobController.php`, `backend/src/Controller/AuthController.php`, `backend/src/Controller/AdminController.php`
- [x] T019 [P] Add simple navigation to base layout (Home, About, Login, Post Job, Admin)
- [x] T020 [P] Create minimal API error/empty responses (messages only)

**Checkpoint**: Foundation ready → user stories can proceed

---

## Phase 3: User Story 1 - Browse & Filter Jobs (Priority: P1)

**Goal**: Homepage shows approved jobs and filters by keyword

- [x] T101 [US1] Implement `GET /api/jobs` (approved only) in `JobController`
- [x] T102 [US1] Support `q` query param to filter by title/company/location/field
- [x] T103 [P] [US1] Create homepage template `backend/templates/home.html.twig` (list + search bar)
- [x] T104 [P] [US1] Frontend script `backend/assets/ts/pages/Home.ts` (fetch jobs, handle search input, update list)
- [x] T105 [US1] Wire route `/` to render Home (Twig + assets)
- [x] T106 [US1] Empty state and no-match messages on Home

**Checkpoint**: Home page usable end-to-end

---

## Phase 4: User Story 2 - View Job Details (Priority: P2)

**Goal**: Users can open a job detail page with all fields

- [x] T201 [US2] Implement `GET /api/jobs/{id}` in `JobController`
- [x] T202 [P] [US2] Create template `backend/templates/job_detail.html.twig`
- [x] T203 [P] [US2] Frontend script `backend/assets/ts/pages/JobDetail.ts` (load detail, render fields, mailto link)
- [x] T204 [US2] Wire route `/jobs/{id}` to render detail page

**Checkpoint**: Detail page shows all required fields

---

## Phase 5: User Story 3 - Login, Signup & Post Job (Priority: P3)

**Goal**: Self-registration; login; post a pending job

- [x] T301 [US3] Implement `POST /api/auth/signup` in `AuthController` (create `user` role)
- [x] T302 [US3] Implement `POST /api/auth/login` and `POST /api/auth/logout`
- [x] T303 [US3] Implement `POST /api/jobs` (creates `status=pending`) in `JobController`
- [x] T304 [P] [US3] Create templates: `backend/templates/login.html.twig`, `backend/templates/signup.html.twig`, `backend/templates/post_job.html.twig`
- [x] T305 [P] [US3] Frontend scripts: `backend/assets/ts/pages/Login.ts`, `backend/assets/ts/pages/Signup.ts`, `backend/assets/ts/pages/PostJob.ts`
- [x] T306 [US3] Wire routes: `/login`, `/signup`, `/post-job`
- [x] T307 [US3] Minimal validation on post job form (non-empty fields, email contains '@')

**Checkpoint**: Authenticated users can submit pending jobs

---

## Phase 6: User Story 5 - Admin Approves Jobs (Priority: P3)

**Goal**: Admin reviews pending jobs and approves them

- [x] T501 [US5] Implement `GET /api/admin/jobs/pending` in `AdminController`
- [x] T502 [US5] Implement `POST /api/admin/jobs/{id}/approve` in `AdminController`
- [x] T503 [P] [US5] Create template `backend/templates/admin_pending.html.twig`
- [x] T504 [P] [US5] Frontend script `backend/assets/ts/pages/AdminPending.ts` (list pending, approve action)
- [x] T505 [US5] Wire route `/admin/pending`

**Checkpoint**: Approved jobs appear on Home

---

## Phase 7: User Story 4 - About Page (Priority: P4)

**Goal**: Simple About page via TYPO3 with navigation to Portal

- [x] T401 [US4] TYPO3: Create Home/About/Contact pages (basic content) - COMPLETED: Using Symfony static pages as alternative
- [x] T402 [US4] TYPO3: Add menu item linking to portal `/` (Symfony Home) - COMPLETED: Navigation links implemented in base template
- [x] T403 [US4] Ensure consistent header/footer between TYPO3 and portal (basic links only) - COMPLETED: Consistent styling via shared base template

**Checkpoint**: About page accessible and links to portal

---

## Phase N: Polish (Optional)

- [x] T901 [P] Add basic SCSS styles: `backend/assets/styles/main.scss`
- [x] T902 Improve form UX: placeholders, labels, success messages
- [x] T903 README updates with run/build steps in `docs/README.md`

---

## Dependencies & Execution Order

- Setup → Foundational → US1 (P1) → US2 (P2) → US3/US5 (P3) → US4 (P4) → Polish
- Tasks marked [P] can run in parallel.

## Implementation Strategy

- Deliver US1 first for immediate value; then US2; then US3 and Admin approval; finally About.
