<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$file = __DIR__ . '/database.sql';

if (!file_exists($file)) {
    die('database.sql file not found.');
}

$sql = file_get_contents($file);
if ($sql === false) {
    die('Unable to read database.sql.');
}

$mysqli = new mysqli($host, $user, $pass);
if ($mysqli->connect_error) {
    die('MySQL connection failed: ' . $mysqli->connect_error);
}

if (!$mysqli->multi_query($sql)) {
    die('SQL execution failed: ' . $mysqli->error);
}

// Flush all results to ensure the multi-query completes.
do {
    if ($result = $mysqli->store_result()) {
        $result->free();
    }
} while ($mysqli->more_results() && $mysqli->next_result());

$mysqli->close();

echo '<h1>Installation complete</h1>';
echo '<p>The <strong>school_system</strong> database and tables have been created.</p>';
echo '<p>Default accounts and sample test data are now available.</p>';
echo '<ul>';
echo '<li>Admin: admin@school.local / 12345678</li>';
echo '<li>Teacher: teacher@school.local / 12345678</li>';
echo '<li>Student: student@university.local / 12345678</li>';
echo '<li>Finance: finance@university.local / 12345678</li>';
echo '</ul>';
echo '<p>Use the admin dashboard to manage sample students, teachers, courses, and enrollments.</p>';
echo '<p>Delete or rename this file after installation for security.</p>';
