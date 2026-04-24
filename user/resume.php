<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_login();

$bundle = get_profile_bundle((int) current_user()['id']);
$profile = $bundle['profile'];
$pageTitle = 'Printable Resume';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <h1 class="h3 mb-0">Printable Resume</h1>
    <button type="button" class="btn btn-primary" onclick="window.print()">Print Resume</button>
</div>
<?php if (!$profile): ?>
    <div class="alert alert-warning">Please complete your profile before printing your resume.</div>
<?php else: ?>
    <?php include __DIR__ . '/../public/_profile_layout.php'; ?>
<?php endif; ?>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
