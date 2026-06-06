<?php
$pageTitle = 'About';
include 'inc/header.php';
?>
<div class="mx-auto max-w-4xl space-y-8">
    <section class="rounded-[2rem] bg-white p-8 shadow-lg ring-1 ring-slate-200">
        <h1 class="text-3xl font-semibold text-slate-900">About the School System</h1>
        <p class="mt-4 text-slate-600">This school management system is designed to help administrators and teachers manage student tracking, attendance, academic progress, and course assignments from a single platform.</p>
        <div class="mt-6 space-y-4 text-slate-600">
            <p class="font-semibold text-slate-900">What this system supports:</p>
            <ul class="list-inside list-disc space-y-2">
                <li>Admin users can manage students, teachers, courses, and enrollment data.</li>
                <li>Teachers can access assigned courses, review student lists, and track progress.</li>
                <li>The database structure supports student records, course enrollment, attendance, and notes.</li>
            </ul>
            <p>Use the navigation menu to access the student, teacher, and course management pages.</p>
        </div>
    </section>
</div>
<?php include 'inc/footer.php'; ?>
