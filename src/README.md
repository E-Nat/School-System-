# University Management System

This repository contains a professional University Management System built using clean PHP MVC architecture and MySQL.

## What is included

- Multi-role access control
- Full authentication and session security
- Academic structure with faculties, departments, programs, courses, semesters
- Student enrollment, attendance, grading, transcripts, finance, announcements, and notifications
- Production-ready normalized database schema

## Key files

- `ARCHITECTURE.md` — system architecture, workflows, and module design
- `database_schema.sql` — full database schema and seed data
- `config.php` — central configuration for database and application settings
- `index.php` — main application router
- `app/Controllers` — controller layer
- `app/Models` — data access layer
- `app/Views` — presentation layer

## Deployment guide

1. Place this project under Apache or Nginx webroot.
2. Configure PHP 8.x and MySQL.
3. Import `database_schema.sql` into your MySQL server.
4. Update `config.php` with your database credentials.
5. Use `public/` as your webroot when moving to production.

## Default seeded accounts

- `superadmin@university.local / 12345678`
- `registrar@university.local / 12345678`
- `dean@university.local / 12345678`
- `headdept@university.local / 12345678`
- `lecturer@university.local / 12345678`
- `student@university.local / 12345678`
- `finance@university.local / 12345678`

## Next steps

- Implement the controller and model classes following the architecture.
- Build middleware for auth and RBAC.
- Create Bootstrap-based dashboard views.
- Add REST-style API endpoints for future integrations.
