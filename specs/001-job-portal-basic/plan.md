# Implementation Plan: Job Portal MVP

**Branch**: `001-job-portal-basic` | **Date**: 2025-12-23 | **Spec**: [/specs/001-job-portal-basic/spec.md](/specs/001-job-portal-basic/spec.md)
**Input**: Feature specification from `/specs/001-job-portal-basic/spec.md`

**Note**: This template is filled in by the `/speckit.plan` command. See `.specify/templates/commands/plan.md` for the execution workflow.

## Summary

MVP delivers: homepage with job listings + simple keyword search, job detail page, login/self-registration for companies/customers, job posting with admin approval, About page. Technical approach: single repo, Symfony backend with Doctrine (SQLite), JSON APIs, TypeScript frontend bundled via Webpack (Symfony Encore) using Bootstrap, SCSS, and jQuery for minimal UI, and TYPO3 for simple content pages linking to the portal.

## Technical Context

<!--
  ACTION REQUIRED: Replace the content in this section with the technical details
  for the project. The structure here is presented in advisory capacity to guide
  the iteration process.
-->

**Language/Version**: PHP 8.2+, TypeScript 5+, SCSS (Sass)  
**Primary Dependencies**: Symfony, Doctrine ORM, Symfony Encore (Webpack), Bootstrap 5, jQuery 3.x, TYPO3 (basic site)  
**Storage**: SQLite (file-based) for MVP  
**Testing**: N/A for MVP (explicitly skipped)  
**Target Platform**: Local dev on macOS; generic Linux server for deploy (later)  
**Project Type**: Web (backend + frontend + CMS)  
**Performance Goals**: None for MVP  
**Constraints**: Keep simple; minimal configuration; no security/performance hardening for MVP  
**Scale/Scope**: Single developer; minimal pages and endpoints

## Constitution Check

_GATE: Must pass before Phase 0 research. Re-check after Phase 1 design._

- Simplicity First: Only essential features included (browse, detail, login/signup, post, approve, about). PASS
- Learn by Building: Uses PHP+Symfony, TypeScript, TYPO3; clear file structure. PASS
- Clear Data Flow: Frontend → JSON API → Doctrine (SQLite); TYPO3 links to portal. PASS
- MVP-Only Scope: No tests/auth complexity beyond form login; minimal validation. PASS
- Consistency & Ownership: Single repo, small commits, quickstart doc. PASS

## Project Structure

### Documentation (this feature)

```text
specs/[###-feature]/
├── plan.md              # This file (/speckit.plan command output)
├── research.md          # Phase 0 output (/speckit.plan command)
├── data-model.md        # Phase 1 output (/speckit.plan command)
├── quickstart.md        # Phase 1 output (/speckit.plan command)
├── contracts/           # Phase 1 output (/speckit.plan command)
└── tasks.md             # Phase 2 output (/speckit.tasks command - NOT created by /speckit.plan)
```

### Source Code (repository root)

<!--
  ACTION REQUIRED: Replace the placeholder tree below with the concrete layout
  for this feature. Delete unused options and expand the chosen structure with
  real paths (e.g., apps/admin, packages/something). The delivered plan must
  not include Option labels.
-->

```text
backend/
├── src/
│   ├── Entity/
│   ├── Controller/
│   ├── Repository/
│   └── Kernel.php
├── config/
└── public/

frontend/
├── src/
│   ├── pages/
│   ├── components/
│   ├── styles/
│   └── api/
├── index.html
└── webpack.config.js (via Symfony Encore config)

cms/typo3/
└── (minimal TYPO3 sitepackage & pages)

docs/
└── README.md
```

**Structure Decision**: Web application with separate backend (Symfony), frontend (TypeScript + Webpack/Encore), and CMS (TYPO3). SQLite used for data. Minimal directories created as needed during implementation.

## Complexity Tracking

> **Fill ONLY if Constitution Check has violations that must be justified**

| Violation | Why Needed | Simpler Alternative Rejected Because |
| --------- | ---------- | ------------------------------------ |
| N/A       | —          | —                                    |
