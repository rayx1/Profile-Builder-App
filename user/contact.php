<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_login();

$userId = (int) current_user()['id'];
$contact = get_user_contact($userId);

if (is_post()) {
    $email = trim($_POST['email'] ?? '');
    $errors = [];

    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Contact email must be valid.';
    }

    if (!$errors) {
        $data = [
            $email,
            trim($_POST['phone'] ?? ''),
            trim($_POST['address'] ?? ''),
            trim($_POST['website'] ?? ''),
            trim($_POST['linkedin'] ?? ''),
            trim($_POST['github'] ?? ''),
            trim($_POST['twitter'] ?? ''),
            trim($_POST['instagram'] ?? ''),
            $userId,
        ];

        if ($contact) {
            $stmt = db()->prepare('UPDATE contacts SET email = ?, phone = ?, address = ?, website = ?, linkedin = ?, github = ?, twitter = ?, instagram = ? WHERE user_id = ?');
            $stmt->execute($data);
        } else {
            $stmt = db()->prepare('INSERT INTO contacts (email, phone, address, website, linkedin, github, twitter, instagram, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute($data);
        }

        set_flash('success', 'Contact and social links saved successfully.');
        redirect('/profile-builder-website-application/user/contact.php');
    }

    set_flash('danger', implode(' ', $errors));
    redirect('/profile-builder-website-application/user/contact.php');
}

$contact = get_user_contact($userId);
$pageTitle = 'Contact & Social Links';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card form-card">
            <div class="card-body p-4">
                <h1 class="h3">Contact & Social Links</h1>
                <form method="post">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= e($contact['email'] ?? current_user()['email']); ?>"></div>
                        <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="<?= e($contact['phone'] ?? ''); ?>"></div>
                        <div class="col-12"><label class="form-label">Address</label><input type="text" name="address" class="form-control" value="<?= e($contact['address'] ?? ''); ?>"></div>
                        <div class="col-md-6"><label class="form-label">Website</label><input type="url" name="website" class="form-control" value="<?= e($contact['website'] ?? ''); ?>"></div>
                        <div class="col-md-6"><label class="form-label">LinkedIn</label><input type="url" name="linkedin" class="form-control" value="<?= e($contact['linkedin'] ?? ''); ?>"></div>
                        <div class="col-md-4"><label class="form-label">GitHub</label><input type="url" name="github" class="form-control" value="<?= e($contact['github'] ?? ''); ?>"></div>
                        <div class="col-md-4"><label class="form-label">Twitter</label><input type="url" name="twitter" class="form-control" value="<?= e($contact['twitter'] ?? ''); ?>"></div>
                        <div class="col-md-4"><label class="form-label">Instagram</label><input type="url" name="instagram" class="form-control" value="<?= e($contact['instagram'] ?? ''); ?>"></div>
                    </div>
                    <button class="btn btn-primary mt-3" type="submit">Save Contact Links</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
