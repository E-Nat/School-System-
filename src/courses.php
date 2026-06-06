<?php
require_once 'Database/dbConnect.php';
require_once 'inc/auth.php';
requireLogin(['admin','teacher']);
$pdo = connectDB();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isAdmin()) {
    $courseCode = trim($_POST['course_code'] ?? '');
    $courseName = trim($_POST['course_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $teacherId = trim($_POST['teacher_id'] ?? null);
    $creditHours = trim($_POST['credit_hours'] ?? 3);

    if (!$courseCode || !$courseName) {
        $errors[] = 'Course code and course name are required.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO courses (course_code, course_name, description, teacher_id, credit_hours) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$courseCode, $courseName, $description, $teacherId ?: null, $creditHours]);
        header('Location: courses.php');
        exit;
    }
}

$teachers = $pdo->query('SELECT id, employee_id, subject_area FROM teachers ORDER BY created_at DESC')->fetchAll();
$courses = $pdo->query('SELECT c.*, t.employee_id FROM courses c LEFT JOIN teachers t ON c.teacher_id = t.id ORDER BY c.created_at DESC')->fetchAll();
$pageTitle = 'Courses';
include 'inc/header.php';
?>
<div class="space-y-6">
    <section class="rounded-[2rem] bg-white p-8 shadow-lg ring-1 ring-slate-200">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-slate-900">Courses</h1>
                <p class="mt-2 text-sm text-slate-500">Manage course catalog, credit hours, and teacher assignments.</p>
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
    <?php if (isAdmin()): ?>
        <section class="rounded-[2rem] bg-white p-8 shadow-lg ring-1 ring-slate-200">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Create Course</h2>
                    <p class="mt-2 text-sm text-slate-500">Publish new course offerings and assign teaching staff.</p>
                </div>
            </div>
            <form method="POST" class="mt-6 grid gap-4 lg:grid-cols-3">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Course Code</span>
                    <input type="text" name="course_code" required class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
                </label>
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Course Name</span>
                    <input type="text" name="course_name" required class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
                </label>
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Credit Hours</span>
                    <input type="number" name="credit_hours" value="3" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100" />
                </label>
                <label class="block lg:col-span-3">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Description</span>
                    <textarea name="description" rows="3" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"></textarea>
                </label>
                <label class="block lg:col-span-2">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Assigned Teacher</span>
                    <select name="teacher_id" class="w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">
                        <option value="">Unassigned</option>
                        <?php foreach ($teachers as $teacher): ?>
                            <option value="<?php echo htmlspecialchars($teacher['id']); ?>"><?php echo htmlspecialchars($teacher['employee_id'] . ' — ' . $teacher['subject_area']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <div class="lg:col-span-3">
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-full bg-cyan-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-cyan-500">Create Course</button>
                </div>
            </form>
        </section>
    <?php endif; ?>
    <section class="rounded-[2rem] bg-white p-8 shadow-lg ring-1 ring-slate-200">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Course Catalog</h2>
                <p class="mt-2 text-sm text-slate-500">Active courses and teaching assignments.</p>
            </div>
        </div>
        <div class="mt-6 overflow-hidden rounded-3xl border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-4 text-left font-semibold text-slate-500">Code</th>
                        <th class="px-4 py-4 text-left font-semibold text-slate-500">Name</th>
                        <th class="px-4 py-4 text-left font-semibold text-slate-500">Teacher</th>
                        <th class="px-4 py-4 text-left font-semibold text-slate-500">Credits</th>
                        <th class="px-4 py-4 text-left font-semibold text-slate-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td class="px-4 py-4 text-slate-900"><?php echo htmlspecialchars($course['course_code']); ?></td>
                            <td class="px-4 py-4 text-slate-700"><?php echo htmlspecialchars($course['course_name']); ?></td>
                            <td class="px-4 py-4 text-slate-700"><?php echo htmlspecialchars($course['employee_id'] ?: 'Unassigned'); ?></td>
                            <td class="px-4 py-4 text-slate-700"><?php echo htmlspecialchars($course['credit_hours']); ?></td>
                            <td class="px-4 py-4 text-slate-700"><?php echo htmlspecialchars($course['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?php include 'inc/footer.php'; ?>
