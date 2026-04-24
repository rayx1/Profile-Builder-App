<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_login();
update_session_user();

$pageTitle = 'User Dashboard';
$userId = (int) current_user()['id'];
$profile = get_user_profile($userId);
$completion = profile_completion($userId);
$stats = [
    'Education' => count(get_records('education', $userId)),
    'Skills' => count(get_records('skills', $userId)),
    'Projects' => count(get_records('projects', $userId)),
    'Certificates' => count(get_records('certificates', $userId)),
];

require_once __DIR__ . '/../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Welcome, <?= e(current_user()['name']); ?></h1>
        <p class="text-muted mb-0">Manage your digital profile, portfolio, and printable resume from one dashboard.</p>
    </div>
    <?php if ($profile && $profile['username']): ?>
        <a class="btn btn-outline-primary" href="<?= e(public_profile_link($profile['username'])); ?>" target="_blank">Public Link</a>
    <?php endif; ?>
</div>

<div class="card dashboard-card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h2 class="h5 mb-0">Profile Completion</h2>
            <span class="fw-semibold"><?= $completion; ?>%</span>
        </div>
        <div class="progress" style="height: 12px;">
            <div class="progress-bar" role="progressbar" style="width: <?= $completion; ?>%;"></div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <?php foreach ($stats as $label => $value): ?>
        <div class="col-md-6 col-lg-3">
            <div class="card dashboard-card h-100">
                <div class="card-body">
                    <h3 class="h6 text-muted"><?= e($label); ?></h3>
                    <p class="display-6 mb-0"><?= $value; ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card dashboard-card">
            <div class="card-body">
                <h2 class="h5">Quick Actions</h2>
                <div class="row g-3 mt-1">
                    <div class="col-md-6"><a class="btn btn-primary w-100" href="/profile-builder-website-application/user/profile.php">Edit Profile</a></div>
                    <div class="col-md-6"><a class="btn btn-outline-primary w-100" href="/profile-builder-website-application/user/education.php">Manage Education</a></div>
                    <div class="col-md-6"><a class="btn btn-outline-primary w-100" href="/profile-builder-website-application/user/skills.php">Manage Skills</a></div>
                    <div class="col-md-6"><a class="btn btn-outline-primary w-100" href="/profile-builder-website-application/user/projects.php">Manage Projects</a></div>
                    <div class="col-md-6"><a class="btn btn-outline-primary w-100" href="/profile-builder-website-application/user/certificates.php">Manage Certificates</a></div>
                    <div class="col-md-6"><a class="btn btn-outline-primary w-100" href="/profile-builder-website-application/user/contact.php">Contact Links</a></div>
                    <div class="col-md-6"><a class="btn btn-outline-dark w-100" href="/profile-builder-website-application/user/templates.php">Choose Template</a></div>
                    <div class="col-md-6"><a class="btn btn-outline-dark w-100" href="/profile-builder-website-application/user/preview.php">Preview Profile</a></div>
                    <div class="col-md-6"><a class="btn btn-outline-dark w-100" href="/profile-builder-website-application/user/resume.php" target="_blank">Printable Resume</a></div>
                    <div class="col-md-6"><a class="btn btn-outline-secondary w-100" href="/profile-builder-website-application/user/settings.php">Account Settings</a></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card dashboard-card">
            <div class="card-body">
                <h2 class="h5">Publishing Status</h2>
                <?php if ($profile): ?>
                    <p class="mb-2">Username: <strong><?= e($profile['username']); ?></strong></p>
                    <p class="mb-2">Template: <strong><?= e(get_templates()[$profile['template_name']] ?? 'Classic Resume'); ?></strong></p>
                    <p class="mb-0">Visibility:
                        <span class="badge bg-<?= e(profile_visibility_badge($profile['privacy'])); ?>">
                            <?= e(ucfirst($profile['privacy'])); ?>
                        </span>
                    </p>
                <?php else: ?>
                    <p class="mb-0 text-muted">Create your profile first to publish a public link.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
