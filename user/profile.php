<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_login();

$userId = (int) current_user()['id'];
$profile = get_user_profile($userId);

if (is_post()) {
    $errors = validate_required([
        'username' => 'Username',
        'full_name' => 'Full name',
        'headline' => 'Headline',
    ]);

    $username = strtolower(trim($_POST['username'] ?? ''));
    if (!preg_match('/^[a-z0-9_]{4,30}$/', $username)) {
        $errors[] = 'Username must be 4-30 characters and use only lowercase letters, numbers, and underscores.';
    }

    $check = db()->prepare('SELECT id FROM profiles WHERE username = ? AND user_id != ? LIMIT 1');
    $check->execute([$username, $userId]);
    if ($check->fetch()) {
        $errors[] = 'This username is already taken.';
    }

    $photoName = $profile['profile_photo'] ?? null;
    if (!empty($_FILES['profile_photo']['name'])) {
        $upload = upload_profile_photo($_FILES['profile_photo']);
        if (!$upload['success']) {
            $errors[] = $upload['message'];
        } else {
            $photoName = $upload['filename'];
        }
    }

    if (!$errors) {
        $data = [
            $username,
            trim($_POST['full_name']),
            trim($_POST['headline']),
            trim($_POST['bio'] ?? ''),
            $_POST['date_of_birth'] ?: null,
            $photoName,
            $_POST['template_name'] ?? ($profile['template_name'] ?? 'classic'),
            $_POST['privacy'] ?? 'public',
            $userId,
        ];

        if ($profile) {
            $stmt = db()->prepare('UPDATE profiles SET username = ?, full_name = ?, headline = ?, bio = ?, date_of_birth = ?, profile_photo = ?, template_name = ?, privacy = ?, updated_at = NOW() WHERE user_id = ?');
            $stmt->execute($data);
        } else {
            $stmt = db()->prepare('INSERT INTO profiles (username, full_name, headline, bio, date_of_birth, profile_photo, template_name, privacy, user_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())');
            $stmt->execute($data);
        }

        set_flash('success', 'Profile information saved successfully.');
        redirect('/profile-builder-website-application/user/profile.php');
    }

    set_flash('danger', implode(' ', $errors));
    redirect('/profile-builder-website-application/user/profile.php');
}

$profile = get_user_profile($userId);
$pageTitle = 'Manage Profile';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card form-card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="h3 mb-0">Personal Profile</h1>
                    <?php if ($profile && $profile['username']): ?>
                        <a class="btn btn-outline-primary btn-sm" href="<?= e(public_profile_link($profile['username'])); ?>" target="_blank">View Public Link</a>
                    <?php endif; ?>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Public Username</label>
                            <input type="text" name="username" class="form-control" required value="<?= e($profile['username'] ?? ''); ?>">
                            <div class="form-text">Used for `/public/view.php?u=username`.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control" required value="<?= e($profile['full_name'] ?? current_user()['name']); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Professional Headline</label>
                            <input type="text" name="headline" class="form-control" required value="<?= e($profile['headline'] ?? ''); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control" value="<?= e($profile['date_of_birth'] ?? ''); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Visibility</label>
                            <select name="privacy" class="form-select">
                                <option value="public" <?= ($profile['privacy'] ?? 'public') === 'public' ? 'selected' : ''; ?>>Public</option>
                                <option value="private" <?= ($profile['privacy'] ?? '') === 'private' ? 'selected' : ''; ?>>Private</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Profile Photo</label>
                            <input type="file" name="profile_photo" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Default Template</label>
                            <select name="template_name" class="form-select">
                                <?php foreach (get_templates() as $key => $label): ?>
                                    <option value="<?= e($key); ?>" <?= ($profile['template_name'] ?? 'classic') === $key ? 'selected' : ''; ?>><?= e($label); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Bio / Summary</label>
                            <textarea name="bio" class="form-control" rows="5"><?= e($profile['bio'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    <button class="btn btn-primary mt-3" type="submit">Save Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
