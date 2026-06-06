<?php
require_once 'Database/dbConnect.php';
require_once 'inc/auth.php';
requireLogin(['super_admin', 'admin', 'registrar']);
$pdo = connectDB();

$errors = [];
$success = false;

// Get available roles
$roles = $pdo->query('SELECT id, code, name FROM roles ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);

// Handle CREATE operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $roleId = trim($_POST['role_id'] ?? '');
    $password = 'DefaultPass123!'; // Default password for new users

    // Validate inputs
    if (!$firstName || !$lastName) {
        $errors[] = 'First name and last name are required.';
    }
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email address is required.';
    }
    if (!$roleId) {
        $errors[] = 'Role is required.';
    }

    // Check if email already exists
    if (!$errors) {
        $existing = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $existing->execute([$email]);
        if ($existing->fetch()) {
            $errors[] = 'Email address already exists.';
        }
    }

    if (!$errors) {
        try {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (role_id, first_name, last_name, email, password, phone, status) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$roleId, $firstName, $lastName, $email, $passwordHash, $phone, 'active']);
            $success = true;
            // Redirect to prevent form resubmission
            header('Location: users.php?success=1');
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
}

// Handle UPDATE operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $userId = (int)$_POST['user_id'] ?? 0;
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $roleId = trim($_POST['role_id'] ?? '');
    $status = trim($_POST['status'] ?? 'active');

    // Validate inputs
    if (!$userId) {
        $errors[] = 'Invalid user ID.';
    }
    if (!$firstName || !$lastName) {
        $errors[] = 'First name and last name are required.';
    }
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email address is required.';
    }
    if (!$roleId) {
        $errors[] = 'Role is required.';
    }

    // Check if email is unique (excluding current user)
    if (!$errors) {
        $existing = $pdo->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
        $existing->execute([$email, $userId]);
        if ($existing->fetch()) {
            $errors[] = 'Email address already exists.';
        }
    }

    if (!$errors) {
        try {
            $stmt = $pdo->prepare('UPDATE users SET role_id = ?, first_name = ?, last_name = ?, email = ?, phone = ?, status = ? WHERE id = ?');
            $stmt->execute([$roleId, $firstName, $lastName, $email, $phone, $status, $userId]);
            header('Location: users.php?success=1&action=update');
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
}

// Handle DELETE operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $userId = (int)$_POST['user_id'] ?? 0;
    
    if (!$userId) {
        $errors[] = 'Invalid user ID.';
    }
    
    // Prevent deleting own account
    if ($userId === $_SESSION['user']['id']) {
        $errors[] = 'You cannot delete your own account.';
    }

    if (!$errors) {
        try {
            $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$userId]);
            header('Location: users.php?success=1&action=delete');
            exit;
        } catch (PDOException $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
}

// Fetch all users with their role information
$users = $pdo->query('SELECT u.id, u.first_name, u.last_name, u.email, u.phone, u.status, u.created_at, r.name as role_name, r.code as role_code FROM users u LEFT JOIN roles r ON u.role_id = r.id ORDER BY u.created_at DESC')->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'User Management';
include 'inc/header.php';
?>

<div class="space-y-6">
    <!-- Header Section -->
    <section class="rounded-[2rem] bg-white p-8 shadow-lg ring-1 ring-slate-200">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-slate-900">User Management</h1>
                <p class="mt-2 text-sm text-slate-500">Create, update, and manage system users and their roles.</p>
            </div>
            <button onclick="document.getElementById('createModal').showModal()" class="rounded-full bg-cyan-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-cyan-500">+ Add User</button>
        </div>
    </section>

    <!-- Success Message -->
    <?php if (isset($_GET['success'])): ?>
        <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
            <div class="font-semibold">✓ Success</div>
            <p class="mt-1">
                <?php 
                if (isset($_GET['action']) && $_GET['action'] === 'delete') {
                    echo 'User deleted successfully.';
                } elseif (isset($_GET['action']) && $_GET['action'] === 'update') {
                    echo 'User updated successfully.';
                } else {
                    echo 'User created successfully.';
                }
                ?>
            </p>
        </div>
    <?php endif; ?>

    <!-- Error Messages -->
    <?php if ($errors): ?>
        <div class="rounded-3xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
            <div class="font-semibold">✗ Error</div>
            <ul class="mt-2 space-y-1">
                <?php foreach ($errors as $error): ?>
                    <li>• <?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Users Table -->
    <div class="rounded-[2rem] bg-white p-8 shadow-lg ring-1 ring-slate-200">
        <div class="mb-6">
            <input type="text" id="searchInput" placeholder="Search users by name or email..." class="w-full rounded-3xl border border-slate-200 px-5 py-3 text-sm text-slate-900 outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm text-slate-700">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.2em] text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Phone</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Created</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white" id="usersTableBody">
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-500">No users found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-slate-50" data-user-name="<?php echo htmlspecialchars(strtolower($user['first_name'] . ' ' . $user['last_name'])); ?>" data-user-email="<?php echo htmlspecialchars(strtolower($user['email'])); ?>">
                                <td class="px-4 py-3 font-semibold text-slate-900"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($user['email']); ?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($user['phone'] ?? '-'); ?></td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-cyan-100 px-3 py-1 text-xs font-semibold text-cyan-700"><?php echo htmlspecialchars($user['role_name']); ?></span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full <?php echo $user['status'] === 'active' ? 'bg-emerald-100 text-emerald-700' : ($user['status'] === 'inactive' ? 'bg-slate-100 text-slate-700' : 'bg-rose-100 text-rose-700'); ?> px-3 py-1 text-xs font-semibold">
                                        <?php echo ucfirst(htmlspecialchars($user['status'])); ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-2">
                                        <button onclick="editUser(<?php echo $user['id']; ?>)" class="text-blue-600 hover:text-blue-800 font-semibold">Edit</button>
                                        <?php if ($user['id'] !== $_SESSION['user']['id']): ?>
                                            <button onclick="deleteUser(<?php echo $user['id']; ?>)" class="text-rose-600 hover:text-rose-800 font-semibold">Delete</button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create User Modal -->
<dialog id="createModal" class="rounded-3xl border border-slate-200 shadow-2xl backdrop:bg-black/50">
    <form method="POST" class="w-full max-w-md space-y-4 p-8">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">Add New User</h2>
            <p class="mt-1 text-sm text-slate-500">Create a new user account in the system.</p>
        </div>

        <div class="space-y-4">
            <div>
                <label class="text-sm font-semibold text-slate-700">First Name *</label>
                <input type="text" name="first_name" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-2 text-slate-900 outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-700">Last Name *</label>
                <input type="text" name="last_name" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-2 text-slate-900 outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-700">Email *</label>
                <input type="email" name="email" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-2 text-slate-900 outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-700">Phone</label>
                <input type="tel" name="phone" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-2 text-slate-900 outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-700">Role *</label>
                <select name="role_id" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-2 text-slate-900 outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">
                    <option value="">Select a role...</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?php echo htmlspecialchars($role['id']); ?>"><?php echo htmlspecialchars($role['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="rounded-2xl bg-slate-50 p-3">
                <p class="text-xs text-slate-600"><strong>Note:</strong> Default password will be sent to the user's email.</p>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="button" onclick="document.getElementById('createModal').close()" class="flex-1 rounded-2xl border border-slate-200 px-4 py-2 text-slate-700 hover:bg-slate-50">Cancel</button>
            <button type="submit" name="action" value="create" class="flex-1 rounded-2xl bg-cyan-600 px-4 py-2 text-white hover:bg-cyan-500">Create User</button>
        </div>
    </form>
</dialog>

<!-- Edit User Modal -->
<dialog id="editModal" class="rounded-3xl border border-slate-200 shadow-2xl backdrop:bg-black/50">
    <form method="POST" class="w-full max-w-md space-y-4 p-8">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">Edit User</h2>
            <p class="mt-1 text-sm text-slate-500">Update user information and role.</p>
        </div>

        <input type="hidden" name="action" value="update">
        <input type="hidden" name="user_id" id="editUserId">

        <div class="space-y-4">
            <div>
                <label class="text-sm font-semibold text-slate-700">First Name *</label>
                <input type="text" name="first_name" id="editFirstName" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-2 text-slate-900 outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-700">Last Name *</label>
                <input type="text" name="last_name" id="editLastName" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-2 text-slate-900 outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-700">Email *</label>
                <input type="email" name="email" id="editEmail" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-2 text-slate-900 outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-700">Phone</label>
                <input type="tel" name="phone" id="editPhone" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-2 text-slate-900 outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-700">Role *</label>
                <select name="role_id" id="editRoleId" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-2 text-slate-900 outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">
                    <option value="">Select a role...</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?php echo htmlspecialchars($role['id']); ?>"><?php echo htmlspecialchars($role['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-700">Status *</label>
                <select name="status" id="editStatus" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-2 text-slate-900 outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="suspended">Suspended</option>
                </select>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="button" onclick="document.getElementById('editModal').close()" class="flex-1 rounded-2xl border border-slate-200 px-4 py-2 text-slate-700 hover:bg-slate-50">Cancel</button>
            <button type="submit" class="flex-1 rounded-2xl bg-cyan-600 px-4 py-2 text-white hover:bg-cyan-500">Update User</button>
        </div>
    </form>
</dialog>

<!-- Delete Confirmation Modal -->
<dialog id="deleteModal" class="rounded-3xl border border-slate-200 shadow-2xl backdrop:bg-black/50">
    <form method="POST" class="w-full max-w-md space-y-4 p-8">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">Delete User</h2>
            <p class="mt-3 text-sm text-slate-600">Are you sure you want to delete this user? This action cannot be undone.</p>
        </div>

        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="user_id" id="deleteUserId">

        <div class="flex gap-3">
            <button type="button" onclick="document.getElementById('deleteModal').close()" class="flex-1 rounded-2xl border border-slate-200 px-4 py-2 text-slate-700 hover:bg-slate-50">Cancel</button>
            <button type="submit" class="flex-1 rounded-2xl bg-rose-600 px-4 py-2 text-white hover:bg-rose-500">Delete User</button>
        </div>
    </form>
</dialog>

<?php include 'inc/footer.php'; ?>

<script>
// Store user data for edit modal
const userDataMap = {
    <?php foreach ($users as $user): ?>
        <?php echo $user['id']; ?>: {
            first_name: '<?php echo addslashes(htmlspecialchars($user['first_name'])); ?>',
            last_name: '<?php echo addslashes(htmlspecialchars($user['last_name'])); ?>',
            email: '<?php echo addslashes(htmlspecialchars($user['email'])); ?>',
            phone: '<?php echo addslashes(htmlspecialchars($user['phone'] ?? '')); ?>',
            role_id: '<?php echo htmlspecialchars($user['id']); ?>',
            status: 'active',
            role_code: '<?php echo htmlspecialchars($user['role_code']); ?>'
        },
    <?php endforeach; ?>
};

function editUser(userId) {
    const user = userDataMap[userId];
    if (!user) return;

    document.getElementById('editUserId').value = userId;
    document.getElementById('editFirstName').value = user.first_name;
    document.getElementById('editLastName').value = user.last_name;
    document.getElementById('editEmail').value = user.email;
    document.getElementById('editPhone').value = user.phone;
    document.getElementById('editStatus').value = user.status;
    
    // Find role ID by traversing roles and matching with current user's role
    const roleSelects = document.getElementById('editRoleId');
    const userRow = document.querySelector(`[data-user-email="${user.email}"]`);
    const roleText = userRow?.querySelector('td:nth-child(4)')?.textContent?.trim();
    
    for (let option of roleSelects.options) {
        if (option.textContent.trim() === roleText) {
            roleSelects.value = option.value;
            break;
        }
    }

    document.getElementById('editModal').showModal();
}

function deleteUser(userId) {
    document.getElementById('deleteUserId').value = userId;
    document.getElementById('deleteModal').showModal();
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#usersTableBody tr');
    
    rows.forEach(row => {
        const name = row.dataset.userName || '';
        const email = row.dataset.userEmail || '';
        const matches = name.includes(searchTerm) || email.includes(searchTerm);
        row.style.display = matches ? '' : 'none';
    });
});
</script>
