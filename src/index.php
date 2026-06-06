<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        nav {
            background-color: #2c3e50;
            padding: 1rem 0;
            position: sticky;
            top: 0;
        }
        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 2rem;
        }
        nav a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }
        nav a:hover {
            color: #3498db;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 1rem;
            text-align: center;
        }
        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        .header p {
            font-size: 1.2rem;
        }
        .main-content {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }
        .feature-card {
            background: #f4f4f4;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        .feature-card h3 {
            color: #667eea;
            margin-bottom: 1rem;
        }
        .cta-button {
            display: inline-block;
            background-color: #667eea;
            color: white;
            padding: 0.8rem 2rem;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 1rem;
            transition: background-color 0.3s;
        }
        .cta-button:hover {
            background-color: #764ba2;
        }
        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
        }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="students.php">Students</a></li>
            <li><a href="teachers.php">Teachers</a></li>
            <li><a href="courses.php">Courses</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>
    
    <div class="header">
        <h1>School Management System</h1>
        <p>Streamline your educational institution</p>
    </div>
    
    <div class="main-content">
        <div class="features">
            <div class="feature-card">
                <h3>Student Management</h3>
                <p>Manage student records, enrollment, and academic progress.</p>
                <a href="students.php" class="cta-button">View Students</a>
            </div>
            <div class="feature-card">
                <h3>Teacher Management</h3>
                <p>Manage teacher profiles, schedules, and assignments.</p>
                <a href="teachers.php" class="cta-button">View Teachers</a>
            </div>
            <div class="feature-card">
                <h3>Course Management</h3>
                <p>Create and manage courses, curriculum, and class schedules.</p>
                <a href="courses.php" class="cta-button">View Courses</a>
            </div>
        </div>
    </div>
    
    <footer>
        <p>&copy; 2024 School Management System. All rights reserved.</p>
    </footer>
    
</body>
</html>