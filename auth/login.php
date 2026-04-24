<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_guest();

if (is_post()) {
    $errors = validate_required([
        'email' => 'Email',
        'password' => 'Password',
    ]);

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$errors) {
        $stmt = db()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            $errors[] = 'Invalid email or password.';
        } elseif ($user['status'] !== 'active') {
            $errors[] = 'Your account is inactive.';
        } else {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'status' => $user['status'],
                'created_at' => $user['created_at'],
            ];

            if ($user['role'] === 'admin') {
                redirect('/profile-builder-website-application/admin/dashboard.php');
            }

            redirect('/profile-builder-website-application/user/dashboard.php');
        }
    }

    set_flash('danger', implode(' ', $errors));
    redirect('/profile-builder-website-application/auth/login.php');
}

$pageTitle = 'Login - Profile Builder';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="card form-card">
            <div class="card-body p-4">
                <h1 class="h3">Login</h1>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Login</button>
                </form>
                <div class="small text-muted mt-3">
                    Demo accounts are documented in the README after database import.
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
