<?php
require_once 'Database/dbConnect.php';
require_once 'inc/auth.php';
requireLogin(['admin','teacher']);
$pdo = connectDB();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = trim($_POST['student_id'] ?? '');
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $gradeLevel = trim($_POST['grade_level'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $guardianName = trim($_POST['guardian_name'] ?? '');
    $guardianPhone = trim($_POST['guardian_phone'] ?? '');
    $enrollmentDate = trim($_POST['enrollment_date'] ?? '');

    if (!$studentId || !$firstName || !$lastName) {
        $errors[] = 'Student ID, first name, and last name are required.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO students (student_id, first_name, last_name, grade_level, email, phone, guardian_name, guardian_phone, enrollment_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$studentId, $firstName, $lastName, $gradeLevel, $email, $phone, $guardianName, $guardianPhone, $enrollmentDate]);
        header('Location: students.php');
        exit;
    }
}

$students = $pdo->query('SELECT * FROM students ORDER BY created_at DESC')->fetchAll();
$pageTitle = 'Students';
include 'inc/header.php';
?>
<div class="space-y-6">
    <section class="rounded-[2rem] bg-white p-8 shadow-lg ring-1 ring-slate-200">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-slate-900">Students</h1>
                <p class="mt-2 text-sm text-slate-500">Track student records, enrollment details, and academic progress.</p>
            </div>
        </div>
    </section>
    <?php if ($errors): ?>
        <div class="rounded-3xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
            <ul class="list-disc space-y-1 pl-5">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <section class="rounded-[2rem] bg-white p-8 shadow-lg ring-1 ring-slate-200">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Add Student</h2>
                <p class="mt-2 text-sm text-slate-500">Create and manage student enrollment records.</p>
            </div>
        </div>
        <form method="POST" class="mt-6 grid gap-4 lg:grid-cols-3">
            <label class="block">
                <span class="mb-2 block text-sm font-medium text-slate-700">Student ID</span>
                <input type="text" name="student_id" required class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-medium text-slate-700">First Name</span>
                <input type="text" name="first_name" required class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-medium text-slate-700">Last Name</span>
                <input type="text" name="last_name" required class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-medium text-slate-700">Grade Level</span>
                <input type="text" name="grade_level" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-medium text-slate-700">Email</span>
                <input type="email" name="email" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-medium text-slate-700">Phone</span>
                <input type="text" name="phone" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-medium text-slate-700">Guardian Name</span>
                <input type="text" name="guardian_name" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-medium text-slate-700">Guardian Phone</span>
                <input type="text" name="guardian_phone" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
            </label>
            <label class="block">
                <span class="mb-2 block text-sm font-medium text-slate-700">Enrollment Date</span>
                <input type="date" name="enrollment_date" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
            </label>
            <div class="lg:col-span-3">
                <button type="submit" class="inline-flex w-full items-center justify-center rounded-full bg-cyan-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-cyan-500">Save Student</button>
            </div>
        </form>
    </section>
    <section class="rounded-[2rem] bg-white p-8 shadow-lg ring-1 ring-slate-200">
        <h2 class="text-xl font-semibold text-slate-900">Student List</h2>
        <div class="mt-6 overflow-hidden rounded-3xl border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-4 text-left font-semibold text-slate-500">Student ID</th>
                        <th class="px-4 py-4 text-left font-semibold text-slate-500">Name</th>
                        <th class="px-4 py-4 text-left font-semibold text-slate-500">Grade</th>
                        <th class="px-4 py-4 text-left font-semibold text-slate-500">Email</th>
                        <th class="px-4 py-4 text-left font-semibold text-slate-500">Phone</th>
                        <th class="px-4 py-4 text-left font-semibold text-slate-500">Guardian</th>
                        <th class="px-4 py-4 text-left font-semibold text-slate-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td class="px-4 py-4 text-slate-900"><?php echo htmlspecialchars($student['student_id']); ?></td>
                            <td class="px-4 py-4 text-slate-700"><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                            <td class="px-4 py-4 text-slate-700"><?php echo htmlspecialchars($student['grade_level']); ?></td>
                            <td class="px-4 py-4 text-slate-700"><?php echo htmlspecialchars($student['email']); ?></td>
                            <td class="px-4 py-4 text-slate-700"><?php echo htmlspecialchars($student['phone']); ?></td>
                            <td class="px-4 py-4 text-slate-700"><?php echo htmlspecialchars($student['guardian_name']); ?></td>
                            <td class="px-4 py-4 text-slate-700"><?php echo htmlspecialchars($student['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?php include 'inc/footer.php'; ?>
