<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_login();

$userId = (int) current_user()['id'];
$editItem = null;

if (isset($_GET['delete'])) {
    delete_record('education', $userId, (int) $_GET['delete']);
    set_flash('success', 'Education record deleted successfully.');
    redirect('/profile-builder-website-application/user/education.php');
}

if (isset($_GET['edit'])) {
    $editItem = find_record('education', $userId, (int) $_GET['edit']);
}

if (is_post()) {
    $errors = validate_required([
        'degree' => 'Degree',
        'institution' => 'Institution',
        'start_year' => 'Start year',
    ]);

    if (!$errors) {
        if (!empty($_POST['id'])) {
            $stmt = db()->prepare('UPDATE education SET degree = ?, institution = ?, start_year = ?, end_year = ?, description = ? WHERE id = ? AND user_id = ?');
            $stmt->execute([
                trim($_POST['degree']),
                trim($_POST['institution']),
                trim($_POST['start_year']),
                trim($_POST['end_year'] ?? ''),
                trim($_POST['description'] ?? ''),
                (int) $_POST['id'],
                $userId,
            ]);
            set_flash('success', 'Education updated successfully.');
        } else {
            $stmt = db()->prepare('INSERT INTO education (user_id, degree, institution, start_year, end_year, description) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([
                $userId,
                trim($_POST['degree']),
                trim($_POST['institution']),
                trim($_POST['start_year']),
                trim($_POST['end_year'] ?? ''),
                trim($_POST['description'] ?? ''),
            ]);
            set_flash('success', 'Education added successfully.');
        }
        redirect('/profile-builder-website-application/user/education.php');
    }

    set_flash('danger', implode(' ', $errors));
    redirect('/profile-builder-website-application/user/education.php');
}

$records = get_records('education', $userId, 'start_year DESC');
$pageTitle = 'Manage Education';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card form-card">
            <div class="card-body p-4">
                <h1 class="h4"><?= $editItem ? 'Edit Education' : 'Add Education'; ?></h1>
                <form method="post">
                    <input type="hidden" name="id" value="<?= e($editItem['id'] ?? ''); ?>">
                    <div class="mb-3"><label class="form-label">Degree</label><input type="text" name="degree" class="form-control" required value="<?= e($editItem['degree'] ?? ''); ?>"></div>
                    <div class="mb-3"><label class="form-label">Institution</label><input type="text" name="institution" class="form-control" required value="<?= e($editItem['institution'] ?? ''); ?>"></div>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Start Year</label><input type="text" name="start_year" class="form-control" required value="<?= e($editItem['start_year'] ?? ''); ?>"></div>
                        <div class="col-md-6"><label class="form-label">End Year</label><input type="text" name="end_year" class="form-control" value="<?= e($editItem['end_year'] ?? ''); ?>"></div>
                    </div>
                    <div class="mt-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4"><?= e($editItem['description'] ?? ''); ?></textarea></div>
                    <button class="btn btn-primary mt-3" type="submit"><?= $editItem ? 'Update' : 'Add'; ?> Education</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card content-card">
            <div class="card-body p-4">
                <h2 class="h4">Saved Education Records</h2>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead><tr><th>Degree</th><th>Institution</th><th>Years</th><th class="text-end">Action</th></tr></thead>
                        <tbody>
                        <?php foreach ($records as $record): ?>
                            <tr>
                                <td><?= e($record['degree']); ?></td>
                                <td><?= e($record['institution']); ?></td>
                                <td><?= e($record['start_year']); ?> - <?= e($record['end_year']); ?></td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="?edit=<?= (int) $record['id']; ?>">Edit</a>
                                    <a class="btn btn-sm btn-outline-danger" data-confirm="Delete this education record?" href="?delete=<?= (int) $record['id']; ?>">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (!$records): ?><tr><td colspan="4" class="text-center text-muted">No education records added yet.</td></tr><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
