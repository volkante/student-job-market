# Data Model: Job Portal MVP

Date: 2025-12-23
Spec: [/specs/001-job-portal-basic/spec.md](/specs/001-job-portal-basic/spec.md)

## Entities

### Job

- id: integer (PK)
- title: string (required)
- company: string (required)
- location: string (city, required)
- salary: string (optional, free text)
- startDate: date (optional)
- employmentType: enum [full-time, part-time] (required)
- field: string (job field, required)
- email: string (required)
- status: enum [pending, approved] (default: pending)
- createdAt: datetime (auto)

#### Relationships

- Application (optional, future): not included in MVP but can be added later

#### Validation (minimal)

- title/company/location/field/email/employmentType: non-empty
- email: contains '@'
- startDate: valid date if provided

### User

- id: integer (PK)
- username: string (unique, required)
- passwordHash: string (required)
- role: enum [user, admin] (default: user)
- createdAt: datetime (auto)

#### Validation (minimal)

- username: non-empty, unique
- password: non-empty (hashed on save)

## State Transitions

- Job: `pending` → `approved` (action: admin approves)
- User: N/A

## Notes

- SQLite for storage in MVP; migrations optional; simple seed for sample jobs recommended.
