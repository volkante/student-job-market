# Feature Specification: Job Portal MVP

**Feature Branch**: `001-job-portal-basic`  
**Created**: 2025-12-23  
**Status**: Draft  
**Input**: User description: "Bir anasayfa olacak, buradan şu an güncel olan iş ilanları görülebilecek. Anasayfadaki sade bir search bar ile bu ilanlar filtrelenebilecek. Bir tane de sade bir about sayfası olacak. Onun dışında anasayfadaki \"giriş \" butonuyla ulaşılan login sayfası olacak. burada kullanıcı adı ve şifreyle girilen bir kayıtlı kullanıcı bölümü olacak. bu bölümden şirketler ve/ya müşsteriler temel iş ilanı bilgilerini doldurup ilan girebilecekler. İşte bu ilanlar anasayfamızda yayınlanacak. Maaş, hangi il, ne zaman başlayacak, tam zamanlı mı yarı zamanlı mı ve iş alanı ve email bilgilerini dolduracakları bir form olacak verecekleri ilanla ilgili."

## User Scenarios & Testing _(mandatory)_

### User Story 1 - Browse & Filter Jobs (Priority: P1)

Students and visitors open the homepage and see a list of current job postings. They can type in a simple search bar to filter the list by keyword across job title, company, location, and job field.

**Why this priority**: Core value of the portal is discovering jobs quickly; delivers immediate utility without requiring login.

**Independent Test**: Open homepage → see at least 3 sample jobs → type a keyword → list updates to show matching jobs only.

**Acceptance Scenarios**:

1. **Given** jobs exist, **When** the user opens the homepage, **Then** the list shows current postings with basic fields.
2. **Given** the list is visible, **When** the user types "Ankara" in the search bar, **Then** only jobs with location or field containing "Ankara" are shown.

---

### User Story 2 - View Job Details (Priority: P2)

Users click a job in the list to view its full details on a dedicated page, including contact email and all provided fields.

**Why this priority**: Completes the discovery flow; users can see full information to decide to contact.

**Independent Test**: From the homepage, click any job → detail page loads → email contact is visible and clickable (mailto link).

**Acceptance Scenarios**:

1. **Given** the list is visible, **When** the user clicks a job title, **Then** a detail page shows title, company, location, salary, start date, full/part-time, job field, and email.

---

### User Story 3 - Login & Post Job (Priority: P3)

Companies/customers access a login page via the homepage “giriş” button. After logging in with username and password, they can post a job using a simple form: salary, city, start date, full-time/part-time, job field, and email. The job enters a pending state until an admin approves it.

**Why this priority**: Enables content creation for the portal with a simple moderation step.

**Independent Test**: Click “giriş” → login with valid credentials → open job form → submit → job is saved as pending and visible in the admin review list.

**Acceptance Scenarios**:

1. **Given** a valid account exists, **When** the user enters username/password and submits, **Then** access is granted to the posting area.
2. **Given** the posting form is visible, **When** the user fills all required fields and submits, **Then** the job is saved with status “pending”.

---

### User Story 4 - About Page (Priority: P4)

Users can open a simple About page describing the portal’s purpose.

**Why this priority**: Basic informational page common to most sites; supports credibility.

**Independent Test**: Open “About” from navigation → static content loads successfully.

**Acceptance Scenarios**:

1. **Given** the site navigation is visible, **When** the user clicks “About”, **Then** a static page with simple text is displayed.

---

### User Story 5 - Admin Approves Jobs (Priority: P3)

Admins log in to view a simple list of pending jobs and can approve them. Approved jobs become visible on the homepage.

**Why this priority**: Required for manual approval before publishing; keeps the MVP controlled and simple.

**Independent Test**: Admin logs in → opens pending jobs list → clicks approve on a job → job appears on homepage list.

**Acceptance Scenarios**:

1. **Given** pending jobs exist, **When** the admin opens the review list, **Then** all pending jobs are listed.
2. **Given** a pending job is listed, **When** the admin clicks approve, **Then** the job status changes to “approved” and the job appears on the homepage.

### Edge Cases

- Homepage shows an empty state if no jobs exist (message: “Şu an ilan bulunmuyor”).
- Search with no matches shows a message: “Eşleşme bulunamadı”.
- Login with incorrect credentials shows a generic error (“Geçersiz kullanıcı adı/şifre”).
- Job form missing required fields shows inline messages and prevents submission.
- Start date must be a valid date; if invalid, show a simple error.

## Requirements _(mandatory)_

### Functional Requirements

- **FR-001**: Homepage MUST display a list of current jobs with title, company, location, and job field.
- **FR-002**: Homepage MUST include a simple search bar that filters the list by keyword across title, company, location, and job field.
- **FR-003**: Users MUST be able to open a job detail page with all fields: salary, city, start date, full-time/part-time, job field, and contact email.
- **FR-004**: Users MUST be able to navigate to a simple About page from the homepage.
- **FR-005**: System MUST provide a login page reachable via a “giriş” button on the homepage.
- **FR-006**: System MUST authenticate users with username and password to access the job posting area.
- **FR-007**: Authenticated users MUST be able to create a job posting with fields: salary, city, start date, full-time/part-time, job field, and email.
- **FR-008**: Newly created job postings MUST be queued for admin review and appear on the homepage only after approval.
- **FR-009**: System MUST provide a logout option that returns the user to the homepage.
- **FR-010**: System MUST display clear messages for empty lists, no search matches, invalid login, and invalid form inputs.
- **FR-011**: Job detail MUST provide a simple way to contact via the provided email (e.g., clearly visible email address).
- **FR-012**: System MUST maintain a minimal, predictable navigation (Home, About, Login, Post Job).
- **FR-013**: Data entry MUST use sensible defaults (e.g., today’s date available in date picker).
- **FR-014**: Search MUST be case-insensitive and match partial words.
- **FR-015**: System MUST persist jobs and users so content and access remain across sessions.
- **FR-016**: System MUST support self-registration via a minimal signup form to create accounts with username and password.
- **FR-017**: System MUST provide an admin review flow to approve pending job postings before publishing.
- **FR-018**: Contact email MUST be publicly visible on the job detail page.

### Key Entities _(include if feature involves data)_

- **Job**: Represents a job posting; attributes include id, title, company, location (city), salary, start date, employment type (full-time/part-time), job field, contact email, status (pending/approved), createdAt.
- **User**: Represents a registered account that can log in and post jobs; attributes include id, username, password, role (company/customer/admin), createdAt.

## Dependencies & Assumptions

- Posting a job requires login; browsing and viewing details do not.
- Search filters by a single keyword across multiple fields (no advanced filters).
- Contact email is shown on the job detail page for all users (per Q3: A).
- Newly posted jobs appear after admin approval (per Q2: B).
- Accounts are created via self-registration (per Q1: B).

## Success Criteria _(mandatory)_

### Measurable Outcomes

- **SC-001**: Users can find a relevant job using the search bar in under 30 seconds.
- **SC-002**: Users can post a new job from login to publish in under 3 minutes.
- **SC-003**: 90% of users can navigate to the About page and back to Home without confusion.
- **SC-004**: 90% of job details show all required fields clearly (salary, city, start date, employment type, job field, email).
