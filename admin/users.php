<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_admin();

if (isset($_GET['toggle'])) {
    $id = (int) $_GET['toggle'];
    $stmt = db()->prepare("UPDATE users SET status = CASE WHEN status = 'active' THEN 'inactive' ELSE 'active' END WHERE id = ? AND role = 'user'");
    $stmt->execute([$id]);
    set_flash('success', 'User status updated successfully.');
    redirect('/profile-builder-website-application/admin/users.php');
}

$stmt = db()->query("SELECT id, name, email, role, status, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();

$pageTitle = 'Manage Users';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="card content-card">
    <div class="card-body p-4">
        <h1 class="h3 mb-3">All Users</h1>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Created</th><th class="text-end">Action</th></tr></thead>
                <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= e($user['name']); ?></td>
                        <td><?= e($user['email']); ?></td>
                        <td><span class="badge bg-dark"><?= e($user['role']); ?></span></td>
                        <td><span class="badge bg-<?= $user['status'] === 'active' ? 'success' : 'secondary'; ?>"><?= e($user['status']); ?></span></td>
                        <td><?= e(date('d M Y', strtotime($user['created_at']))); ?></td>
                        <td class="text-end">
                            <?php if ($user['role'] === 'user'): ?>
                                <a class="btn btn-sm btn-outline-primary" data-confirm="Change this user status?" href="?toggle=<?= (int) $user['id']; ?>">
                                    <?= $user['status'] === 'active' ? 'Deactivate' : 'Activate'; ?>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
