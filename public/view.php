<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

$username = strtolower(trim($_GET['u'] ?? ''));
$stmt = db()->prepare("SELECT * FROM profiles WHERE username = ? AND privacy = 'public' LIMIT 1");
$stmt->execute([$username]);
$profile = $stmt->fetch();

$pageTitle = $profile ? ($profile['full_name'] . ' - Public Profile') : 'Profile Not Found';
require_once __DIR__ . '/../includes/header.php';

if (!$profile): ?>
    <div class="alert alert-warning">This public profile is not available. It may be private or the username may not exist.</div>
<?php else:
    $bundle = get_profile_bundle((int) $profile['user_id']);
    ?>
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <h1 class="h3 mb-0">Public Profile</h1>
        <a class="btn btn-outline-secondary" href="/profile-builder-website-application/index.php">Back to Home</a>
    </div>
    <?php include __DIR__ . '/_profile_layout.php'; ?>
<?php endif; ?>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
