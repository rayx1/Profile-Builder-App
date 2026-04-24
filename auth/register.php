<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_guest();

if (is_post()) {
    $errors = validate_required([
        'name' => 'Name',
        'email' => 'Email',
        'password' => 'Password',
        'confirm_password' => 'Confirm password',
    ]);

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long.';
    }

    if ($password !== ($_POST['confirm_password'] ?? '')) {
        $errors[] = 'Password and confirm password do not match.';
    }

    $checkStmt = db()->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $checkStmt->execute([$email]);
    if ($checkStmt->fetch()) {
        $errors[] = 'This email is already registered.';
    }

    if (!$errors) {
        $stmt = db()->prepare('INSERT INTO users (name, email, password, role, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())');
        $stmt->execute([
            trim($_POST['name']),
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            'user',
            'active',
        ]);

        set_flash('success', 'Registration completed successfully. Please login.');
        redirect('/profile-builder-website-application/auth/login.php');
    }

    set_flash('danger', implode(' ', $errors));
    redirect('/profile-builder-website-application/auth/register.php');
}

$pageTitle = 'Register - Profile Builder';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card form-card">
            <div class="card-body p-4">
                <h1 class="h3">Create Account</h1>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Register</button>
                </form>
                <p class="mt-3 mb-0 text-center">Already registered? <a href="/profile-builder-website-application/auth/login.php">Login here</a></p>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
