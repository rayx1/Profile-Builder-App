<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_admin();

$stmt = db()->query('SELECT * FROM contact_messages ORDER BY created_at DESC');
$messages = $stmt->fetchAll();

$pageTitle = 'Contact Messages';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="card content-card">
    <div class="card-body p-4">
        <h1 class="h3 mb-3">Contact Messages</h1>
        <?php foreach ($messages as $message): ?>
            <div class="border rounded p-3 mb-3">
                <div class="d-flex justify-content-between flex-wrap gap-2">
                    <div>
                        <h2 class="h6 mb-1"><?= e($message['subject']); ?></h2>
                        <div class="small text-muted">From <?= e($message['name']); ?> (<?= e($message['email']); ?>)</div>
                    </div>
                    <div class="small text-muted"><?= e(date('d M Y h:i A', strtotime($message['created_at']))); ?></div>
                </div>
                <p class="mb-0 mt-2"><?= nl2br(e($message['message'])); ?></p>
            </div>
        <?php endforeach; ?>
        <?php if (!$messages): ?><div class="text-muted">No contact messages received yet.</div><?php endif; ?>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
