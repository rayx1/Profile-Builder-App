<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_admin();

$stmt = db()->query("SELECT profiles.*, users.email
    FROM profiles
    INNER JOIN users ON users.id = profiles.user_id
    ORDER BY profiles.updated_at DESC");
$profiles = $stmt->fetchAll();

$pageTitle = 'Manage Profiles';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="card content-card">
    <div class="card-body p-4">
        <h1 class="h3 mb-3">All Profiles</h1>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead><tr><th>Name</th><th>Username</th><th>Email</th><th>Template</th><th>Privacy</th><th>Updated</th><th>Public Link</th></tr></thead>
                <tbody>
                <?php foreach ($profiles as $profile): ?>
                    <tr>
                        <td><?= e($profile['full_name']); ?></td>
                        <td><?= e($profile['username']); ?></td>
                        <td><?= e($profile['email']); ?></td>
                        <td><?= e(get_templates()[$profile['template_name']] ?? $profile['template_name']); ?></td>
                        <td><span class="badge bg-<?= e(profile_visibility_badge($profile['privacy'])); ?>"><?= e($profile['privacy']); ?></span></td>
                        <td><?= e(date('d M Y', strtotime($profile['updated_at']))); ?></td>
                        <td>
                            <?php if ($profile['privacy'] === 'public'): ?>
                                <a href="<?= e(public_profile_link($profile['username'])); ?>" target="_blank">Open</a>
                            <?php else: ?>
                                <span class="text-muted">Hidden</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$profiles): ?><tr><td colspan="7" class="text-center text-muted">No profiles found.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
