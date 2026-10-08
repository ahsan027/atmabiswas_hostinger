# ATMABISWAS Backend Deployment & Operations Guide

## 1. Project Summary

ATMABISWAS is a PHP-based website project with a custom backend administration system for managing content, user access, blog publishing, career/job operations, notices, and other dynamic website data. The project uses a direct PHP architecture rather than a framework, with modular backend scripts organized under the backend folder and a MySQL database handled through PDO.

This README is focused on backend deployment, system setup, configuration, environment handling, and operational use in hosting environments such as Hostinger, cPanel, or other PHP-enabled servers.

---

## 2. Backend Architecture Overview

```text
Browser Request
   |
   v
Public PHP Pages (root folder)
   |
   +--> backend/ modules and processing scripts
            |
            +--> Session validation
            +--> Database queries via PDO
            +--> Image/file upload checks
            +--> Form sanitization and validation
            +--> Redirect or JSON response
            +--> MySQL data storage
```

### Primary backend elements

- backend/Database/db.php : centralized database connection
- backend/login/ : admin authentication and login routes
- backend/DashBoard/ : dashboard and management interfaces
- backend/blogUpload_process.php : blog creation/publishing logic
- backend/addJob_processing.php : career/job insertion logic
- backend/blogSanitizer.php : HTML sanitization for blog content
- backend/*_processing.php : specific business logic endpoints

---

## 3. Technology Stack

- PHP
- MySQL / MariaDB
- PDO database layer
- Composer dependency manager
- HTML/CSS/JS frontend
- PHPMailer for email functionality
- HTML Purifier for content sanitization
- Session-based admin authentication

### Composer dependencies

```json
{
  "require": {
    "ezyang/htmlpurifier": "^4.18",
    "phpmailer/phpmailer": "^6.10"
  }
}
```

---

## 4. Repository Structure Relevant to Backend

```text
.
├── backend/
│   ├── Database/
│   │   ├── db.php
│   │   ├── db.sql
│   │   ├── atmabiswas.sql
│   │   ├── admin_users_table.sql
│   │   ├── notices.sql
│   │   ├── about_us_migration.sql
│   │   ├── blog_schema_update.sql
│   │   ├── fix_ampersand.sql
│   │   ├── migrate.php
│   │   └── README.md
│   │
│   ├── DashBoard/
│   │   ├── dashboard.php
│   │   ├── blog_manager.php
│   │   ├── blog.php
│   │   ├── blog_content.php
│   │   ├── blog_edit.php
│   │   ├── createjob.php
│   │   ├── addJobPosition.php
│   │   ├── adminSignup.php
│   │   ├── manageAdmins.php
│   │   ├── csrf_helper.php
│   │   └── ...
│   │
│   ├── login/
│   │   ├── prelogin.php
│   │   ├── loging.php
│   │   ├── logout.php
│   │   └── ...
│   │
│   ├── addJob_processing.php
│   ├── blogUpload_process.php
│   ├── blogSanitizer.php
│   ├── blogContentImage_upload.php
│   ├── deleteJobPositions.php
│   ├── deleteSector.php
│   ├── get_job_code.php
│   ├── get_job_position.php
│   ├── getBranchNumber.php
│   ├── sendingMail.php
│   └── ...
│
├── config.php
├── composer.json
├── index.php
├── career.php
├── contact.php
├── aboutus.php
├── press.php
├── notice.php
├── Events.php
├── uploads/
├── vendor/
├── README.MD
└── ...
```

---

## 5. Database Configuration and Connection Details

### Main database connection file

The core database logic is in:

- backend/Database/db.php

This file uses a singleton pattern and PDO connection manager.

```php
class Db {
    private static $instance = null;
    private $hostname = "localhost";
    private $user = "u106340611_arafat";
    private $pswd = "MacBook@007Arafat";
    private $dbname = "u106340611_arafatbiswas";
}
```

### Environment override support

The code attempts to read database values from environment variables when available:

```php
if (getenv('DB_HOST')) $this->hostname = getenv('DB_HOST');
if (getenv('DB_USER')) $this->user     = getenv('DB_USER');
if (getenv('DB_PASS') !== false) $this->pswd = getenv('DB_PASS');
if (getenv('DB_NAME')) $this->dbname   = getenv('DB_NAME');
```

### Production recommendation

This project currently contains hardcoded values in the source. For production use, these should be moved to:

- environment variables
- .env files (if using a loader)
- server config or hosting panel variables
- a secure secret management layer

This is a critical backend hardening step before deployment to public hosting.

---

## 6. Deployment Setup

### 6.1 Requirements

Your deployment environment should have:

- PHP 8.x or compatible version
- MySQL database with proper permissions
- Apache or Nginx web server
- Composer installed
- writable uploads and temp directories
- SSL enabled for production

### 6.2 Install dependencies

From the project root:

```bash
composer install
```

### 6.3 Database import

Use the SQL files in backend/Database/ to initialize the schema and seed required tables.

Suggested order:

```text
atmabiswas.sql
admin_users_table.sql
blog_schema_update.sql
notices.sql
about_us_migration.sql
fix_ampersand.sql
```

Then validate the resulting table structure before login or content management operations.

### 6.4 Configuration for production

Update backend/Database/db.php to use the live database credentials or inject environment variables.

Example environment variables:

```bash
DB_HOST=localhost
DB_USER=your_db_user
DB_PASS=your_secure_password
DB_NAME=your_database_name
```

### 6.5 Start the app

On local development environment:

```bash
php -S localhost:8000
```

On hosting environment, upload the project files to the web root or subdirectory and ensure PHP is enabled.

---

## 7. Admin Authentication Flow

The backend authentication pattern is implemented through PHP sessions.

Typical access check:

```php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: /backend/login/loging.php");
    exit();
}
```

### Administrative login flow

1. User lands on the login page from backend/login/
2. Credentials are submitted to authentication logic
3. Session is created on successful login
4. Protected pages verify the session before continuing
5. logout.php destroys session state on sign out

### Important hosting note

Because session-based auth depends on server-side cookies, the site must be served with a correctly configured domain and secure cookie policy. On production, use HTTPS and make sure the session cookie domain/path settings are appropriate.

---

## 8. Key Backend Modules and What They Do

### 8.1 Blog publishing module

File:

- backend/blogUpload_process.php
- backend/blogSanitizer.php

Responsibilities:

- validate blog title, summary, and content
- sanitize HTML content before saving
- generate slug values for SEO-friendly URLs
- generate reading time
- validate upload type and size
- store cover image in uploads/blog_imgs/
- insert records into blogs table
- set published vs draft status

Important validation logic:

```php
if (empty($title)) throw new Exception('Press title is required.');
if (empty($content)) throw new Exception('Press content is required.');
if (!isset($_FILES['thumbnail']) || $_FILES['thumbnail']['error'] !== UPLOAD_ERR_OK) {
    throw new Exception('Press thumbnail is required.');
}
```

### 8.2 Job management module

Files:

- backend/addJob_processing.php
- backend/deleteJobPositions.php
- backend/get_job_code.php
- backend/get_job_position.php
- backend/DashBoard/addJobPosition.php

Responsibilities:

- prevent duplicate job titles
- generate dynamic job codes
- insert into jobcodes and sectors tables
- support dashboard-driven job creation and deletion

### 8.3 Dashboard management module

Files:

- backend/DashBoard/dashboard.php
- backend/DashBoard/blog_manager.php
- backend/DashBoard/manageAdmins.php
- backend/DashBoard/createjob.php
- backend/DashBoard/updatejob.php

Responsibilities:

- admin UI for content and data maintenance
- listing and update flow for records
- page generation and form submission handling
- business logic for editing published content and job info

### 8.4 Mail functionality

File:

- backend/sendingMail.php

This module suggests the backend can send email communication from the application, potentially for contact/inquiry or admin notifications.

---

## 9. Security Practices Already Present

This backend includes several good security patterns:

- session checks before protected pages
- prepared statements with PDO
- HTML sanitization before storing content
- upload validation for MIME type and file size
- no-cache headers in config.php
- CSRF helper support in backend/DashBoard/csrf_helper.php

### Example of security-focused sanitization

```php
require_once __DIR__ . '/blogSanitizer.php';
$content = sanitize_blog_html($_POST['blog_content'] ?? '');
```

### Example of upload restriction

```php
$allowedThumbTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
if (!array_key_exists($mime, $allowedThumbTypes)) {
    throw new Exception('Thumbnail must be a JPG, PNG, or WebP image.');
}
```

---

## 10. Security Hardening Before Production

Before public deployment, strongly consider the following:

1. Remove hardcoded credentials from source files
2. Move DB settings to environment variables
3. Add a production .env loader or server-level config
4. Enforce HTTPS with secure cookies and HSTS
5. Restrict admin folders with stronger role-based access
6. Validate all POST/GET input consistently
7. Add logging for critical actions (admin login, blog publish, job create/delete)
8. Review file upload directories for direct public access
9. Add server-side rate-limiting for forms and login attempts
10. Use a proper secret manager for production credentials

---

## 11. Hosting Specific Recommendations

### For Hostinger / cPanel style hosting

- upload all files to the public_html directory or project subfolder
- create the database in MySQL manager
- import SQL schema files
- set writable permissions on upload folders such as uploads/blog_imgs/
- ensure php.ini supports required extensions like PDO MySQL
- configure domain root correctly for routing to index.php

### File permission review

Suggested checks:

```bash
chmod 755 uploads
chmod 755 uploads/blog_imgs
chmod 644 config.php
```

Use caution and adapt according to your host environment.

---

## 12. Operational Workflow

### Normal admin workflow

1. Admin logs in
2. User reaches dashboard panel
3. Creates/updates blog articles or job listings
4. Server validates and sanitizes input
5. Data writes to MySQL tables
6. Files are uploaded or stored in uploads/
7. System redirects with success or error feedback

### Typical backend transaction lifecycle

```text
Form POST -> validation -> sanitization -> database insert/update -> redirect -> UI refresh
```

---

## 13. Backend Risks and Observations

This project is functional, but some areas should be improved before broader production use:

- credentials are embedded in source code
- access control is session-based but not strongly role-based
- some backend flows are script-centric and not standardized across modules
- consistent API response handling is not fully enforced
- logging and monitoring are minimal

These are common in custom PHP systems but should be addressed as the project grows.

---

## 14. Recommended Future Improvements

- centralize configuration into env files
- create a reusable admin middleware/check helper
- standardize all controllers/processors under one backend pattern
- add audit logs for content changes and admin actions
- create automated tests for blog creation and job insertion
- support database migration tooling instead of ad hoc SQL imports
- improve role-based permissions for admins

---

## 15. Quick Setup Checklist

```text
1. Install PHP + MySQL
2. Run composer install
3. Import SQL files in backend/Database/
4. Update database credentials
5. Set file permissions for uploads
6. Start PHP server or deploy to hosting
7. Access admin login route
8. Verify dashboard works and content creation succeeds
```

---

## 16. Final Note

The ATMABISWAS backend is a custom PHP content-management and administration framework built for a small-to-medium organization website. It is modular, practical, and database-driven, but for stable production use it should be hardened by moving sensitive configuration, improving authorization, and standardizing backend workflows.

This README is intentionally focused on deployment, hosting readiness, and backend operations for real-world use.


- database queries
- permission checks
- HTML form rendering
- create/update/delete operations
- redirect and success/error messaging

---

### 5.4 Blog Publishing Backend

The blog publishing system is one of the most important backend features in this project.

Files involved:

- backend/blogUpload_process.php
- backend/blogSanitizer.php
- backend/blogContentImage_upload.php
- backend/DashBoard/blog_manager.php
- backend/DashBoard/blog.php
- backend/DashBoard/blog_edit.php
- backend/DashBoard/blog_content.php

#### Responsibilities

- sanitizing HTML content before storing it in DB
- validating required fields such as title, summary, and content
- generating article slugs
- creating reading-time metadata
- validating thumbnail upload type and size
- saving the image into the uploads/blog_imgs folder
- inserting SEO metadata such as title, description, keywords, canonical URL, and social image
- setting the post status to draft or published

#### Typical validation process

```php
$title = trim($_POST['blog_title'] ?? '');
$content = sanitize_blog_html($_POST['blog_content'] ?? '');
$summary = sanitize_blog_html($_POST['summary_content'] ?? '');
```

The code also checks:

- title must not be empty
- content must not be empty
- summary must not be empty
- uploaded file must be a valid image type
- image size must not exceed the limit
- post length is capped to prevent abuse

This is a good example of server-side sanitation and validation logic in the repository.

---

### 5.5 Job Management Backend

The job system manages recruitment and opportunity tables.

Files involved:

- backend/addJob_processing.php
- backend/deleteJobPositions.php
- backend/get_job_code.php
- backend/get_job_position.php
- backend/DashBoard/addJobPosition.php
- backend/DashBoard/createjob.php
- backend/DashBoard/updatejob.php

#### Business logic

- Check if a job title already exists
- Auto-generate job code based on initials and random digits
- Save data to the jobcodes table
- Save or manage sectors in the sectors table
- Prevent duplicate data insertion
- Redirect to a success/error screen after the transaction

Example behavior:

```php
$checkTitle = $conn->prepare("SELECT jobid FROM jobcodes WHERE JobTitle = :job_title LIMIT 1");
$checkTitle->execute();
```

Then:

```php
$initials = '';
foreach ($words as $word) {
    if ($word !== '') $initials .= $word[0];
}
$jobCode = substr($initials, 0, 4) . rand(10, 99);
```

This shows a custom business rule for generating job identifiers rather than relying on auto-increment alone.

---

### 5.6 Notice and Content Management

The project includes notice-related content, and the backend supports it through database-backed modules.

Files include:

- backend/Database/notices.sql
- notice.php
- backend/DashBoard/viewallPage.php
- newnotice.php

The backend data model appears to support campaign notices, announcements, or informational updates that are stored in the database and rendered publicly.

---

### 5.7 Mail and Notification Features

The project includes Composer mail support through PHPMailer.

Relevant files:

- composer.json
- backend/sendingMail.php

This likely supports:

- contact form submissions
- automated admin notifications
- user communication and outbound messaging

The presence of PHPMailer indicates that some backend workflows are intended to send notifications or inquiry responses.

---

## 6. Security Model

The backend implements several important security patterns.

### 6.1 Session protection

Protected actions validate the existence of a PHP session before performing sensitive operations.

### 6.2 Prepared statements

The SQL logic uses PDO prepared statements to reduce the risk of SQL injection.

Example:

```php
$stmt = $conn->prepare("INSERT INTO blogs ... VALUES (:title, :slug, :content, ...)");
$stmt->execute();
```

### 6.3 CSRF protection

There is a dedicated helper file:

- backend/DashBoard/csrf_helper.php

This indicates the admin dashboard includes token validation to prevent cross-site request forgery on sensitive actions.

### 6.4 HTML sanitization

Blog content is sanitized before persistence.

```php
require_once __DIR__ . '/blogSanitizer.php';
$content = sanitize_blog_html($_POST['blog_content'] ?? '');
```

This is important because admin content is often rich text and must not allow dangerous HTML or script injection.

### 6.5 File upload restrictions

The blog upload process validates:

- MIME type
- extension mapping
- upload size
- directory path and file creation logic

This reduces the risk of malicious file uploads and invalid content types.

### 6.6 Cache prevention

The main config.php file uses no-cache headers to prevent stale browser caching on sensitive pages.

---

## 7. Database Design and Data Flow

The project stores content and admin data in MySQL. The SQL files under backend/Database/ strongly suggest a custom relational model.

### Likely main entities

- admin users
- blogs / posts
- job codes
- sectors
- notices
- article metadata (SEO fields, social image, canonical URL)
- branch/regional office records
- contact-related information

### Common database flow

1. User/admin submits data in a dashboard form
2. PHP script receives POST data
3. Input is validated and sanitized
4. A prepared statement inserts or updates rows
5. Redirect or JSON response is returned to the frontend

Example flow:

```php
$pdo = getDB();
$stmt = $pdo->prepare("INSERT INTO blogs (...) VALUES (...) ");
$stmt->execute();
```

This pattern is repeated across forms in the backend.

---

## 8. Request/Response Lifecycle

### Admin login lifecycle

```text
Browser -> login page -> validate credentials -> session created -> dashboard access granted -> protected actions executed
```

### Blog submission lifecycle

```text
Admin form -> blogUpload_process.php -> security validation -> upload image -> sanitize content -> insert into blogs -> success/error response
```

### Job creation lifecycle

```text
Admin dashboard -> addJob_processing.php -> duplicate check -> auto-generate job code -> insert into jobcodes/sectors -> redirect with status message
```

---

## 9. Deployment and Environment Setup

### Requirements

- PHP runtime
- MySQL server
- Apache or nginx web server
- Composer dependencies installed
- Writable uploads directory for media files

### Local setup steps

1. Clone the repository.
2. Install PHP dependencies:

```bash
composer install
```

3. Create or import the database schema using SQL files from backend/Database/.
4. Update the database credentials in backend/Database/db.php.
5. Start the app locally:

```bash
php -S localhost:8000
```

6. Open the site in a browser.

### Production hosting notes

This project appears to be designed for hosting environments like Hostinger. The config.php file is structured to use hosting-friendly URL and base path logic.

Important production reminder:

- do not keep hardcoded DB credentials in source control
- use environment variables or hosting configuration management
- restrict admin folder access
- ensure uploads and generated files have correct permissions

---

## 10. Security Hardening Recommendations

Although the project includes strong patterns, production hardening should still include the following:

- Move database credentials to environment variables
- Add role-based permissions for different admin actions
- Add server-side logging for all backend mutations
- Add audit trails for blog and job changes
- Use HTTPS everywhere
- Limit direct file execution and unauthorized directories
- Validate all request methods (POST/GET) consistently
- Restrict upload directory access from the web server
- Review all public-facing endpoints for XSS and injection issues
- Add automated tests for critical workflows

---

## 11. Project Strengths

The backend has several good design decisions:

- centralized database access
- prepared statements
- session-based admin protection
- content sanitization
- file upload validation
- modular file organization
- dashboard-driven management flow

These patterns make the project manageable for a custom CMS and administrative portal without a full framework.

---

## 12. Observed Risks and Gaps

The codebase is functional, but some production concerns are visible:

- hardcoded credentials in db.php
- some scripts depend on direct path assumptions
- admin authorization levels are not fully separated by role
- inconsistent patterns between different backend modules
- some scripts are not fully standardized in routing/response patterns

These are common in custom PHP projects and should be addressed before scaling to a production-grade environment.

---

## 13. Recommended Future Backend Improvements

- Introduce a proper environment configuration file
- Standardize all backend scripts under a shared request/API layer
- Add centralized logging and monitoring
- Introduce user roles and permissions
- Add validation helpers for all forms
- Create a unified response format for JSON and redirect actions
- Improve unit/integration test coverage
- Separate admin logic from presentation code
- Introduce a bootstrap or dependency injection layer for cleaner architecture

---

## 14. Summary

The ATMABISWAS backend is a custom PHP administration system built around database interaction, session-based security, media handling, content management, and dashboard workflows. It is structured to support a community website and internal admin operations without a full framework.

The project is strong in modularity and practical business functionality, but for long-term maintainability and production security, it should be refactored around stricter environment configuration, role-based access control, and standardized backend patterns.

---

## 15. Quick Reference

### Common entry points

- backend/login/prelogin.php – login entry point
- backend/login/loging.php – admin authentication page
- backend/login/logout.php – logout endpoint
- backend/DashBoard/dashboard.php – main admin dashboard
- backend/blogUpload_process.php – blog publishing processor
- backend/addJob_processing.php – job creation processor
- backend/Database/db.php – DB connection class

### Common setup commands

```bash
composer install
php -S localhost:8000
```

### Common database action

```sql
SELECT * FROM blogs;
SELECT * FROM admin_users;
SELECT * FROM jobcodes;
```

---

This README is intentionally focused on the backend architecture and management flow of the project. For a more frontend-specific guide, a separate documentation set can be created for the public website pages, design structure, and client-side JavaScript interactions.
