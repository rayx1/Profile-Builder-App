<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_login();

$userId = (int) current_user()['id'];

if (is_post()) {
    $errors = validate_required([
        'current_password' => 'Current password',
        'new_password' => 'New password',
        'confirm_password' => 'Confirm password',
    ]);

    if (($_POST['new_password'] ?? '') !== ($_POST['confirm_password'] ?? '')) {
        $errors[] = 'New password and confirm password must match.';
    }

    if (strlen($_POST['new_password'] ?? '') < 8) {
        $errors[] = 'New password must be at least 8 characters.';
    }

    $stmt = db()->prepare('SELECT password FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([$userId]);
    $hash = (string) $stmt->fetchColumn();

    if (!$hash || !password_verify($_POST['current_password'] ?? '', $hash)) {
        $errors[] = 'Current password is incorrect.';
    }

    if (!$errors) {
        $update = db()->prepare('UPDATE users SET password = ? WHERE id = ?');
        $update->execute([password_hash($_POST['new_password'], PASSWORD_DEFAULT), $userId]);
        set_flash('success', 'Password changed successfully.');
        redirect('/profile-builder-website-application/user/settings.php');
    }

    set_flash('danger', implode(' ', $errors));
    redirect('/profile-builder-website-application/user/settings.php');
}

$pageTitle = 'Account Settings';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card form-card">
            <div class="card-body p-4">
                <h1 class="h3">Change Password</h1>
                <form method="post">
                    <div class="mb-3"><label class="form-label">Current Password</label><input type="password" name="current_password" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">New Password</label><input type="password" name="new_password" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Confirm New Password</label><input type="password" name="confirm_password" class="form-control" required></div>
                    <button class="btn btn-primary" type="submit">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
