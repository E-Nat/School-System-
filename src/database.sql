-- University Management System Database Schema
-- Run this in your MySQL server after creating a database named `school_system`

CREATE DATABASE IF NOT EXISTS school_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE school_system;

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
    duration_years INT DEFAULT 4,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS academic_years (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL UNIQUE,
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

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('super_admin','registrar','dean','department_head','lecturer','student','finance_staff') NOT NULL DEFAULT 'student',
    phone VARCHAR(30) DEFAULT NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    faculty_id INT DEFAULT NULL,
    department_id INT DEFAULT NULL,
    employee_id VARCHAR(50) NOT NULL UNIQUE,
    position VARCHAR(100) DEFAULT NULL,
    hire_date DATE DEFAULT NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (faculty_id) REFERENCES faculties(id) ON DELETE SET NULL,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    program_id INT DEFAULT NULL,
    student_id VARCHAR(50) NOT NULL UNIQUE,
    student_number VARCHAR(50) NOT NULL UNIQUE,
    admission_year YEAR DEFAULT NULL,
    date_of_birth DATE DEFAULT NULL,
    gender ENUM('male','female','other') DEFAULT 'male',
    grade_level VARCHAR(50) DEFAULT NULL,
    current_year VARCHAR(50) DEFAULT NULL,
    current_semester VARCHAR(50) DEFAULT NULL,
    status ENUM('active','graduated','inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    program_id INT DEFAULT NULL,
    course_code VARCHAR(50) NOT NULL UNIQUE,
    course_name VARCHAR(150) NOT NULL,
    description TEXT,
    credit_hours INT DEFAULT 3,
    course_level VARCHAR(50) DEFAULT NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE SET NULL
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
    FOREIGN KEY (lecturer_id) REFERENCES teachers(id) ON DELETE SET NULL
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

CREATE TABLE IF NOT EXISTS enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_offering_id INT NOT NULL,
    section_id INT DEFAULT NULL,
    status ENUM('pending','approved','rejected','completed','withdrawn') NOT NULL DEFAULT 'pending',
    advisor_approval ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    finance_verification ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    enrolled_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_offering_id) REFERENCES course_offerings(id) ON DELETE CASCADE,
    FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE SET NULL,
    UNIQUE KEY uniq_student_course (student_id, course_offering_id)
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
    FOREIGN KEY (recorded_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_offering_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT DEFAULT NULL,
    due_date DATE DEFAULT NULL,
    weight INT DEFAULT 0,
    status ENUM('open','closed') NOT NULL DEFAULT 'open',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_offering_id) REFERENCES course_offerings(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS exams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_offering_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    exam_type ENUM('midterm','final','quiz','assignment') NOT NULL DEFAULT 'midterm',
    exam_date DATE DEFAULT NULL,
    weight INT DEFAULT 0,
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
    grade_value VARCHAR(10) DEFAULT NULL,
    score DECIMAL(6,2) DEFAULT NULL,
    grade_type ENUM('assignment','exam','final','midterm') NOT NULL DEFAULT 'final',
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
    academic_year_id INT DEFAULT NULL,
    gpa DECIMAL(4,2) DEFAULT NULL,
    status ENUM('draft','finalized') NOT NULL DEFAULT 'draft',
    generated_by INT DEFAULT NULL,
    generated_at DATETIME DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE SET NULL,
    FOREIGN KEY (generated_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tuition_fees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    due_date DATE DEFAULT NULL,
    status ENUM('pending','paid','overdue') NOT NULL DEFAULT 'pending',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    tuition_fee_id INT DEFAULT NULL,
    amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    paid_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    method VARCHAR(100) DEFAULT NULL,
    invoice_number VARCHAR(100) DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (tuition_fee_id) REFERENCES tuition_fees(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS scholarships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    awarded_date DATE DEFAULT NULL,
    status ENUM('active','expired','revoked') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    category VARCHAR(100) DEFAULT NULL,
    start_date DATE DEFAULT NULL,
    end_date DATE DEFAULT NULL,
    created_by INT DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL,
    uploaded_by INT DEFAULT NULL,
    uploaded_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    action VARCHAR(150) NOT NULL,
    details TEXT DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) DEFAULT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS student_courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    enroll_date DATE NOT NULL DEFAULT CURRENT_DATE,
    status ENUM('enrolled','completed','withdrawn') NOT NULL DEFAULT 'enrolled',
    grade VARCHAR(10) DEFAULT NULL,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY student_course_unique (student_id, course_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS attendance_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    teacher_id INT DEFAULT NULL,
    attendance_date DATE NOT NULL DEFAULT CURRENT_DATE,
    status ENUM('present','absent','late','excused') NOT NULL DEFAULT 'present',
    note TEXT DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS student_notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    teacher_id INT DEFAULT NULL,
    note_type ENUM('behavior','academic','attendance','remark') NOT NULL DEFAULT 'remark',
    note_text TEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO users (first_name, last_name, email, password, role, phone, status) VALUES
('Super', 'Admin', 'superadmin@university.local', '$2y$10$kWEuPAEM.zhumMGJHCQRzut7AbadBh1znLmLJRsyeX2q1h7w8mYn6', 'super_admin', '', 'active'),
('Registrar', 'Office', 'registrar@university.local', '$2y$10$kWEuPAEM.zhumMGJHCQRzut7AbadBh1znLmLJRsyeX2q1h7w8mYn6', 'registrar', '', 'active'),
('Dean', 'User', 'dean@university.local', '$2y$10$kWEuPAEM.zhumMGJHCQRzut7AbadBh1znLmLJRsyeX2q1h7w8mYn6', 'dean', '', 'active'),
('Head', 'Department', 'headdept@university.local', '$2y$10$kWEuPAEM.zhumMGJHCQRzut7AbadBh1znLmLJRsyeX2q1h7w8mYn6', 'department_head', '', 'active'),
('Lecturer', 'User', 'lecturer@university.local', '$2y$10$kWEuPAEM.zhumMGJHCQRzut7AbadBh1znLmLJRsyeX2q1h7w8mYn6', 'lecturer', '', 'active'),
('Student', 'User', 'student@university.local', '$2y$10$kWEuPAEM.zhumMGJHCQRzut7AbadBh1znLmLJRsyeX2q1h7w8mYn6', 'student', '', 'active'),
('Finance', 'Staff', 'finance@university.local', '$2y$10$kWEuPAEM.zhumMGJHCQRzut7AbadBh1znLmLJRsyeX2q1h7w8mYn6', 'finance_staff', '', 'active');

INSERT IGNORE INTO teachers (user_id, employee_id, faculty_id, department_id, position, status)
SELECT id, 'EMP001', NULL, NULL, 'Lecturer', 'active' FROM users WHERE role = 'lecturer' LIMIT 1;

INSERT IGNORE INTO students (user_id, program_id, student_id, student_number, admission_year, date_of_birth, current_year, current_semester, status)
SELECT id, NULL, 'STD1001', 'STD1001', YEAR(CURDATE()), NULL, 'Year 1', 'Semester 1', 'active' FROM users WHERE role = 'student' LIMIT 1;

-- Default passwords for seeded accounts:
-- superadmin@university.local / 12345678
-- registrar@university.local / 12345678
-- dean@university.local / 12345678
-- headdept@university.local / 12345678
-- lecturer@university.local / 12345678
-- student@university.local / 12345678
-- finance@university.local / 12345678

INSERT IGNORE INTO faculties (code, name, description) VALUES
('ENG', 'Engineering Faculty', 'Engineering and technology programs'),
('SCI', 'Science Faculty', 'Science, mathematics, and research programs');

INSERT IGNORE INTO departments (faculty_id, code, name, description)
SELECT f.id, 'CSE', 'Computer Science Engineering', 'Computer Science and Engineering department' FROM faculties f WHERE f.code = 'ENG'
UNION ALL
SELECT f.id, 'MATH', 'Mathematics', 'Mathematics and statistics department' FROM faculties f WHERE f.code = 'SCI';

INSERT IGNORE INTO programs (department_id, code, name, level, duration_years, status)
SELECT d.id, 'BSCS', 'BSc Computer Science', 'undergraduate', 4, 'active' FROM departments d WHERE d.code = 'CSE'
UNION ALL
SELECT d.id, 'BSCM', 'BSc Mathematics', 'undergraduate', 4, 'active' FROM departments d WHERE d.code = 'MATH';

INSERT IGNORE INTO classrooms (building, room_number, capacity, resources, status) VALUES
('Main Building', 'A101', 40, 'Projector, Whiteboard', 'active'),
('Science Block', 'B202', 30, 'Lab equipment, network access', 'active');

INSERT IGNORE INTO users (first_name, last_name, email, password, role, phone, status) VALUES
('Alice', 'Brown', 'alice@university.local', '$2y$10$kWEuPAEM.zhumMGJHCQRzut7AbadBh1znLmLJRsyeX2q1h7w8mYn6', 'lecturer', '+880100000001', 'active'),
('Bob', 'Green', 'bob@university.local', '$2y$10$kWEuPAEM.zhumMGJHCQRzut7AbadBh1znLmLJRsyeX2q1h7w8mYn6', 'lecturer', '+880100000002', 'active'),
('Jane', 'Student', 'jane.student@university.local', '$2y$10$kWEuPAEM.zhumMGJHCQRzut7AbadBh1znLmLJRsyeX2q1h7w8mYn6', 'student', '+880100000003', 'active'),
('Mark', 'Student', 'mark.student@university.local', '$2y$10$kWEuPAEM.zhumMGJHCQRzut7AbadBh1znLmLJRsyeX2q1h7w8mYn6', 'student', '+880100000004', 'active');

INSERT IGNORE INTO teachers (user_id, employee_id, faculty_id, department_id, position, status)
SELECT u.id, 'EMP002', f.id, d.id, 'Senior Lecturer', 'active'
FROM users u
JOIN faculties f ON f.code = 'ENG'
JOIN departments d ON d.code = 'CSE'
WHERE u.email = 'alice@university.local'
UNION ALL
SELECT u.id, 'EMP003', f.id, d.id, 'Lecturer', 'active'
FROM users u
JOIN faculties f ON f.code = 'ENG'
JOIN departments d ON d.code = 'CSE'
WHERE u.email = 'bob@university.local';

INSERT IGNORE INTO students (user_id, program_id, student_id, student_number, admission_year, date_of_birth, gender, grade_level, current_year, current_semester, status, email, phone, guardian_name, guardian_phone, enrollment_date)
SELECT u.id, p.id, 'STD1002', 'STD1002', YEAR(CURDATE()), '2005-03-12', 'female', 'Year 1', 'Year 1', 'Semester 1', 'active', u.email, '+880100000003', 'Mary Student', '+880100100003', CURDATE()
FROM users u
JOIN programs p ON p.code = 'BSCS'
WHERE u.email = 'jane.student@university.local'
UNION ALL
SELECT u.id, p.id, 'STD1003', 'STD1003', YEAR(CURDATE()), '2005-07-22', 'male', 'Year 1', 'Year 1', 'Semester 1', 'active', u.email, '+880100000004', 'Peter Student', '+880100100004', CURDATE()
FROM users u
JOIN programs p ON p.code = 'BSCS'
WHERE u.email = 'mark.student@university.local';

INSERT IGNORE INTO courses (program_id, course_code, course_name, description, credit_hours, course_level, status)
SELECT p.id, 'CS101', 'Intro to Programming', 'Learn programming fundamentals with PHP and Python.', 3, 'undergraduate', 'active' FROM programs p WHERE p.code = 'BSCS'
UNION ALL
SELECT p.id, 'CS102', 'Data Structures', 'Data structures, algorithms, and problem solving.', 3, 'undergraduate', 'active' FROM programs p WHERE p.code = 'BSCS'
UNION ALL
SELECT p.id, 'MATH101', 'Calculus I', 'Differential calculus and foundational mathematics.', 3, 'undergraduate', 'active' FROM programs p WHERE p.code = 'BSCM'
UNION ALL
SELECT p.id, 'MATH102', 'Linear Algebra', 'Matrices, vectors, and systems of equations.', 3, 'undergraduate', 'active' FROM programs p WHERE p.code = 'BSCM';

INSERT IGNORE INTO student_courses (student_id, course_id, enroll_date, status, grade)
SELECT s.id, c.id, CURDATE(), 'enrolled', NULL
FROM students s
JOIN courses c ON c.course_code IN ('CS101', 'CS102')
WHERE s.student_id = 'STD1002'
UNION ALL
SELECT s.id, c.id, CURDATE(), 'enrolled', NULL
FROM students s
JOIN courses c ON c.course_code = 'CS101'
WHERE s.student_id = 'STD1003';

INSERT IGNORE INTO announcements (title, content, category, start_date, end_date, created_by)
SELECT 'Academic calendar updated', 'The Fall semester timetable is now available. Check your dashboard for the latest schedule.', 'Academic', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), u.id
FROM users u
WHERE u.email = 'superadmin@university.local';

INSERT IGNORE INTO documents (title, file_path, description, uploaded_by)
SELECT 'Student Handbook', 'uploads/student-handbook.pdf', 'Campus policies and student resources.', u.id
FROM users u
WHERE u.email = 'superadmin@university.local';
