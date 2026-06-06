<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageTitle = $pageTitle ?? 'School Management System';
$currentUser = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
<header class="sticky top-0 z-50 bg-slate-950 text-white shadow-lg shadow-slate-900/10">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
        <a href="index.php" class="flex items-center gap-3 text-lg font-semibold tracking-tight text-white sm:text-xl">
            <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-cyan-400 to-indigo-500 text-slate-950">UMS</span>
            <span>University Management System</span>
        </a>
        <nav class="flex flex-wrap items-center gap-2 text-sm font-medium text-slate-200">
            <a href="index.php" class="rounded-full px-4 py-2 transition hover:bg-slate-800">Home</a>
            <a href="students.php" class="rounded-full px-4 py-2 transition hover:bg-slate-800">Students</a>
            <a href="teachers.php" class="rounded-full px-4 py-2 transition hover:bg-slate-800">Teachers</a>
            <a href="courses.php" class="rounded-full px-4 py-2 transition hover:bg-slate-800">Courses</a>
            <a href="about.php" class="rounded-full px-4 py-2 transition hover:bg-slate-800">About</a>
            <a href="contact.php" class="rounded-full px-4 py-2 transition hover:bg-slate-800">Contact</a>
            <?php if ($currentUser): ?>
                <?php if (in_array($currentUser['role'], ['super_admin', 'admin', 'registrar', 'dean', 'department_head', 'finance_staff'], true)): ?>
                    <a href="admin_dashboard.php" class="rounded-full bg-cyan-500 px-4 py-2 text-white transition hover:bg-cyan-400">Dashboard</a>
                <?php elseif ($currentUser['role'] === 'lecturer'): ?>
                    <a href="teacher_dashboard.php" class="rounded-full bg-cyan-500 px-4 py-2 text-white transition hover:bg-cyan-400">Dashboard</a>
                <?php elseif ($currentUser['role'] === 'student'): ?>
                    <a href="student_dashboard.php" class="rounded-full bg-cyan-500 px-4 py-2 text-white transition hover:bg-cyan-400">Dashboard</a>
                <?php endif; ?>
                <a href="logout.php" class="rounded-full border border-white/20 px-4 py-2 text-white transition hover:bg-white/10">Logout</a>
            <?php else: ?>
                <a href="sign_in.php" class="rounded-full bg-white px-4 py-2 text-slate-950 transition hover:bg-slate-100">Sign In</a>
                <a href="sign_up.php" class="rounded-full border border-white/20 px-4 py-2 text-white transition hover:bg-white/10">Sign Up</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
