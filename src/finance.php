<?php
require_once 'Database/dbConnect.php';
require_once 'inc/auth.php';
requireLogin(['super_admin', 'admin', 'registrar', 'dean', 'department_head', 'finance_staff']);
$pdo = connectDB();

$payments = $pdo->query('SELECT p.id, s.student_id, s.first_name, s.last_name, p.amount, p.method, p.paid_at FROM payments p JOIN students s ON p.student_id = s.id ORDER BY p.paid_at DESC LIMIT 25')->fetchAll();

$pageTitle = 'Finance';
include 'inc/header.php';
?>
<div class="space-y-6">
    <section class="rounded-[2rem] bg-white p-8 shadow-lg ring-1 ring-slate-200">
        <h1 class="text-3xl font-semibold text-slate-900">Finance overview</h1>
        <p class="mt-3 text-sm text-slate-500">Review recent payments and tuition transaction activity.</p>
    </section>
    <section class="rounded-[2rem] bg-white p-8 shadow-lg ring-1 ring-slate-200">
        <?php if ($payments): ?>
            <div class="overflow-hidden rounded-3xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm text-slate-700">
                    <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.2em] text-slate-500">
                        <tr>
                            <th class="px-4 py-3">Student</th>
                            <th class="px-4 py-3">Amount</th>
                            <th class="px-4 py-3">Method</th>
                            <th class="px-4 py-3">Paid At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($payment['student_id'] . ' - ' . $payment['first_name'] . ' ' . $payment['last_name']); ?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars(number_format($payment['amount'], 2)); ?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($payment['method']); ?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($payment['paid_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-sm text-slate-500">No payments found yet.</p>
        <?php endif; ?>
    </section>
</div>
<?php include 'inc/footer.php'; ?>