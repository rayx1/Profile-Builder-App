<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_login();

$bundle = get_profile_bundle((int) current_user()['id']);
$profile = $bundle['profile'];

$pageTitle = 'Preview Profile';
require_once __DIR__ . '/../includes/header.php';

if (!$profile): ?>
    <div class="alert alert-warning">Please create your profile first.</div>
<?php else: ?>
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <h1 class="h3 mb-0">Profile Preview</h1>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-primary" href="/profile-builder-website-application/user/profile.php">Edit Profile</a>
            <a class="btn btn-primary" href="/profile-builder-website-application/user/resume.php" target="_blank">Open Resume</a>
        </div>
    </div>
    <?php include __DIR__ . '/../public/_profile_layout.php'; ?>
<?php endif; ?>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
