<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="mb-8 rounded-4xl bg-gradient-to-r from-slate-900 via-slate-800 to-cyan-600 px-8 py-10 text-white shadow-2xl ring-1 ring-slate-200/10 sm:px-12">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-cyan-200/80">Lecturer dashboard</p>
            <h1 class="mt-4 text-4xl font-semibold tracking-tight">Welcome, <?php echo htmlspecialchars($_SESSION['user']['first_name'] ?? 'Lecturer'); ?>.</h1>
            <p class="mt-3 max-w-2xl text-sm text-white/80">Manage your courses, assignments, and student progress with a clean instructor workspace.</p>
        </div>
        <div>
            <a href="index.php?page=logout" class="inline-flex rounded-full bg-white px-6 py-3 text-sm font-semibold text-slate-900 transition hover:bg-slate-100">Logout</a>
        </div>
    </div>
</div>

<div class="grid gap-6 xl:grid-cols-[1.4fr_0.9fr]">
    <section class="rounded-4xl bg-white p-8 shadow-lg ring-1 ring-slate-200">
        <h2 class="text-xl font-semibold text-slate-900">Assigned Courses</h2>
        <p class="mt-2 text-sm text-slate-500">Review your course schedule, attendance lists, and grading tasks from one place.</p>
        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                <p class="text-sm text-slate-500">Current courses</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900">0</p>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                <p class="text-sm text-slate-500">Upcoming classes</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900">0</p>
            </div>
        </div>
        <a href="#" class="mt-6 inline-flex rounded-full bg-cyan-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-cyan-500">View schedule</a>
    </section>
    <section class="rounded-4xl bg-white p-8 shadow-lg ring-1 ring-slate-200">
        <h2 class="text-xl font-semibold text-slate-900">Recent submissions</h2>
        <p class="mt-2 text-sm text-slate-500">Check student assignments, assessments and progress updates quickly.</p>
        <div class="mt-6 grid gap-4">
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                <p class="text-sm text-slate-500">Pending grading</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900">0</p>
            </div>
            <a href="#" class="inline-flex rounded-full bg-cyan-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-cyan-500">View submissions</a>
        </div>
    </section>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>