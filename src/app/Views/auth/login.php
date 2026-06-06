<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="mx-auto grid max-w-6xl gap-10 lg:grid-cols-[1.05fr_0.95fr]">
    <section class="hidden rounded-[2rem] bg-gradient-to-br from-slate-950 via-slate-900 to-cyan-600 p-10 text-white shadow-2xl lg:block">
        <p class="text-sm font-semibold uppercase tracking-[0.35em] text-cyan-300">Welcome back</p>
        <h1 class="mt-6 text-4xl font-semibold tracking-tight">Secure access to your university portal</h1>
        <p class="mt-6 text-base leading-7 text-slate-200">Login and access your dashboard with role-based security, attendance, enrollment, grades, and academic workflows.</p>
        <div class="mt-10 grid gap-4">
            <div class="rounded-3xl bg-white/10 p-6 ring-1 ring-white/10">
                <p class="text-sm uppercase tracking-[0.3em] text-cyan-200">Fast access</p>
                <p class="mt-3 text-sm text-slate-100">Single sign-on experience for all campus roles.</p>
            </div>
            <div class="rounded-3xl bg-white/10 p-6 ring-1 ring-white/10">
                <p class="text-sm uppercase tracking-[0.3em] text-cyan-200">Secure login</p>
                <p class="mt-3 text-sm text-slate-100">Built with bcrypt, CSRF protection, and secure session handling.</p>
            </div>
        </div>
    </section>

    <div class="rounded-[2rem] bg-white p-8 shadow-2xl ring-1 ring-slate-200 sm:p-10">
        <div class="mb-8 text-center">
            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-cyan-600">University System</p>
            <h1 class="mt-4 text-3xl font-semibold tracking-tight text-slate-900">Sign in to your account</h1>
            <p class="mt-2 text-sm text-slate-500">Securely access your dashboard as Super Admin, Lecturer, Student, or Staff.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="mb-6 rounded-3xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="index.php?page=login" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?php echo \App\Core\Security::generateCsrfToken(); ?>">

            <label class="block">
                <span class="mb-2 block text-sm font-medium text-slate-700">Email address</span>
                <input type="email" id="email" name="email" required class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
            </label>

            <label class="block">
                <span class="mb-2 block text-sm font-medium text-slate-700">Password</span>
                <input type="password" id="password" name="password" required class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
            </label>

            <button type="submit" class="inline-flex w-full items-center justify-center rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Sign In</button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">New to UMS? <a href="index.php?page=register" class="font-semibold text-cyan-600 hover:text-cyan-700">Create an account</a></p>
    </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>