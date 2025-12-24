# Job Portal MVP - Student Learning Project

A simple job portal MVP built with Symfony, TypeScript, and Bootstrap for learning PHP, Symfony, TYPO3, TypeScript, and modern web development.

## 🚀 Quick Start

### Prerequisites

- PHP 8.4+ with extensions: pdo, pdo_sqlite, json, ctype, iconv
- Node.js 18+ and npm
- Symfony CLI (recommended) or PHP built-in server
- Composer

### Installation & Setup

1. **Clone and navigate:**

   ```bash
   git clone <repository-url>
   cd student-job-market
   ```

2. **Setup Backend (Symfony):**

   ```bash
   cd backend
   composer install
   npm install
   ```

3. **Configure Database:**

   ```bash
   # Database is SQLite, no configuration needed
   # Create database schema
   bin/console doctrine:migrations:migrate --no-interaction

   # Load sample data
   bin/console doctrine:fixtures:load --no-interaction
   ```

4. **Build Assets:**

   ```bash
   npm run build
   # Or for development with watch mode:
   npm run dev
   ```

5. **Start Development Server:**

   ```bash
   symfony serve -d
   # Or PHP built-in:
   php -S 127.0.0.1:8000 -t public/
   ```

6. **Access Application:**
   - Main Portal: http://127.0.0.1:8000
   - Login with test accounts: - Admin: `admin` / `admin123` - User: `user` / `user123`
     php bin/console doctrine:fixtures:load

# Run development server

php bin/console server:run

# or

symfony server:start

```

### Features

- ✅ Homepage with job listings
- ✅ Search/filter jobs by keyword
- ✅ Job detail pages with contact info
- ✅ User signup and login
- ✅ Companies can post jobs (pending approval)
- ✅ Admin approval for job posts
- ✅ Simple About page (via TYPO3)

### Project Structure

- `backend/` - Symfony application with API
- `cms/typo3/` - TYPO3 for content pages
- `specs/` - Feature specifications

### Development

- Assets: `npm run dev` (development) or `npm run build` (production)
- Database: SQLite (in `backend/var/data.db`)
- No tests, security hardening, or performance optimization in MVP

See [quickstart guide](../specs/001-job-portal-basic/quickstart.md) for detailed setup.
```
