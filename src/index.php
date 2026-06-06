<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --accent-color: #60a5fa;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: 0.5px;
        }
        .navbar-nav .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
            margin-right: 0.5rem;
        }
        .navbar-nav .nav-link:hover {
            color: var(--accent-color) !important;
            transform: translateY(-2px);
        }
        .navbar-nav .nav-link.active {
            color: var(--accent-color) !important;
            border-bottom: 2px solid var(--accent-color);
        }
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 5rem 0;
            margin-bottom: 3rem;
        }
        .hero-section h1 {
            font-weight: 700;
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .hero-section p {
            font-size: 1.3rem;
            opacity: 0.95;
        }
        .feature-card {
            border: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.15);
        }
        .feature-card .card-body {
            padding: 2rem;
        }
        .feature-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        .feature-card h5 {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
        }
        .feature-card p {
            color: #64748b;
            line-height: 1.8;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            font-weight: 600;
            padding: 0.6rem 1.8rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(37, 99, 235, 0.3);
        }
        .btn-primary:active {
            transform: translateY(0);
        }
        footer {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            color: white;
            padding: 2.5rem 0;
            margin-top: 4rem;
        }
        footer p {
            margin: 0;
            font-weight: 500;
        }
        .dropdown-toggle::after {
            display: none;
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
        }
        .dropdown-menu .dropdown-item {
            color: white;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .dropdown-menu .dropdown-item:hover {
            background: rgba(255,255,255,0.15);
            color: var(--accent-color);
        }
        @media (max-width: 992px) {
            .navbar-nav {
                text-align: center;
            }
            .navbar-nav .nav-link {
                padding-top: 0.75rem;
                padding-bottom: 0.75rem;
            }
        }
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2rem;
            }
            .hero-section p {
                font-size: 1rem;
            }
            .navbar-nav {
                text-align: center;
            }
            .feature-card {
                margin-bottom: 1rem;
            }
            .feature-icon {
                font-size: 2.5rem;
            }
        }
        @media (max-width: 576px) {
            .hero-section {
                padding: 3rem 0;
                margin-bottom: 2rem;
            }
            .hero-section h1 {
                font-size: 1.5rem;
            }
            .feature-card .card-body {
                padding: 1.5rem;
            }
            .btn-primary {
                padding: 0.5rem 1.5rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-school me-2"></i>School System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="students.php">Students</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="teachers.php">Teachers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="courses.php">Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="hero-section">
        <div class="container text-center">
            <h1 class="display-4">School Management System</h1>
            <p class="lead">Streamline your educational institution with our comprehensive management solution</p>
        </div>
    </div>
    
    <div class="container mb-5">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card feature-card">
                    <div class="card-body text-center">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h5 class="card-title">Student Management</h5>
                        <p class="card-text">Manage student records, enrollment, and academic progress with ease and efficiency.</p>
                        <a href="students.php" class="btn btn-primary">View Students</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card feature-card">
                    <div class="card-body text-center">
                        <div class="feature-icon">
                            <i class="fas fa-chalkboard-user"></i>
                        </div>
                        <h5 class="card-title">Teacher Management</h5>
                        <p class="card-text">Manage teacher profiles, schedules, and assignments effortlessly.</p>
                        <a href="teachers.php" class="btn btn-primary">View Teachers</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card feature-card">
                    <div class="card-body text-center">
                        <div class="feature-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h5 class="card-title">Course Management</h5>
                        <p class="card-text">Create and manage courses, curriculum, and class schedules efficiently.</p>
                        <a href="courses.php" class="btn btn-primary">View Courses</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <footer>
        <div class="container text-center">
            <p>&copy; 2024 School Management System. All rights reserved.</p>
        </div>
    </footer>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>