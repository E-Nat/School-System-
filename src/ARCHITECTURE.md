# University Management System Architecture

## 1. System overview

This is a professional, production-grade University Management System built as a clean PHP MVC application with a MySQL backend.

The system supports:
- Multi-role access control for Super Admin, Registrar, Dean, Department Head, Lecturer, Student, Finance Staff.
- Full authentication, RBAC, session security, CSRF protection, and password hashing.
- Academic structure from faculties through programs, courses, sections, academic years, and semesters.
- Core campus workflows: student admissions, course enrollment, attendance, grading, transcripts, schedules, announcements, finance, and audit logs.

## 2. Architecture

### Folder structure

    /src
      /app
        /Controllers
        /Models
        /Views
      /config
      /routes
      /public
      /services
      /middleware
      /database.sql
      /ARCHITECTURE.md
      /README.md

### Layer responsibilities

- `Controllers`: receives HTTP requests, applies validation, decides which model methods to invoke, and renders views.
- `Models`: encapsulates database access for each business entity using PDO and prepared statements.
- `Views`: responsive Bootstrap-based HTML templates with minimal PHP logic.
- `Config`: centralized environment and database settings.
- `Routes`: HTTP route definitions and request dispatching.
- `Services`: shared domain services like PDF generation, grade calculation, and schedule conflict checking.
- `Middleware`: security and RBAC enforcement for authenticated requests.

## 3. Authentication and RBAC

- `users` table stores core identity data.
- `roles` table defines role metadata.
- `user_roles` join table supports future multi-role expansion.
- `AuthController` handles login, logout, and registration.
- Role middleware prevents unauthorized access by mapping pages to allowed roles.

## 4. Database design philosophy

- Normalized to 3NF.
- Proper primary keys, foreign keys, and indexes.
- `ON DELETE CASCADE` for dependent child records.
- `ON DELETE SET NULL` for optional relationships.
- Timestamp fields on all tables.

## 5. Key workflows

### A. Student registration flow

1. Super Admin or Registrar creates a student record.
2. Student account is created and credential email is sent.
3. Student logs in and selects an academic year/semester.
4. Student requests course enrollment.
5. System checks prerequisites, schedule conflicts, and advisor approvals.
6. Enrollment is confirmed once finance and academic approvals are complete.

### B. Attendance flow

1. Lecturer chooses an active section.
2. Lecturer marks attendance for a specific date.
3. Attendance records are stored per enrollment.
4. Students can view history via their portal.

### C. Grading flow

1. Lecturer enters assignment, quiz, midterm, and final scores.
2. System calculates weighted grades automatically.
3. Department Head approves grades.
4. Grades are published to the student portal and GPA updates.

### D. Transcript flow

1. Student submits transcript request.
2. Registrar verifies the request and approves.
3. System generates an official transcript PDF.

## 6. Core modules

- `Student Management`
- `Lecturer Management`
- `Course Management`
- `Enrollment Management`
- `Attendance Management`
- `Grade and GPA Management`
- `Transcript Generation`
- `Schedule and Timetable Management`
- `Finance and Tuition Management`
- `Announcement and Notification Management`
- `Audit Logging`

## 7. Deployment summary

- Use Apache or Nginx with PHP 8.x.
- Use `public/` as the webroot.
- Keep configuration in `config/config.php`.
- Use Composer for dependencies if additional libraries are introduced.
- Enable HTTPS and secure session settings.

## 8. Next steps

1. Create the core MVC skeleton in `/src/app`.
2. Implement database models and migration logic.
3. Build authentication, middleware, and RBAC.
4. Implement dashboard views and CRUD forms.
5. Add API endpoints for future mobile/SPA consumption.
