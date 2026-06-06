<?php
require_once 'Database/dbConnect.php';
require_once 'inc/auth.php';
requireLogin(['admin','teacher']);
$pdo = connectDB();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isAdmin()) {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $employeeId = trim($_POST['employee_id'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $subjectArea = trim($_POST['subject_area'] ?? '');

    if (!$firstName || !$lastName || !$email || !$employeeId) {
        $errors[] = 'First name, last name, email, and employee ID are required.';
    }

    if (!$errors) {
        $passwordHash = password_hash('Teacher123!', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?);');
        $stmt->execute([$firstName, $lastName, $email, $passwordHash, 'teacher']);
        $userId = $pdo->lastInsertId();
        $stmt = $pdo->prepare('INSERT INTO teachers (user_id, employee_id, department, subject_area) VALUES (?, ?, ?, ?)');
        $stmt->execute([$userId, $employeeId, $department, $subjectArea]);
        header('Location: teachers.php');
        exit;
    }
}

$teachers = $pdo->query('SELECT t.*, u.email, u.first_name, u.last_name FROM teachers t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC')->fetchAll();
$pageTitle = 'Teachers';
include 'inc/header.php';
?>
<div class="space-y-6">
    <div class="rounded-[2rem] bg-white p-8 shadow-lg ring-1 ring-slate-200">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-slate-900">Teachers</h1>
                <p class="mt-2 text-sm text-slate-500">Manage teacher profiles, assignments, and department responsibilities.</p>
            </div>
        </div>
    </div>
    <?php if ($errors): ?>
        <div class="rounded-3xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
            <ul class="list-disc space-y-1 pl-5">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php if (isAdmin()): ?>
        <section class="rounded-[2rem] bg-white p-8 shadow-lg ring-1 ring-slate-200">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Add Teacher</h2>
                    <p class="mt-2 text-sm text-slate-500">Create a new teacher record and assign departments.</p>
                </div>
            </div>
            <form method="POST" class="mt-6 grid gap-4 lg:grid-cols-3">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">First Name</span>
                    <input type="text" name="first_name" required class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
                </label>
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Last Name</span>
                    <input type="text" name="last_name" required class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
                </label>
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Email</span>
                    <input type="email" name="email" required class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
                </label>
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Employee ID</span>
                    <input type="text" name="employee_id" required class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
                </label>
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Department</span>
                    <input type="text" name="department" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
                </label>
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Subject Area</span>
                    <input type="text" name="subject_area" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
                </label>
                <div class="lg:col-span-3">
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-full bg-cyan-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-cyan-500">Add Teacher</button>
                </div>
            </form>
        </section>
    <?php endif; ?>
    <section class="rounded-[2rem] bg-white p-8 shadow-lg ring-1 ring-slate-200">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Teacher List</h2>
                <p class="mt-2 text-sm text-slate-500">All active teachers in the system.</p>
            </div>
        </div>
        <div class="mt-6 overflow-hidden rounded-3xl border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-4 text-left font-semibold text-slate-500">Name</th>
                        <th class="px-4 py-4 text-left font-semibold text-slate-500">Email</th>
                        <th class="px-4 py-4 text-left font-semibold text-slate-500">Employee ID</th>
                        <th class="px-4 py-4 text-left font-semibold text-slate-500">Department</th>
                        <th class="px-4 py-4 text-left font-semibold text-slate-500">Subject</th>
                        <th class="px-4 py-4 text-left font-semibold text-slate-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    <?php foreach ($teachers as $teacher): ?>
                        <tr>
                            <td class="px-4 py-4 text-slate-900"><?php echo htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']); ?></td>
                            <td class="px-4 py-4 text-slate-700"><?php echo htmlspecialchars($teacher['email']); ?></td>
                            <td class="px-4 py-4 text-slate-700"><?php echo htmlspecialchars($teacher['employee_id']); ?></td>
                            <td class="px-4 py-4 text-slate-700"><?php echo htmlspecialchars($teacher['department']); ?></td>
                            <td class="px-4 py-4 text-slate-700"><?php echo htmlspecialchars($teacher['subject_area']); ?></td>
                            <td class="px-4 py-4 text-slate-700"><?php echo htmlspecialchars($teacher['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?php include 'inc/footer.php'; ?>
