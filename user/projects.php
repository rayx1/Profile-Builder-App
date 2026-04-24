<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_login();

$userId = (int) current_user()['id'];
$editItem = null;

if (isset($_GET['delete'])) {
    delete_record('projects', $userId, (int) $_GET['delete']);
    set_flash('success', 'Project deleted successfully.');
    redirect('/profile-builder-website-application/user/projects.php');
}

if (isset($_GET['edit'])) {
    $editItem = find_record('projects', $userId, (int) $_GET['edit']);
}

if (is_post()) {
    $errors = validate_required([
        'title' => 'Project title',
        'description' => 'Description',
    ]);

    if (!$errors) {
        if (!empty($_POST['id'])) {
            $stmt = db()->prepare('UPDATE projects SET title = ?, description = ?, project_link = ?, github_link = ?, technologies = ? WHERE id = ? AND user_id = ?');
            $stmt->execute([
                trim($_POST['title']),
                trim($_POST['description']),
                trim($_POST['project_link'] ?? ''),
                trim($_POST['github_link'] ?? ''),
                trim($_POST['technologies'] ?? ''),
                (int) $_POST['id'],
                $userId,
            ]);
            set_flash('success', 'Project updated successfully.');
        } else {
            $stmt = db()->prepare('INSERT INTO projects (user_id, title, description, project_link, github_link, technologies, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())');
            $stmt->execute([
                $userId,
                trim($_POST['title']),
                trim($_POST['description']),
                trim($_POST['project_link'] ?? ''),
                trim($_POST['github_link'] ?? ''),
                trim($_POST['technologies'] ?? ''),
            ]);
            set_flash('success', 'Project added successfully.');
        }
        redirect('/profile-builder-website-application/user/projects.php');
    }

    set_flash('danger', implode(' ', $errors));
    redirect('/profile-builder-website-application/user/projects.php');
}

$records = get_records('projects', $userId, 'created_at DESC');
$pageTitle = 'Manage Projects';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card form-card">
            <div class="card-body p-4">
                <h1 class="h4"><?= $editItem ? 'Edit Project' : 'Add Project'; ?></h1>
                <form method="post">
                    <input type="hidden" name="id" value="<?= e($editItem['id'] ?? ''); ?>">
                    <div class="mb-3"><label class="form-label">Project Title</label><input type="text" name="title" class="form-control" required value="<?= e($editItem['title'] ?? ''); ?>"></div>
                    <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4" required><?= e($editItem['description'] ?? ''); ?></textarea></div>
                    <div class="mb-3"><label class="form-label">Project Link</label><input type="url" name="project_link" class="form-control" value="<?= e($editItem['project_link'] ?? ''); ?>"></div>
                    <div class="mb-3"><label class="form-label">GitHub Link</label><input type="url" name="github_link" class="form-control" value="<?= e($editItem['github_link'] ?? ''); ?>"></div>
                    <div class="mb-3"><label class="form-label">Technologies</label><input type="text" name="technologies" class="form-control" value="<?= e($editItem['technologies'] ?? ''); ?>"></div>
                    <button class="btn btn-primary" type="submit"><?= $editItem ? 'Update' : 'Add'; ?> Project</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card content-card">
            <div class="card-body p-4">
                <h2 class="h4">Saved Projects</h2>
                <?php foreach ($records as $record): ?>
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <h3 class="h6 mb-1"><?= e($record['title']); ?></h3>
                                <p class="mb-2"><?= e($record['description']); ?></p>
                                <div class="small text-muted"><?= e($record['technologies']); ?></div>
                            </div>
                            <div class="text-end">
                                <a class="btn btn-sm btn-outline-primary mb-1" href="?edit=<?= (int) $record['id']; ?>">Edit</a>
                                <a class="btn btn-sm btn-outline-danger" data-confirm="Delete this project?" href="?delete=<?= (int) $record['id']; ?>">Delete</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (!$records): ?><div class="text-muted">No projects added yet.</div><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
