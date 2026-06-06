-- University Management System Database Schema
-- Production-ready normalized schema for a large university ERP

CREATE DATABASE IF NOT EXISTS school_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE school_system;

CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(30) DEFAULT NULL,
    email_verified TINYINT(1) NOT NULL DEFAULT 0,
    last_login DATETIME DEFAULT NULL,
    status ENUM('active','inactive','suspended') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS faculties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    faculty_id INT NOT NULL,
    code VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (faculty_id) REFERENCES faculties(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS programs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    department_id INT NOT NULL,
    code VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(150) NOT NULL,
    level ENUM('undergraduate','graduate','postgraduate') NOT NULL DEFAULT 'undergraduate',
    duration_years INT NOT NULL DEFAULT 4,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS academic_years (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(120) NOT NULL UNIQUE,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS semesters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    academic_year_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS classrooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    building VARCHAR(100) DEFAULT NULL,
    room_number VARCHAR(50) DEFAULT NULL,
    capacity INT DEFAULT 0,
    resources VARCHAR(255) DEFAULT NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    program_id INT NOT NULL,
    department_id INT DEFAULT NULL,
    faculty_id INT DEFAULT NULL,
    course_code VARCHAR(50) NOT NULL UNIQUE,
    course_name VARCHAR(200) NOT NULL,
    description TEXT,
    credit_hours INT NOT NULL DEFAULT 3,
    course_level VARCHAR(50) DEFAULT NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE CASCADE,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL,
    FOREIGN KEY (faculty_id) REFERENCES faculties(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS course_prerequisites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    prerequisite_course_id INT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (prerequisite_course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY uniq_course_prerequisite (course_id, prerequisite_course_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS course_offerings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    academic_year_id INT NOT NULL,
    semester_id INT NOT NULL,
    lecturer_id INT DEFAULT NULL,
    section_label VARCHAR(50) DEFAULT NULL,
    capacity INT DEFAULT 0,
    status ENUM('open','closed','cancelled') NOT NULL DEFAULT 'open',
    start_date DATE DEFAULT NULL,
    end_date DATE DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
    FOREIGN KEY (semester_id) REFERENCES semesters(id) ON DELETE CASCADE,
    FOREIGN KEY (lecturer_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS sections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_offering_id INT NOT NULL,
    section_code VARCHAR(50) NOT NULL,
    classroom_id INT DEFAULT NULL,
    max_capacity INT DEFAULT 0,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_offering_id) REFERENCES course_offerings(id) ON DELETE CASCADE,
    FOREIGN KEY (classroom_id) REFERENCES classrooms(id) ON DELETE SET NULL,
    UNIQUE KEY uniq_section_code (course_offering_id, section_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_id INT NOT NULL,
    classroom_id INT DEFAULT NULL,
    day_of_week ENUM('monday','tuesday','wednesday','thursday','friday','saturday','sunday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE,
    FOREIGN KEY (classroom_id) REFERENCES classrooms(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS scholarships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    title VARCHAR(150) NOT NULL,
    description TEXT DEFAULT NULL,
    amount DECIMAL(12,2) DEFAULT NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tuition_fees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    program_id INT DEFAULT NULL,
    semester_id INT DEFAULT NULL,
    amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    description VARCHAR(255) DEFAULT NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE SET NULL,
    FOREIGN KEY (semester_id) REFERENCES semesters(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    tuition_fee_id INT DEFAULT NULL,
    invoice_number VARCHAR(80) NOT NULL UNIQUE,
    amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    paid_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    due_date DATE DEFAULT NULL,
    status ENUM('pending','paid','overdue') NOT NULL DEFAULT 'pending',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tuition_fee_id) REFERENCES tuition_fees(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT NOT NULL,
    student_id INT NOT NULL,
    amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    payment_method ENUM('cash','card','transfer','online') NOT NULL DEFAULT 'cash',
    payment_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    reference_number VARCHAR(120) DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    program_id INT DEFAULT NULL,
    student_number VARCHAR(80) NOT NULL UNIQUE,
    admission_year YEAR DEFAULT NULL,
    date_of_birth DATE DEFAULT NULL,
    gender ENUM('male','female','other') DEFAULT 'other',
    grade_level VARCHAR(60) DEFAULT NULL,
    current_year VARCHAR(50) DEFAULT NULL,
    current_semester VARCHAR(50) DEFAULT NULL,
    scholarship_id INT DEFAULT NULL,
    status ENUM('active','graduated','inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE SET NULL,
    FOREIGN KEY (scholarship_id) REFERENCES scholarships(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS lecturers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    faculty_id INT DEFAULT NULL,
    department_id INT DEFAULT NULL,
    employee_id VARCHAR(80) NOT NULL UNIQUE,
    position VARCHAR(150) DEFAULT NULL,
    hire_date DATE DEFAULT NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (faculty_id) REFERENCES faculties(id) ON DELETE SET NULL,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    body TEXT NOT NULL,
    visible_to ENUM('all','students','lecturers','staff','admins') NOT NULL DEFAULT 'all',
    start_date DATE DEFAULT NULL,
    end_date DATE DEFAULT NULL,
    status ENUM('published','draft','archived') NOT NULL DEFAULT 'published',
    created_by INT DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info','warning','success','error') NOT NULL DEFAULT 'info',
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    link VARCHAR(255) DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    section_id INT NOT NULL,
    academic_year_id INT NOT NULL,
    semester_id INT NOT NULL,
    status ENUM('pending','approved','rejected','completed','withdrawn') NOT NULL DEFAULT 'pending',
    advisor_approval ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    finance_verification ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    enrolled_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE,
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
    FOREIGN KEY (semester_id) REFERENCES semesters(id) ON DELETE CASCADE,
    UNIQUE KEY uniq_student_section (student_id, section_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    enrollment_id INT NOT NULL,
    attendance_date DATE NOT NULL,
    status ENUM('present','absent','late','excused') NOT NULL DEFAULT 'present',
    remarks TEXT DEFAULT NULL,
    recorded_by INT DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE,
    FOREIGN KEY (recorded_by) REFERENCES users(id) ON DELETE SET NULL,
    UNIQUE KEY uniq_enrollment_date (enrollment_id, attendance_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_offering_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT DEFAULT NULL,
    due_date DATE DEFAULT NULL,
    weight DECIMAL(5,2) DEFAULT 0,
    status ENUM('open','closed') NOT NULL DEFAULT 'open',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_offering_id) REFERENCES course_offerings(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS exams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_offering_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    exam_type ENUM('midterm','final','quiz','assignment') NOT NULL DEFAULT 'midterm',
    exam_date DATE DEFAULT NULL,
    weight DECIMAL(5,2) DEFAULT 0,
    status ENUM('scheduled','completed','cancelled') NOT NULL DEFAULT 'scheduled',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_offering_id) REFERENCES course_offerings(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS grades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    enrollment_id INT NOT NULL,
    assignment_id INT DEFAULT NULL,
    exam_id INT DEFAULT NULL,
    grade_type ENUM('assignment','exam','midterm','final','final_grade') NOT NULL DEFAULT 'final',
    score DECIMAL(6,2) DEFAULT NULL,
    grade_value VARCHAR(10) DEFAULT NULL,
    approved_by INT DEFAULT NULL,
    approved_at DATETIME DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE,
    FOREIGN KEY (assignment_id) REFERENCES assignments(id) ON DELETE SET NULL,
    FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE SET NULL,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS transcripts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    requested_by INT NOT NULL,
    verified_by INT DEFAULT NULL,
    status ENUM('requested','verified','issued','cancelled') NOT NULL DEFAULT 'requested',
    pdf_path VARCHAR(255) DEFAULT NULL,
    request_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    verified_at DATETIME DEFAULT NULL,
    issued_at DATETIME DEFAULT NULL,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (requested_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    action VARCHAR(120) NOT NULL,
    details TEXT DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed data for roles and default users
INSERT IGNORE INTO roles (code, name, description) VALUES
('super_admin', 'Super Admin', 'Full system administrator with access to all modules'),
('registrar', 'Registrar Office', 'Manages student enrollment, transcripts, and academic approvals'),
('dean', 'Dean', 'Approves faculty and academic program decisions'),
('department_head', 'Department Head', 'Oversees department operations and approvals'),
('lecturer', 'Lecturer', 'Teaches courses, marks attendance, and enters grades'),
('student', 'Student', 'Enrolls in courses and views schedules, grades, and transcripts'),
('finance_staff', 'Finance Staff', 'Handles tuition, payments, invoices, and financial verification');

INSERT IGNORE INTO users (role_id, first_name, last_name, email, password, phone, status) VALUES
((SELECT id FROM roles WHERE code = 'super_admin'), 'Super', 'Admin', 'superadmin@university.local', '$2y$10$kWEuPAEM.zhumMGJHCQRzut7AbadBh1znLmLJRsyeX2q1h7w8mYn6', '', 'active'),
((SELECT id FROM roles WHERE code = 'registrar'), 'Registrar', 'Office', 'registrar@university.local', '$2y$10$kWEuPAEM.zhumMGJHCQRzut7AbadBh1znLmLJRsyeX2q1h7w8mYn6', '', 'active'),
((SELECT id FROM roles WHERE code = 'dean'), 'Dean', 'User', 'dean@university.local', '$2y$10$kWEuPAEM.zhumMGJHCQRzut7AbadBh1znLmLJRsyeX2q1h7w8mYn6', '', 'active'),
((SELECT id FROM roles WHERE code = 'department_head'), 'Head', 'Department', 'headdept@university.local', '$2y$10$kWEuPAEM.zhumMGJHCQRzut7AbadBh1znLmLJRsyeX2q1h7w8mYn6', '', 'active'),
((SELECT id FROM roles WHERE code = 'lecturer'), 'Lecturer', 'User', 'lecturer@university.local', '$2y$10$kWEuPAEM.zhumMGJHCQRzut7AbadBh1znLmLJRsyeX2q1h7w8mYn6', '', 'active'),
((SELECT id FROM roles WHERE code = 'student'), 'Student', 'User', 'student@university.local', '$2y$10$kWEuPAEM.zhumMGJHCQRzut7AbadBh1znLmLJRsyeX2q1h7w8mYn6', '', 'active'),
((SELECT id FROM roles WHERE code = 'finance_staff'), 'Finance', 'Staff', 'finance@university.local', '$2y$10$kWEuPAEM.zhumMGJHCQRzut7AbadBh1znLmLJRsyeX2q1h7w8mYn6', '', 'active');

INSERT IGNORE INTO faculties (code, name, description) VALUES
('SCI', 'Faculty of Science', 'Science faculty including mathematics, physics, and chemistry'),
('ENG', 'Faculty of Engineering', 'Engineering faculty including computer science, electrical and civil engineering');

INSERT IGNORE INTO departments (faculty_id, code, name) VALUES
((SELECT id FROM faculties WHERE code = 'SCI'), 'MATH', 'Department of Mathematics'),
((SELECT id FROM faculties WHERE code = 'ENG'), 'CSE', 'Department of Computer Science and Engineering');

INSERT IGNORE INTO programs (department_id, code, name) VALUES
((SELECT id FROM departments WHERE code = 'CSE'), 'BSC-CS', 'Bachelor of Science in Computer Science'),
((SELECT id FROM departments WHERE code = 'MATH'), 'BSC-MATH', 'Bachelor of Science in Mathematics');

INSERT IGNORE INTO academic_years (title, start_date, end_date) VALUES
('2025/2026', '2025-09-01', '2026-08-31');

INSERT IGNORE INTO semesters (academic_year_id, name, start_date, end_date) VALUES
((SELECT id FROM academic_years WHERE title = '2025/2026'), 'Fall 2025', '2025-09-01', '2025-12-20'),
((SELECT id FROM academic_years WHERE title = '2025/2026'), 'Spring 2026', '2026-01-10', '2026-05-15');
