<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_login();

$userId = (int) current_user()['id'];
$editItem = null;

if (isset($_GET['delete'])) {
    delete_record('skills', $userId, (int) $_GET['delete']);
    set_flash('success', 'Skill deleted successfully.');
    redirect('/profile-builder-website-application/user/skills.php');
}

if (isset($_GET['edit'])) {
    $editItem = find_record('skills', $userId, (int) $_GET['edit']);
}

if (is_post()) {
    $errors = validate_required([
        'skill_name' => 'Skill name',
        'skill_level' => 'Skill level',
    ]);

    if (!$errors) {
        if (!empty($_POST['id'])) {
            $stmt = db()->prepare('UPDATE skills SET skill_name = ?, skill_level = ? WHERE id = ? AND user_id = ?');
            $stmt->execute([trim($_POST['skill_name']), $_POST['skill_level'], (int) $_POST['id'], $userId]);
            set_flash('success', 'Skill updated successfully.');
        } else {
            $stmt = db()->prepare('INSERT INTO skills (user_id, skill_name, skill_level) VALUES (?, ?, ?)');
            $stmt->execute([$userId, trim($_POST['skill_name']), $_POST['skill_level']]);
            set_flash('success', 'Skill added successfully.');
        }
        redirect('/profile-builder-website-application/user/skills.php');
    }

    set_flash('danger', implode(' ', $errors));
    redirect('/profile-builder-website-application/user/skills.php');
}

$records = get_records('skills', $userId, 'skill_name ASC');
$pageTitle = 'Manage Skills';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card form-card">
            <div class="card-body p-4">
                <h1 class="h4"><?= $editItem ? 'Edit Skill' : 'Add Skill'; ?></h1>
                <form method="post">
                    <input type="hidden" name="id" value="<?= e($editItem['id'] ?? ''); ?>">
                    <div class="mb-3"><label class="form-label">Skill</label><input type="text" name="skill_name" class="form-control" required value="<?= e($editItem['skill_name'] ?? ''); ?>"></div>
                    <div class="mb-3">
                        <label class="form-label">Level</label>
                        <select name="skill_level" class="form-select" required>
                            <?php foreach (['Beginner', 'Intermediate', 'Advanced', 'Expert'] as $level): ?>
                                <option value="<?= e($level); ?>" <?= ($editItem['skill_level'] ?? '') === $level ? 'selected' : ''; ?>><?= e($level); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button class="btn btn-primary" type="submit"><?= $editItem ? 'Update' : 'Add'; ?> Skill</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card content-card">
            <div class="card-body p-4">
                <h2 class="h4">Saved Skills</h2>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead><tr><th>Skill</th><th>Level</th><th class="text-end">Action</th></tr></thead>
                        <tbody>
                        <?php foreach ($records as $record): ?>
                            <tr>
                                <td><?= e($record['skill_name']); ?></td>
                                <td><span class="badge bg-info text-dark"><?= e($record['skill_level']); ?></span></td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="?edit=<?= (int) $record['id']; ?>">Edit</a>
                                    <a class="btn btn-sm btn-outline-danger" data-confirm="Delete this skill?" href="?delete=<?= (int) $record['id']; ?>">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (!$records): ?><tr><td colspan="3" class="text-center text-muted">No skills added yet.</td></tr><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
