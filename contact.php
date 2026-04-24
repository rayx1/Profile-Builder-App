<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

if (is_post()) {
    $errors = validate_required([
        'name' => 'Name',
        'email' => 'Email',
        'subject' => 'Subject',
        'message' => 'Message',
    ]);

    if (!filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please provide a valid email address.';
    }

    if (!$errors) {
        $stmt = db()->prepare('INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())');
        $stmt->execute([
            trim($_POST['name']),
            trim($_POST['email']),
            trim($_POST['subject']),
            trim($_POST['message']),
        ]);
        set_flash('success', 'Your message has been sent successfully.');
        redirect('/profile-builder-website-application/contact.php');
    }

    set_flash('danger', implode(' ', $errors));
    redirect('/profile-builder-website-application/contact.php');
}

$pageTitle = 'Contact - Profile Builder';
require_once __DIR__ . '/includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card form-card">
            <div class="card-body p-4">
                <h1 class="h3">Contact Us</h1>
                <p class="text-muted">Use this form to send a general message to the platform administrator.</p>
                <form method="post">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Message</label>
                            <textarea name="message" class="form-control" rows="5" required></textarea>
                        </div>
                    </div>
                    <button class="btn btn-primary mt-3" type="submit">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
