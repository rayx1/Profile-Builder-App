<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_login();

$userId = (int) current_user()['id'];
$editItem = null;

if (isset($_GET['delete'])) {
    delete_record('certificates', $userId, (int) $_GET['delete']);
    set_flash('success', 'Certificate deleted successfully.');
    redirect('/profile-builder-website-application/user/certificates.php');
}

if (isset($_GET['edit'])) {
    $editItem = find_record('certificates', $userId, (int) $_GET['edit']);
}

if (is_post()) {
    $errors = validate_required([
        'title' => 'Certificate title',
        'organization' => 'Organization',
    ]);

    if (!$errors) {
        if (!empty($_POST['id'])) {
            $stmt = db()->prepare('UPDATE certificates SET title = ?, organization = ?, issue_date = ?, certificate_link = ? WHERE id = ? AND user_id = ?');
            $stmt->execute([
                trim($_POST['title']),
                trim($_POST['organization']),
                $_POST['issue_date'] ?: null,
                trim($_POST['certificate_link'] ?? ''),
                (int) $_POST['id'],
                $userId,
            ]);
            set_flash('success', 'Certificate updated successfully.');
        } else {
            $stmt = db()->prepare('INSERT INTO certificates (user_id, title, organization, issue_date, certificate_link) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([
                $userId,
                trim($_POST['title']),
                trim($_POST['organization']),
                $_POST['issue_date'] ?: null,
                trim($_POST['certificate_link'] ?? ''),
            ]);
            set_flash('success', 'Certificate added successfully.');
        }
        redirect('/profile-builder-website-application/user/certificates.php');
    }

    set_flash('danger', implode(' ', $errors));
    redirect('/profile-builder-website-application/user/certificates.php');
}

$records = get_records('certificates', $userId, 'issue_date DESC');
$pageTitle = 'Manage Certificates';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card form-card">
            <div class="card-body p-4">
                <h1 class="h4"><?= $editItem ? 'Edit Certificate' : 'Add Certificate'; ?></h1>
                <form method="post">
                    <input type="hidden" name="id" value="<?= e($editItem['id'] ?? ''); ?>">
                    <div class="mb-3"><label class="form-label">Title</label><input type="text" name="title" class="form-control" required value="<?= e($editItem['title'] ?? ''); ?>"></div>
                    <div class="mb-3"><label class="form-label">Organization</label><input type="text" name="organization" class="form-control" required value="<?= e($editItem['organization'] ?? ''); ?>"></div>
                    <div class="mb-3"><label class="form-label">Issue Date</label><input type="date" name="issue_date" class="form-control" value="<?= e($editItem['issue_date'] ?? ''); ?>"></div>
                    <div class="mb-3"><label class="form-label">Certificate Link</label><input type="url" name="certificate_link" class="form-control" value="<?= e($editItem['certificate_link'] ?? ''); ?>"></div>
                    <button class="btn btn-primary" type="submit"><?= $editItem ? 'Update' : 'Add'; ?> Certificate</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card content-card">
            <div class="card-body p-4">
                <h2 class="h4">Saved Certificates</h2>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead><tr><th>Title</th><th>Organization</th><th>Date</th><th class="text-end">Action</th></tr></thead>
                        <tbody>
                        <?php foreach ($records as $record): ?>
                            <tr>
                                <td><?= e($record['title']); ?></td>
                                <td><?= e($record['organization']); ?></td>
                                <td><?= e($record['issue_date']); ?></td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="?edit=<?= (int) $record['id']; ?>">Edit</a>
                                    <a class="btn btn-sm btn-outline-danger" data-confirm="Delete this certificate?" href="?delete=<?= (int) $record['id']; ?>">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (!$records): ?><tr><td colspan="4" class="text-center text-muted">No certificates added yet.</td></tr><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
