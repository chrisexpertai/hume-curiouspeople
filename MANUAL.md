# Hume LMS — Full Developer & Administrator Manual

**Platform:** hume.curiouspeople.co.za
**Framework:** Laravel 10.21 (PHP 8.1+)
**Last Updated:** 2026-03-18

---

## Table of Contents

1. [Architecture Overview](#1-architecture-overview)
2. [Installation & Setup](#2-installation--setup)
3. [Environment Configuration](#3-environment-configuration)
4. [Database](#4-database)
5. [User Roles & Permissions](#5-user-roles--permissions)
6. [Course Management](#6-course-management)
7. [Assessments (Quizzes & Assignments)](#7-assessments-quizzes--assignments)
8. [Subscriptions & Payments](#8-subscriptions--payments)
9. [Plugins](#9-plugins)
10. [Media & File Storage](#10-media--file-storage)
11. [Email & Notifications](#11-email--notifications)
12. [Social Authentication](#12-social-authentication)
13. [Admin Dashboard](#13-admin-dashboard)
14. [Frontend & Theming](#14-frontend--theming)
15. [API Reference](#15-api-reference)
16. [Deployment](#16-deployment)
17. [Maintenance & Troubleshooting](#17-maintenance--troubleshooting)
18. [Security Considerations](#18-security-considerations)

---

## 1. Architecture Overview

### Stack

```
Browser → Nginx/Apache → Laravel 10 (PHP 8.1+)
                             ↓            ↓
                          MySQL       DigitalOcean Spaces (S3)
                       (Remote DB)      (CDN media)
```

### Application Layers

| Layer | Location | Purpose |
|---|---|---|
| Routes | `routes/web.php`, `routes/api.php` | URL → Controller mapping |
| Controllers | `app/Http/Controllers/` | Request handling, business logic |
| Models | `app/Models/` | Eloquent ORM, database relations |
| Views | `resources/views/` | Blade templates |
| Middleware | `app/Http/Middleware/` | Request pipeline (auth, roles, plugins) |
| Plugins | `app/Plugins/` | Modular feature extensions |
| Helpers | `app/helpers.php` | Global utility functions |
| Options | `app/options.php` | Key-value settings store (DB-backed) |

### Plugin Architecture

Plugins are loaded via `app/Http/Middleware/CheckPlugins.php`. Each plugin lives in `app/Plugins/{PluginName}/` and registers its own Service Provider. Active plugins:

- **Certificate** — PDF certificate generation
- **MultiInstructor** — Multiple instructors per course
- **StudentsProgress** — Detailed progress analytics

Custom plugins extend from `app/CustomPlugins/`.

### Filter / Hook System

The platform implements a WordPress-style filter/action hook system via `app/FilterManager.php`. Use `apply_piksera_filter($tag, $value, ...$args)` in helpers.php for extensible hooks across plugins.

---

## 2. Installation & Setup

### Server Requirements

- PHP 8.1+ with extensions: `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `gd` or `imagick`
- Composer 2+
- Node.js 16+ and npm
- MySQL 5.7+ or MariaDB 10.3+
- Web server: Nginx or Apache with `mod_rewrite`

### Step-by-Step Installation

```bash
# 1. Clone the repository
git clone https://github.com/chrisexpertai/hume-curiouspeople.git
cd hume-curiouspeople

# 2. Install PHP dependencies
composer install --optimize-autoloader --no-dev   # production
# OR
composer install                                   # development

# 3. Install Node dependencies
npm install

# 4. Configure environment
cp .env.example .env
php artisan key:generate

# 5. Edit .env with your database, storage, and service credentials
nano .env

# 6. Run database migrations
php artisan migrate

# 7. Seed initial data
php artisan db:seed

# 8. Create the storage symlink (public/storage → storage/app/public)
php artisan storage:link

# 9. Compile frontend assets
npm run dev       # development (unminified)
npm run prod      # production (minified)

# 10. Set correct file permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache public/uploads
```

### Web Server Configuration

**Nginx example:**

```nginx
server {
    listen 80;
    server_name hume.curiouspeople.co.za;
    root /var/www/hume-curiouspeople/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

**Apache (.htaccess** is already included in `public/.htaccess`).

---

## 3. Environment Configuration

The `.env` file controls all runtime configuration. **Never commit `.env` to version control.**

### Critical Variables

```dotenv
# Application
APP_NAME=Hume_Curious_People
APP_ENV=production          # local | staging | production
APP_DEBUG=false             # MUST be false in production
APP_URL=https://hume.curiouspeople.co.za
APP_KEY=                    # Generated by php artisan key:generate

# Database
DB_CONNECTION=mysql
DB_HOST=156.38.250.155
DB_PORT=3306
DB_DATABASE=humecppxxb_db2
DB_USERNAME=humecppxxb_2
DB_PASSWORD=

# Storage
FILESYSTEM_DRIVER=local     # local | s3
MEDIA_DEFAULT_STORAGE=public

# DigitalOcean Spaces (S3-compatible)
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=curious-people-lms
AWS_URL=https://curious-people-lms.nyc3.cdn.digitaloceanspaces.com
AWS_ENDPOINT=https://nyc3.digitaloceanspaces.com

# Stripe Payments
STRIPE_KEY=
STRIPE_SECRET=

# Mail
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@hume.curiouspeople.co.za
MAIL_FROM_NAME="${APP_NAME}"

# Cache & Sessions
CACHE_DRIVER=file           # file | redis | database
SESSION_DRIVER=file
SESSION_LIFETIME=120        # minutes
QUEUE_CONNECTION=sync       # sync | database | redis

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=error             # debug | info | warning | error
```

### Social Login Variables

Add these if using social authentication:

```dotenv
FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
FACEBOOK_REDIRECT_URL=https://hume.curiouspeople.co.za/login/facebook/callback

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URL=https://hume.curiouspeople.co.za/login/google/callback

TWITTER_CLIENT_ID=
TWITTER_CLIENT_SECRET=
TWITTER_REDIRECT_URL=https://hume.curiouspeople.co.za/login/twitter/callback

LINKEDIN_CLIENT_ID=
LINKEDIN_CLIENT_SECRET=
LINKEDIN_REDIRECT_URL=https://hume.curiouspeople.co.za/login/linkedin/callback
```

---

## 4. Database

### Structure Overview

The database has **69 migration files** covering all features. Key tables:

| Table | Purpose |
|---|---|
| `users` | All user accounts (students, instructors, admins) |
| `roles` | Role definitions |
| `courses` | Course records |
| `sections` | Course sections |
| `contents` | Course items (lessons, quizzes, assignments) |
| `lessons` | Lesson content |
| `enrolls` | Student-course enrollments |
| `completes` | Completion records per lesson |
| `tests` | Quiz definitions |
| `questions` | Quiz questions |
| `answers` | Answer options |
| `test_results` | Student quiz scores |
| `attempts` | Quiz attempt tracking |
| `assignment_submissions` | Assignment submissions |
| `subscription_plans` | Subscription plan definitions |
| `subscription_payments` | Payment transactions |
| `settings` | Key-value settings store |
| `posts` | Blog posts |
| `comments` | Post and course comments |
| `messages` | Direct messages |
| `notifications` | User notifications |
| `tickets` | Support tickets |
| `certificates` | Issued certificates |
| `earnings` | Instructor earnings |
| `withdraws` | Withdrawal requests |

### Running Migrations

```bash
# Run all pending migrations
php artisan migrate

# Rollback last batch
php artisan migrate:rollback

# Fresh install (drops all tables — DESTRUCTIVE)
php artisan migrate:fresh --seed

# Check migration status
php artisan migrate:status
```

### Database Seeder

The seeder creates initial admin user, roles, and settings:

```bash
php artisan db:seed
# Specific seeder:
php artisan db:seed --class=UserSeeder
```

### Settings System

Global settings are stored in the `settings` table and accessed via:

```php
get_option('setting_key', 'default_value');   // Read
update_option('setting_key', 'new_value');    // Write
```

Managed in the admin panel under **Settings → General**.

---

## 5. User Roles & Permissions

### Role Types

| Role | Capabilities |
|---|---|
| **Admin** | Full system access, manage all users/courses/payments |
| **Instructor** | Create/manage own courses, view own earnings |
| **Student** | Enroll in courses, take quizzes, submit assignments |

### Role-Based Middleware

Routes are protected by middleware in `routes/web.php`:

```php
->middleware('auth')           // Must be logged in
->middleware('admin')          // Must be admin
->middleware('instructor')     // Must be instructor
->middleware('CheckUserType')  // Checks specific user type
```

### Instructor Application Flow

1. Student applies to become instructor via **Dashboard → Become an Instructor**
2. Application stored in `teacher_applications` table
3. Admin reviews via **Admin → Teacher Applications**
4. On approval, user role is updated to `instructor`

### Account Deactivation

Users can deactivate their own accounts. Admins can reactivate via the admin panel. Deactivated users can also self-reactivate via `/reactivate`.

---

## 6. Course Management

### Course Structure

```
Course
  └── Section(s)
        └── Content items (lessons, quizzes, assignments)
              └── Lesson (text/video content)
              └── Test (quiz with questions/answers)
              └── Assignment (submission prompt)
```

### Creating a Course (Instructor)

1. Navigate to **Dashboard → My Courses → Create Course**
2. Fill in: Title, description, category, price, featured image
3. Add **Sections** to organize content
4. Within each section, add **Content** items:
   - **Lessons** — Text, video URL, or uploaded video file
   - **Quizzes** — Multiple choice/true-false questions
   - **Assignments** — File submission prompts
5. Add **Attachments** (downloadable resources)
6. Publish the course

### Video Handling

- Video metadata extracted via `james-heinrich/getid3` (ID3 tags)
- ID3 processor in `app/ID3/`
- Supports direct upload to local storage or DigitalOcean Spaces
- Video files excluded from git via `.gitignore` (`*.mp4`, `*.avi`, etc.)

### Course Enrollment

Students enroll via the course detail page. Enrollment types:
- **Free** — Instant enrollment
- **Paid** — Requires payment or active subscription
- **Subscription** — Covered by a subscription plan

Enrollment records stored in `enrolls` table.

### Progress Tracking

Completions tracked per content item in `completes` table. Progress percentage calculated dynamically in `DashboardController`.

---

## 7. Assessments (Quizzes & Assignments)

### Quiz System

**Quiz Controller:** `app/Http/Controllers/QuizController.php`

Quiz flow:
1. Student starts quiz (creates `Attempt` record)
2. Questions displayed one at a time or all at once
3. Answers submitted and scored server-side
4. Results stored in `test_results` and `test_result_answers`
5. Pass/fail determined by passing score threshold

**Question Types:**
- Multiple choice (single answer)
- True / False
- Multiple select (multiple correct answers)

### Assignments

**Assignment Controller:** `app/Http/Controllers/AssignmentController.php`

Assignment flow:
1. Student views assignment prompt
2. Submits text response or uploads file
3. Submission stored in `assignment_submissions`
4. Instructor reviews and optionally grades via admin/dashboard

---

## 8. Subscriptions & Payments

### Subscription Plans

Managed in `subscription_plans` table. Each plan defines:
- Name, description, price
- Billing period (monthly/yearly)
- Maximum courses accessible
- Features list (JSON)

Admin manages plans via **Admin → Subscription Plans**.

### Payment Flow (Stripe)

**Controllers:** `PaymentController`, `GatewayController`, `SubscriptionPaymentController`

1. User selects a plan → `/subscription/checkout`
2. Stripe Elements collects card details
3. Server creates PaymentIntent via Stripe SDK
4. On success, `subscription_payments` record created
5. User subscription activated
6. Renewal reminder sent before expiry

### Subscription Management Routes

```
GET  /subscriptions                         # List plans
POST /assign-subscription-plan/{plan_id}    # Subscribe
POST /renew-subscription/{plan_id}          # Renew
POST /switch-subscription-plan/{plan_id}    # Change plan
GET  /subscription/checkout                 # Checkout page
```

### Admin Payment Management

```
GET  /admin/subscription-payments           # Payment list
POST /admin/subscription-payments/update-status  # Update payment status
```

### Individual Course Payments

Courses can also be purchased individually (non-subscription). Cart system in `CartController` handles multi-course checkouts.

---

## 9. Plugins

### Plugin Architecture

Plugins live in `app/Plugins/{PluginName}/` and are loaded by `CheckPlugins` middleware. Each plugin has:

```
app/Plugins/PluginName/
  ├── PluginNameServiceProvider.php
  ├── Controllers/
  ├── Models/
  ├── migrations/
  └── views/
```

### Certificate Plugin

**Location:** `app/Plugins/Certificate/`

- Generates PDF certificates when a student completes a course
- Certificate templates managed in admin panel
- Uses mPDF for PDF rendering
- Certificates stored and accessible via unique URL
- Model: `Certificate`

### MultiInstructor Plugin

**Location:** `app/Plugins/MultiInstructor/`

- Allows multiple instructors to be assigned to a single course
- Each instructor can have different roles (lead, contributor)
- Earnings distributed among instructors

### StudentsProgress Plugin

**Location:** `app/Plugins/StudentsProgress/`

- Detailed analytics per student per course
- Progress charts and completion metrics
- Accessible by instructors for their enrolled students

### Adding a Custom Plugin

1. Create directory: `app/CustomPlugins/YourPlugin/`
2. Create a Service Provider extending Laravel's `ServiceProvider`
3. Register routes, views, and migrations within the provider
4. Activate via the admin plugins panel

---

## 10. Media & File Storage

### Storage Configuration

`config/filesystems.php` defines two disks:

```php
'local' => [
    'driver' => 'local',
    'root'   => storage_path('app'),
],
'public' => [
    'driver'     => 'local',
    'root'       => storage_path('app/public'),
    'url'        => env('APP_URL').'/storage',
    'visibility' => 'public',
],
's3' => [
    'driver'   => 's3',
    'key'      => env('AWS_ACCESS_KEY_ID'),
    'secret'   => env('AWS_SECRET_ACCESS_KEY'),
    'region'   => env('AWS_DEFAULT_REGION'),
    'bucket'   => env('AWS_BUCKET'),
    'url'      => env('AWS_URL'),
    'endpoint' => env('AWS_ENDPOINT'),
],
```

Switch between local and S3 via `FILESYSTEM_DRIVER` in `.env`.

### Image Processing

`config/media.php` defines image size presets:

| Preset | Usage |
|---|---|
| `thumbnail` | Course thumbnails |
| `avatar` | User profile pictures |
| `featured` | Course featured images |
| `banner` | Page banners |

Images resized by `intervention/image` on upload.

### File Manager (LFM)

Laravel File Manager (`unisharp/laravel-filemanager`) is integrated for rich media browsing. Accessible to admins and instructors. Configuration in `config/lfm.php`.

Routes: `/laravel-filemanager/*`

### Upload Helper

Use the global `uploadFile()` function from `app/helpers.php`:

```php
$path = uploadFile($request->file('image'), 'images/courses');
```

---

## 11. Email & Notifications

### Mail Configuration

Configure SMTP in `.env`. The platform supports:
- Standard SMTP
- Mailgun (via `MAILGUN_DOMAIN` / `MAILGUN_SECRET`)
- Amazon SES
- Postmark

### Mailable Classes

Located in `app/Mail/`. Laravel's `Mailable` class handles all transactional email. Email templates in `resources/views/emails/`.

Common notifications:
- Registration confirmation
- Password reset
- Enrollment confirmation
- Assignment/quiz result
- Subscription confirmation and renewal reminder
- Instructor application status

### In-App Notifications

Notifications stored in the `notifications` table. Displayed in the user dashboard notification bell. Based on Laravel's notification system.

---

## 12. Social Authentication

Powered by **Laravel Socialite** (`laravel/socialite: ^5.12`).

### Supported Providers

- Facebook
- Google
- Twitter
- LinkedIn

### Setup per Provider

1. Create OAuth app on the provider's developer console
2. Set redirect URL to: `https://hume.curiouspeople.co.za/login/{provider}/callback`
3. Add credentials to `.env` (see [Environment Configuration](#3-environment-configuration))
4. Enable the provider in admin **Settings → Social Login**

### Routes

```
GET /login/{provider}           # Redirect to provider
GET /login/{provider}/callback  # Handle OAuth callback
```

### Controller

`app/Http/Controllers/AuthController.php` handles the OAuth flow and user creation/linking.

---

## 13. Admin Dashboard

Access at `/admin` — requires admin role.

### Admin Sections

| Section | URL | Purpose |
|---|---|---|
| Dashboard | `/admin/dashboard` | Overview stats and activity |
| Users | `/admin/users` | Manage all user accounts |
| Courses | `/admin/courses` | Moderate and manage courses |
| Categories | `/admin/categories` | Course category management |
| Lessons | `/admin/lessons` | Lesson content management |
| Quizzes | `/admin/tests` | Quiz management |
| Blog Posts | `/admin/posts` | Blog content management |
| Pages | `/admin/pages` | Static page management |
| Menus | `/admin/menus` | Navigation menu builder |
| Tickets | `/admin/tickets` | Support ticket queue |
| Subscription Plans | `/admin/subscription-plans` | Plan management |
| Payments | `/admin/subscription-payments` | Payment history and status |
| Earnings | `/admin/earnings` | Instructor earnings overview |
| Withdrawals | `/admin/withdraws` | Withdrawal request management |
| Meetings | `/admin/meetings` | User meeting management |
| Analytics | `/admin/analytics` | Website usage statistics |
| Plugins | `/admin/plugins` | Activate/deactivate plugins |
| Settings | `/admin/settings` | System configuration |
| Custom CSS | `/admin/custom-css` | Custom stylesheet editor |

### Settings Management

System settings stored in `settings` table, editable via **Admin → Settings**. Settings are grouped:

- **General** — Site name, logo, favicon, contact info
- **Homepage** — Hero section, featured courses, sections
- **Payment** — Stripe keys, currency
- **Social Login** — OAuth credentials toggle
- **Email** — SMTP configuration
- **SEO** — Meta tags, analytics codes

---

## 14. Frontend & Theming

### Templates

Blade templates in `resources/views/`:

```
views/
  admin/          Admin panel views
  auth/           Login, register, password reset
  front/          Public-facing pages (home, course, blog, contact)
  sections/       Reusable partial sections
  subscription-plans/  Subscription pages
  emails/         Email templates
  layouts/        Base layout templates
```

### Asset Pipeline

Laravel Mix configured in `webpack.mix.js`.

```bash
npm run dev       # Compile for development
npm run prod      # Minify for production
npm run watch     # Watch for changes (development)
npm run hot       # Hot module replacement
```

Compiled assets output to `public/js/` and `public/css/`.

Source files:
- JavaScript: `resources/js/`
- Sass/SCSS: `resources/sass/`
- CSS: `resources/css/`

### Rich Text Editors

- **CKEditor 5** — Main course content editor
- **Summernote** — Blog and description editor
- **GrapesJS** — Page builder (for landing pages)

### Custom CSS

Admin-injectable custom CSS stored in `custom_css` table, applied site-wide. Edit via **Admin → Custom CSS**.

---

## 15. API Reference

### Authentication

API routes protected by Laravel Sanctum. Include token in requests:

```
Authorization: Bearer {your-token}
```

### Endpoints

```
GET /api/user          # Get the authenticated user's profile
```

The API is minimal — the application is primarily web-based with Blade-rendered views.

### CSRF Protection

All web form submissions require a CSRF token. In Blade templates:

```html
<form method="POST">
    @csrf
    ...
</form>
```

---

## 16. Deployment

### Using deploy.sh

The included `deploy.sh` script deploys changed files via SFTP:

```bash
# Set variables in deploy.sh before running
REMOTE_HOST=your-server.com
REMOTE_USER=your-ssh-user
REMOTE_PWD=your-password
REMOTE_DIR=/var/www/hume-curiouspeople
SFTP_PORT=22

bash deploy.sh
```

> **Note:** Store SFTP credentials in environment variables rather than hardcoding them in the script.

### Manual Deployment Steps

```bash
# On the server
cd /var/www/hume-curiouspeople

# Pull latest code
git pull origin main

# Install/update PHP dependencies (no dev packages)
composer install --optimize-autoloader --no-dev

# Run any new migrations
php artisan migrate --force

# Clear and rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Recompile assets (if not pre-compiled)
npm ci && npm run prod

# Set permissions
chmod -R 755 storage bootstrap/cache
```

### Caching Commands (Production)

```bash
php artisan config:cache     # Cache config files
php artisan route:cache      # Cache routes
php artisan view:cache       # Cache Blade views
php artisan event:cache      # Cache event/listener map

# Clear all caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan event:clear
```

### Queue Workers (if using database/redis queue)

```bash
php artisan queue:work --sleep=3 --tries=3 --daemon
```

Use Supervisor to keep queue workers running in production.

---

## 17. Maintenance & Troubleshooting

### Common Artisan Commands

```bash
php artisan migrate:status          # Check migration state
php artisan tinker                  # Interactive PHP shell
php artisan route:list              # List all routes
php artisan storage:link            # Recreate storage symlink
php artisan queue:work              # Process queued jobs
php artisan schedule:run            # Run scheduled tasks
php artisan down                    # Enable maintenance mode
php artisan up                      # Disable maintenance mode
```

### Log Files

- **Application Logs:** `storage/logs/laravel.log`
- **Nginx Logs:** `/var/log/nginx/error.log` (server)

Tail logs in real-time:

```bash
tail -f storage/logs/laravel.log
```

### File Permission Issues

```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Storage Symlink Issues

```bash
# Remove broken link and recreate
rm public/storage
php artisan storage:link
```

### Cache Problems

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload
```

### Database Connection Issues

1. Check `.env` for correct `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
2. Verify the remote MySQL server allows connections from your IP
3. Test connection: `php artisan tinker` → `DB::connection()->getPdo()`

### Composer Issues

```bash
# Regenerate autoloader
composer dump-autoload

# Update dependencies
composer update

# Clear Composer cache
composer clear-cache
```

### Node / Asset Issues

```bash
# Clean and reinstall
rm -rf node_modules package-lock.json
npm install
npm run dev
```

Or use the Makefile shortcut:

```bash
make clean
make dev
```

---

## 18. Security Considerations

### Environment Security

- **Never commit `.env`** — it contains database passwords, API keys, and the app encryption key
- Set `APP_DEBUG=false` in production — debug mode exposes stack traces
- Set `APP_ENV=production` in production
- Rotate `APP_KEY` if it may have been exposed (invalidates all sessions and encrypted data)

### Sensitive Files Protected by .gitignore

The following are excluded from version control:

- `.env` — all secrets
- `vendor/` — Composer packages (regenerated via `composer install`)
- `node_modules/` — npm packages
- `storage/` — user uploads, logs, cache
- `public/uploads/` — user-uploaded media
- `public/storage/` — storage symlink target
- `deploy.sh` — may contain SFTP credentials

### CSRF Protection

All POST/PUT/DELETE routes use CSRF verification via `VerifyCsrfToken` middleware. API routes use Sanctum token authentication.

### Input Sanitization

HTML input sanitized via `ezyang/htmlpurifier` through the `cleanhtml()` helper before storage.

### File Upload Security

- Uploaded files validated by type and size in Form Requests
- Files stored outside webroot where possible, or in `/storage` accessed via symlink
- Video/media files served via controller or CDN, not direct path

### SQL Injection

All database queries use Eloquent ORM or Query Builder with parameterized bindings. Never construct raw queries with user input.

### Access Control

- Admin routes protected by `admin` middleware
- Instructor routes protected by `instructor` middleware
- All authenticated routes behind `auth` middleware
- API routes protected by Sanctum (`auth:sanctum`)

### Stripe Security

- Stripe keys stored only in `.env`, never in code
- Use Stripe webhooks to verify payment events server-side
- Never log full card details

### Recommended Production Hardening

```bash
# Disable directory listing in Nginx
autoindex off;

# Set secure headers
add_header X-Frame-Options "SAMEORIGIN";
add_header X-Content-Type-Options "nosniff";
add_header Referrer-Policy "strict-origin-when-cross-origin";

# Use HTTPS only — redirect HTTP
server {
    listen 80;
    return 301 https://$host$request_uri;
}
```

---

*For questions or issues, raise a ticket in the admin support panel or contact the development team.*
