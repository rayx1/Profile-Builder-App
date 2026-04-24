<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_admin();

$pageTitle = 'Admin Dashboard';
$stats = [
    'Total Users' => dashboard_stat('SELECT COUNT(*) FROM users WHERE role = ?', ['user']),
    'Total Profiles' => dashboard_stat('SELECT COUNT(*) FROM profiles'),
    'Public Profiles' => dashboard_stat("SELECT COUNT(*) FROM profiles WHERE privacy = 'public'"),
    'Private Profiles' => dashboard_stat("SELECT COUNT(*) FROM profiles WHERE privacy = 'private'"),
    'Contact Messages' => dashboard_stat('SELECT COUNT(*) FROM contact_messages'),
];

require_once __DIR__ . '/../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Admin Dashboard</h1>
        <p class="text-muted mb-0">Monitor users, published profiles, and incoming contact messages.</p>
    </div>
</div>
<div class="row g-4 mb-4">
    <?php foreach ($stats as $label => $value): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    <h2 class="h6 text-muted"><?= e($label); ?></h2>
                    <p class="display-6 mb-0"><?= $value; ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<div class="row g-4">
    <div class="col-md-4"><a class="btn btn-primary w-100 py-3" href="/profile-builder-website-application/admin/users.php">Manage Users</a></div>
    <div class="col-md-4"><a class="btn btn-outline-primary w-100 py-3" href="/profile-builder-website-application/admin/profiles.php">Manage Profiles</a></div>
    <div class="col-md-4"><a class="btn btn-outline-dark w-100 py-3" href="/profile-builder-website-application/admin/messages.php">View Messages</a></div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
