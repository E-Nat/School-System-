<?php
require_once 'Database/dbConnect.php';
require_once 'inc/auth.php';
requireLogin(['super_admin', 'admin', 'registrar', 'dean', 'department_head', 'finance_staff']);
$pdo = connectDB();

$enrollments = $pdo->query('SELECT sc.id, s.student_id, s.first_name, s.last_name, c.course_code, c.course_name, sc.enroll_date, sc.status FROM student_courses sc JOIN students s ON sc.student_id = s.id JOIN courses c ON sc.course_id = c.id ORDER BY sc.enroll_date DESC LIMIT 25')->fetchAll();

$pageTitle = 'Enrollments';
include 'inc/header.php';
?>
<div class="space-y-6">
    <section class="rounded-[2rem] bg-white p-8 shadow-lg ring-1 ring-slate-200">
        <h1 class="text-3xl font-semibold text-slate-900">Review enrollments</h1>
        <p class="mt-3 text-sm text-slate-500">Browse recent enrollment records and verify student course registrations.</p>
    </section>
    <section class="rounded-[2rem] bg-white p-8 shadow-lg ring-1 ring-slate-200">
        <?php if ($enrollments): ?>
            <div class="overflow-hidden rounded-3xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm text-slate-700">
                    <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.2em] text-slate-500">
                        <tr>
                            <th class="px-4 py-3">Student</th>
                            <th class="px-4 py-3">Course</th>
                            <th class="px-4 py-3">Enrollment Date</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        <?php foreach ($enrollments as $enrollment): ?>
                            <tr>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($enrollment['student_id'] . ' - ' . $enrollment['first_name'] . ' ' . $enrollment['last_name']); ?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($enrollment['course_code'] . ' - ' . $enrollment['course_name']); ?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($enrollment['enroll_date']); ?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($enrollment['status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-sm text-slate-500">No enrollment records found yet.</p>
        <?php endif; ?>
    </section>
</div>
<?php include 'inc/footer.php'; ?>