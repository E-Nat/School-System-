<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="mb-8 rounded-4xl bg-gradient-to-r from-slate-900 via-slate-800 to-cyan-600 px-8 py-10 text-white shadow-2xl ring-1 ring-slate-200/10 sm:px-12">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-cyan-200/80">Admin dashboard</p>
            <h1 class="mt-4 text-4xl font-semibold tracking-tight">Welcome back, <?php echo htmlspecialchars($_SESSION['user']['first_name'] ?? 'Administrator'); ?>.</h1>
            <p class="mt-3 max-w-2xl text-sm text-white/80">As admin, you can manage teachers, students, attendance, reports, feedback, notifications, and full campus operations from one place.</p>
        </div>
        <div>
            <a href="index.php?page=logout" class="inline-flex rounded-full bg-white px-6 py-3 text-sm font-semibold text-slate-900 transition hover:bg-slate-100">Logout</a>
        </div>
    </div>
</div>

<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">Students</h2>
        <p class="mt-6 text-4xl font-semibold text-slate-900"><?php echo (int)($stats['students'] ?? 0); ?></p>
    </div>
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">Lecturers</h2>
        <p class="mt-6 text-4xl font-semibold text-slate-900"><?php echo (int)($stats['lecturers'] ?? 0); ?></p>
    </div>
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">Courses</h2>
        <p class="mt-6 text-4xl font-semibold text-slate-900"><?php echo (int)($stats['courses'] ?? 0); ?></p>
    </div>
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">Faculties</h2>
        <p class="mt-6 text-4xl font-semibold text-slate-900"><?php echo (int)($stats['faculties'] ?? 0); ?></p>
    </div>
</div>

<div class="mt-8 grid gap-6 xl:grid-cols-[1.4fr_0.9fr]">
    <section class="rounded-4xl bg-white p-8 shadow-lg ring-1 ring-slate-200">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Campus operations</h2>
                <p class="mt-2 text-sm text-slate-500">Track the most important metrics for your university in one glance.</p>
            </div>
            <span class="rounded-full bg-cyan-50 px-4 py-2 text-sm font-semibold text-cyan-700">Live overview</span>
        </div>
        <div class="mt-8 grid gap-4 sm:grid-cols-2">
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                <p class="text-sm text-slate-500">Active users</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900"><?php echo (int)($stats['active_users'] ?? 0); ?></p>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                <p class="text-sm text-slate-500">Pending requests</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900">0</p>
            </div>
        </div>
    </section>
    <section class="rounded-4xl bg-white p-8 shadow-lg ring-1 ring-slate-200">
        <h2 class="text-xl font-semibold text-slate-900">Action center</h2>
        <p class="mt-2 text-sm text-slate-500">Quick access to the tools you use daily.</p>
        <div class="mt-6 grid gap-4">
            <a href="/src/users.php" class="rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm font-semibold text-slate-900 transition hover:bg-slate-100">Manage users</a>
            <a href="/src/students.php" class="rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm font-semibold text-slate-900 transition hover:bg-slate-100">Manage students</a>
            <a href="/src/enrollments.php" class="rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm font-semibold text-slate-900 transition hover:bg-slate-100">Review enrollments</a>
            <a href="/src/finance.php" class="rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm font-semibold text-slate-900 transition hover:bg-slate-100">View finance</a>
        </div>
    </section>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>