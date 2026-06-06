<?php
require_once 'Database/dbConnect.php';
require_once 'inc/auth.php';
requireLogin(['super_admin', 'registrar', 'dean', 'department_head', 'finance_staff']);
$pdo = connectDB();

$stats = [
    'students' => $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn(),
    'teachers' => $pdo->query('SELECT COUNT(*) FROM teachers')->fetchColumn(),
    'courses' => $pdo->query('SELECT COUNT(*) FROM courses')->fetchColumn(),
    'enrollments' => $pdo->query('SELECT COUNT(*) FROM student_courses')->fetchColumn()
];

$latestStudents = $pdo->query('SELECT student_id, first_name, last_name, grade_level FROM students ORDER BY id DESC LIMIT 5')->fetchAll(PDO::FETCH_ASSOC);
$latestTeachers = $pdo->query('SELECT employee_id, first_name, last_name, department FROM teachers ORDER BY id DESC LIMIT 5')->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Admin Dashboard';
include 'inc/header.php';
?>
<div class="grid gap-6 xl:grid-cols-[320px_minmax(0,1fr)]">
    <aside class="space-y-6 rounded-[2rem] bg-slate-950 p-6 text-white shadow-2xl ring-1 ring-white/10">
        <div class="space-y-4">
            <div class="flex items-center gap-4 rounded-[2rem] bg-slate-900/90 p-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-cyan-500 text-xl font-semibold text-slate-950"><?php echo strtoupper(substr($_SESSION['user']['first_name'], 0, 1)); ?></div>
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-cyan-200/70">Admin</p>
                    <p class="text-lg font-semibold"><?php echo htmlspecialchars($_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name']); ?></p>
                    <p class="text-sm text-slate-400"><?php echo htmlspecialchars($_SESSION['user']['role']); ?></p>
                </div>
            </div>
            <div class="rounded-[1.75rem] bg-slate-900/80 p-5 ring-1 ring-white/10">
                <p class="text-xs uppercase tracking-[0.35em] text-slate-400">Summary</p>
                <div class="mt-4 grid gap-3">
                    <div class="rounded-3xl bg-slate-950/90 p-4">
                        <p class="text-sm text-slate-400">Students</p>
                        <p class="mt-2 text-2xl font-semibold text-white"><?php echo htmlspecialchars($stats['students']); ?></p>
                    </div>
                    <div class="rounded-3xl bg-slate-950/90 p-4">
                        <p class="text-sm text-slate-400">Courses</p>
                        <p class="mt-2 text-2xl font-semibold text-white"><?php echo htmlspecialchars($stats['courses']); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <nav class="space-y-2">
            <a href="admin_dashboard.php" class="flex items-center gap-3 rounded-3xl bg-white/10 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/15">Overview</a>
            <a href="users.php" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm text-slate-300 transition hover:bg-white/10">Users</a>
            <a href="students.php" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm text-slate-300 transition hover:bg-white/10">Students</a>
            <a href="teachers.php" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm text-slate-300 transition hover:bg-white/10">Teachers</a>
            <a href="courses.php" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm text-slate-300 transition hover:bg-white/10">Courses</a>
            <a href="logout.php" class="flex items-center gap-3 rounded-3xl px-4 py-3 text-sm text-slate-300 transition hover:bg-white/10">Logout</a>
        </nav>
    </aside>
    <section class="space-y-6">
        <div class="rounded-[2rem] bg-white p-6 shadow-lg ring-1 ring-slate-200">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-slate-500">Dashboard overview</p>
                    <h1 class="mt-3 text-3xl font-semibold text-slate-900">Welcome back, <?php echo htmlspecialchars($_SESSION['user']['first_name']); ?>.</h1>
                    <p class="mt-2 text-sm text-slate-500">Manage your school operations, review summary statistics, and launch workflows quickly.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="relative w-full max-w-sm">
                        <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">🔍</span>
                        <input type="search" placeholder="Search students, teachers, courses" class="w-full rounded-full border border-slate-200 bg-slate-50 py-3 pl-12 pr-4 text-sm text-slate-900 outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
                    </div>
                    <button class="rounded-full bg-cyan-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-cyan-500">New Report</button>
                </div>
            </div>
        </div>
        <div class="grid gap-6 xl:grid-cols-4">
            <div class="rounded-[2rem] bg-white p-6 shadow-lg ring-1 ring-slate-200">
                <p class="text-sm uppercase tracking-[0.35em] text-slate-500">Students</p>
                <p class="mt-4 text-4xl font-semibold text-slate-900"><?php echo htmlspecialchars($stats['students']); ?></p>
                <p class="mt-2 text-sm text-slate-500">Total active students.</p>
            </div>
            <div class="rounded-[2rem] bg-white p-6 shadow-lg ring-1 ring-slate-200">
                <p class="text-sm uppercase tracking-[0.35em] text-slate-500">Teachers</p>
                <p class="mt-4 text-4xl font-semibold text-slate-900"><?php echo htmlspecialchars($stats['teachers']); ?></p>
                <p class="mt-2 text-sm text-slate-500">Total teaching staff.</p>
            </div>
            <div class="rounded-[2rem] bg-white p-6 shadow-lg ring-1 ring-slate-200">
                <p class="text-sm uppercase tracking-[0.35em] text-slate-500">Courses</p>
                <p class="mt-4 text-4xl font-semibold text-slate-900"><?php echo htmlspecialchars($stats['courses']); ?></p>
                <p class="mt-2 text-sm text-slate-500">Total active course offerings.</p>
            </div>
            <div class="rounded-[2rem] bg-white p-6 shadow-lg ring-1 ring-slate-200">
                <p class="text-sm uppercase tracking-[0.35em] text-slate-500">Enrollments</p>
                <p class="mt-4 text-4xl font-semibold text-slate-900"><?php echo htmlspecialchars($stats['enrollments']); ?></p>
                <p class="mt-2 text-sm text-slate-500">Enrollments recorded.</p>
            </div>
        </div>
        <div class="grid gap-6 xl:grid-cols-[1.5fr_1fr]">
            <div class="rounded-[2rem] bg-white p-8 shadow-lg ring-1 ring-slate-200">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900">Recent activity</h2>
                        <p class="mt-2 text-sm text-slate-500">Latest students and teacher registrations.</p>
                    </div>
                    <button class="rounded-full bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">View all</button>
                </div>
                <div class="mt-8 space-y-6">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Latest Students</p>
                        <div class="mt-4 overflow-hidden rounded-3xl border border-slate-200">
                            <table class="min-w-full divide-y divide-slate-200 text-sm text-slate-700">
                                <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.2em] text-slate-500">
                                    <tr>
                                        <th class="px-4 py-3">Student ID</th>
                                        <th class="px-4 py-3">Name</th>
                                        <th class="px-4 py-3">Grade</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 bg-white">
                                    <?php foreach ($latestStudents as $student): ?>
                                        <tr>
                                            <td class="px-4 py-3"><?php echo htmlspecialchars($student['student_id']); ?></td>
                                            <td class="px-4 py-3"><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                                            <td class="px-4 py-3"><?php echo htmlspecialchars($student['grade_level']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Latest Teachers</p>
                        <div class="mt-4 overflow-hidden rounded-3xl border border-slate-200">
                            <table class="min-w-full divide-y divide-slate-200 text-sm text-slate-700">
                                <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.2em] text-slate-500">
                                    <tr>
                                        <th class="px-4 py-3">Employee ID</th>
                                        <th class="px-4 py-3">Name</th>
                                        <th class="px-4 py-3">Department</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 bg-white">
                                    <?php foreach ($latestTeachers as $teacher): ?>
                                        <tr>
                                            <td class="px-4 py-3"><?php echo htmlspecialchars($teacher['employee_id']); ?></td>
                                            <td class="px-4 py-3"><?php echo htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']); ?></td>
                                            <td class="px-4 py-3"><?php echo htmlspecialchars($teacher['department']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-y-6">
                <div class="rounded-[2rem] bg-white p-8 shadow-lg ring-1 ring-slate-200">
                    <h2 class="text-xl font-semibold text-slate-900">Top actions</h2>
                    <div class="mt-6 space-y-3">
                        <a href="users.php" class="flex items-center justify-between rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm font-semibold text-slate-900 hover:bg-slate-100">Manage Users <span class="text-slate-400">→</span></a>
                        <a href="students.php" class="flex items-center justify-between rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm font-semibold text-slate-900 hover:bg-slate-100">Manage Students <span class="text-slate-400">→</span></a>
                        <a href="teachers.php" class="flex items-center justify-between rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm font-semibold text-slate-900 hover:bg-slate-100">Manage Teachers <span class="text-slate-400">→</span></a>
                        <a href="courses.php" class="flex items-center justify-between rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm font-semibold text-slate-900 hover:bg-slate-100">Manage Courses <span class="text-slate-400">→</span></a>
                        <a href="logout.php" class="flex items-center justify-between rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm font-semibold text-slate-900 hover:bg-slate-100">Sign Out <span class="text-slate-400">→</span></a>
                    </div>
                </div>
                <div class="rounded-[2rem] bg-gradient-to-br from-slate-950 via-cyan-800 to-cyan-600 p-8 text-white shadow-2xl ring-1 ring-white/10">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm uppercase tracking-[0.35em] text-cyan-200/80">Status</p>
                            <h3 class="mt-4 text-2xl font-semibold">System healthy</h3>
                            <p class="mt-2 text-sm text-cyan-100/80">All core services are operating normally. No critical alerts.</p>
                        </div>
                        <div class="rounded-3xl bg-white/10 px-4 py-3 text-sm font-semibold text-white">Online</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php include 'inc/footer.php'; ?>
