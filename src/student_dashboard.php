<?php
require_once 'Database/dbConnect.php';
require_once 'inc/auth.php';
requireLogin(['student']);
$pdo = connectDB();

$userId = $_SESSION['user']['id'];
$stmt = $pdo->prepare('SELECT * FROM students WHERE user_id = ?');
$stmt->execute([$userId]);
$student = $stmt->fetch();

$courses = [];
if ($student) {
    $stmt = $pdo->prepare('SELECT c.* FROM courses c JOIN student_courses sc ON sc.course_id = c.id WHERE sc.student_id = ?');
    $stmt->execute([$student['id']]);
    $courses = $stmt->fetchAll();
}

$pageTitle = 'Student Dashboard';
include 'inc/header.php';
?>
<div class="space-y-6">
    <section class="rounded-[2rem] bg-gradient-to-r from-slate-950 via-slate-900 to-cyan-600 px-8 py-10 text-white shadow-2xl ring-1 ring-slate-200/10">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-3xl font-semibold tracking-tight">Student Dashboard</h1>
                <p class="mt-2 text-sm text-cyan-100/90">Welcome back, <?php echo htmlspecialchars($_SESSION['user']['first_name']); ?>.</p>
            </div>
        </div>
    </section>
    <section class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-[1.75rem] bg-white p-6 shadow-lg ring-1 ring-slate-200">
            <p class="text-sm uppercase tracking-[0.28em] text-slate-500">Student Number</p>
            <p class="mt-4 text-4xl font-semibold text-slate-900"><?php echo htmlspecialchars($student['student_number'] ?? 'N/A'); ?></p>
        </div>
        <div class="rounded-[1.75rem] bg-white p-6 shadow-lg ring-1 ring-slate-200">
            <p class="text-sm uppercase tracking-[0.28em] text-slate-500">Program</p>
            <p class="mt-4 text-4xl font-semibold text-slate-900"><?php echo htmlspecialchars($student['grade_level'] ?? 'N/A'); ?></p>
        </div>
        <div class="rounded-[1.75rem] bg-white p-6 shadow-lg ring-1 ring-slate-200">
            <p class="text-sm uppercase tracking-[0.28em] text-slate-500">Courses Enrolled</p>
            <p class="mt-4 text-4xl font-semibold text-slate-900"><?php echo count($courses); ?></p>
        </div>
    </section>
    <section class="rounded-[2rem] bg-white p-8 shadow-lg ring-1 ring-slate-200">
        <h2 class="text-xl font-semibold text-slate-900">Registered Courses</h2>
        <?php if ($courses): ?>
            <div class="mt-6 space-y-3">
                <?php foreach ($courses as $course): ?>
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                        <p class="text-sm font-semibold text-slate-900"><?php echo htmlspecialchars($course['course_code'] . ' — ' . $course['course_name']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="mt-6 text-sm text-slate-500">No registered courses yet.</p>
        <?php endif; ?>
    </section>
</div>
<?php include 'inc/footer.php'; ?>
