<?php
require_once 'Database/dbConnect.php';
require_once 'inc/auth.php';
requireLogin(['lecturer']);
$pdo = connectDB();

$userId = $_SESSION['user']['id'];
$teacher = $pdo->prepare('SELECT * FROM teachers WHERE user_id = ?');
$teacher->execute([$userId]);
$teacher = $teacher->fetch();

$courses = $pdo->prepare('SELECT c.* FROM courses c WHERE c.teacher_id = ?');
$courses->execute([$teacher['id']]);
$courseList = $courses->fetchAll();

$pageTitle = 'Teacher Dashboard';
include 'inc/header.php';
?>
<div class="space-y-6">
    <section class="rounded-[2rem] bg-gradient-to-r from-slate-950 via-slate-900 to-cyan-600 px-8 py-10 text-white shadow-2xl ring-1 ring-slate-200/10">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-3xl font-semibold tracking-tight">Teacher Dashboard</h1>
                <p class="mt-2 text-sm text-cyan-100/90">Welcome back, <?php echo htmlspecialchars($_SESSION['user']['first_name']); ?>.</p>
            </div>
        </div>
    </section>
    <section class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-[1.75rem] bg-white p-6 shadow-lg ring-1 ring-slate-200">
            <p class="text-sm uppercase tracking-[0.28em] text-slate-500">Your Courses</p>
            <p class="mt-4 text-4xl font-semibold text-slate-900"><?php echo count($courseList); ?></p>
            <p class="mt-2 text-sm text-slate-500">Courses currently assigned to you.</p>
        </div>
        <div class="rounded-[1.75rem] bg-white p-6 shadow-lg ring-1 ring-slate-200">
            <p class="text-sm uppercase tracking-[0.28em] text-slate-500">Department</p>
            <p class="mt-4 text-4xl font-semibold text-slate-900"><?php echo htmlspecialchars($teacher['department'] ?: 'N/A'); ?></p>
            <p class="mt-2 text-sm text-slate-500">Your home department.</p>
        </div>
        <div class="rounded-[1.75rem] bg-white p-6 shadow-lg ring-1 ring-slate-200">
            <p class="text-sm uppercase tracking-[0.28em] text-slate-500">Teacher ID</p>
            <p class="mt-4 text-4xl font-semibold text-slate-900"><?php echo htmlspecialchars($teacher['employee_id']); ?></p>
            <p class="mt-2 text-sm text-slate-500">Employee code.</p>
        </div>
    </section>
    <section class="rounded-[2rem] bg-white p-8 shadow-lg ring-1 ring-slate-200">
        <h2 class="text-xl font-semibold text-slate-900">Assigned Courses</h2>
        <?php if ($courseList): ?>
            <div class="mt-6 space-y-3">
                <?php foreach ($courseList as $course): ?>
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                        <p class="text-sm font-semibold text-slate-900"><?php echo htmlspecialchars($course['course_code']); ?> — <?php echo htmlspecialchars($course['course_name']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="mt-6 text-sm text-slate-500">No courses assigned yet.</p>
        <?php endif; ?>
    </section>
</div>
<?php include 'inc/footer.php'; ?>
