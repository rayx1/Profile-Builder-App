<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_login();

$userId = (int) current_user()['id'];
$profile = get_user_profile($userId);

if (!$profile) {
    set_flash('danger', 'Create your profile first before selecting a template.');
    redirect('/profile-builder-website-application/user/profile.php');
}

if (is_post()) {
    $template = $_POST['template_name'] ?? 'classic';
    if (!array_key_exists($template, get_templates())) {
        $template = 'classic';
    }

    $stmt = db()->prepare('UPDATE profiles SET template_name = ?, updated_at = NOW() WHERE user_id = ?');
    $stmt->execute([$template, $userId]);
    set_flash('success', 'Profile template updated successfully.');
    redirect('/profile-builder-website-application/user/templates.php');
}

$profile = get_user_profile($userId);
$pageTitle = 'Choose Template';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="card form-card">
    <div class="card-body p-4">
        <h1 class="h3">Select a Profile Template</h1>
        <form method="post" class="template-selector">
            <div class="row g-4 mt-1">
                <?php foreach (get_templates() as $key => $label): ?>
                    <div class="col-md-4">
                        <label class="card template-option h-100 <?= $profile['template_name'] === $key ? 'active' : ''; ?>">
                            <div class="card-body">
                                <input type="radio" class="form-check-input mb-3" name="template_name" value="<?= e($key); ?>" <?= $profile['template_name'] === $key ? 'checked' : ''; ?>>
                                <h2 class="h5"><?= e($label); ?></h2>
                                <p class="mb-0 text-muted">
                                    <?= $key === 'classic' ? 'Traditional resume style for students and professionals.' : ''; ?>
                                    <?= $key === 'modern' ? 'Bold portfolio layout for creatives, developers, and freelancers.' : ''; ?>
                                    <?= $key === 'minimal' ? 'Clean and elegant profile page with a lightweight layout.' : ''; ?>
                                </p>
                            </div>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="btn btn-primary mt-4" type="submit">Save Selected Template</button>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
