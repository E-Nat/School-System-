<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="mx-auto grid max-w-6xl gap-10 lg:grid-cols-[1.05fr_0.95fr]">
    <section class="hidden rounded-[2rem] bg-gradient-to-br from-cyan-700 via-slate-900 to-slate-950 p-10 text-white shadow-2xl lg:block">
        <p class="text-sm font-semibold uppercase tracking-[0.35em] text-cyan-200">Create account</p>
        <h1 class="mt-6 text-4xl font-semibold tracking-tight">Build your campus identity</h1>
        <p class="mt-6 text-base leading-7 text-slate-100">Register students, faculty, or staff with a modern secure workflow. Manage academic data, enrollments, and campus operations from day one.</p>
        <div class="mt-10 grid gap-4">
            <div class="rounded-3xl bg-white/10 p-6 ring-1 ring-white/10">
                <p class="text-sm uppercase tracking-[0.3em] text-cyan-200">Student-focused</p>
                <p class="mt-3 text-sm text-slate-200">Create profiles, enroll in courses, and request transcripts.</p>
            </div>
            <div class="rounded-3xl bg-white/10 p-6 ring-1 ring-white/10">
                <p class="text-sm uppercase tracking-[0.3em] text-cyan-200">Staff-ready</p>
                <p class="mt-3 text-sm text-slate-200">Support registrar, finance, lecturers, and department workflows.</p>
            </div>
        </div>
    </section>

    <div class="rounded-[2rem] bg-white p-8 shadow-2xl ring-1 ring-slate-200 sm:p-10">
        <div class="mb-8 text-center">
            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-cyan-600">Create your profile</p>
            <h1 class="mt-4 text-3xl font-semibold tracking-tight text-slate-900">Register as a campus user</h1>
            <p class="mt-2 text-sm text-slate-500">Create access for students, lecturers, finance staff, or academic leadership.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="mb-6 rounded-3xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="index.php?page=register" class="space-y-5">
            <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">

            <div class="grid gap-4 sm:grid-cols-2">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">First Name</span>
                    <input type="text" id="first_name" name="first_name" required class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
                </label>
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Last Name</span>
                    <input type="text" id="last_name" name="last_name" required class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
                </label>
            </div>

            <label class="block">
                <span class="mb-2 block text-sm font-medium text-slate-700">Email</span>
                <input type="email" id="email" name="email" required class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
            </label>

            <div class="grid gap-4 sm:grid-cols-2">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Password</span>
                    <input type="password" id="password" name="password" required class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
                </label>
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Confirm Password</span>
                    <input type="password" id="confirm_password" name="confirm_password" required class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
                </label>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Role</span>
                    <select id="role" name="role" required class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">
                        <option value="student" selected>Student</option>
                        <option value="lecturer">Lecturer</option>
                        <option value="department_head">Department Head</option>
                        <option value="dean">Dean</option>
                        <option value="registrar">Registrar</option>
                        <option value="finance_staff">Finance Staff</option>
                    </select>
                </label>
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Phone</span>
                    <input type="text" id="phone" name="phone" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
                </label>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Student Number</span>
                    <input type="text" id="student_number" name="student_number" placeholder="Required for student accounts" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
                </label>
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Employee ID</span>
                    <input type="text" id="employee_id" name="employee_id" placeholder="Required for staff and lecturers" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
                </label>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Department</span>
                    <input type="text" id="department" name="department" placeholder="Optional" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
                </label>
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Subject Area</span>
                    <input type="text" id="subject_area" name="subject_area" placeholder="Optional" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
                </label>
            </div>

            <button type="submit" class="inline-flex w-full items-center justify-center rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Register account</button>
        </form>

        <p class="mt-5 text-center text-sm text-slate-500">Already have an account? <a href="index.php?page=login" class="font-semibold text-cyan-600 hover:text-cyan-700">Sign in</a></p>
    </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>