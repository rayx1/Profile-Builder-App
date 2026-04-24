<?php
declare(strict_types=1);

require_once __DIR__ . '/functions.php';
$flash = get_flash();
$pageTitle = $pageTitle ?? 'Profile Builder Website Application';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/profile-builder-website-application/assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/profile-builder-website-application/index.php">Profile Builder</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="/profile-builder-website-application/index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="/profile-builder-website-application/about.php">About</a></li>
                <li class="nav-item"><a class="nav-link" href="/profile-builder-website-application/contact.php">Contact</a></li>
                <?php if (is_logged_in()): ?>
                    <li class="nav-item"><a class="nav-link" href="/profile-builder-website-application/user/dashboard.php">Dashboard</a></li>
                    <?php if (is_admin()): ?>
                        <li class="nav-item"><a class="nav-link" href="/profile-builder-website-application/admin/dashboard.php">Admin</a></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <?php if (is_logged_in()): ?>
                    <span class="text-white-50 small"><?= e(current_user()['name']); ?></span>
                    <a class="btn btn-outline-light btn-sm" href="/profile-builder-website-application/auth/logout.php">Logout</a>
                <?php else: ?>
                    <a class="btn btn-outline-light btn-sm" href="/profile-builder-website-application/auth/login.php">Login</a>
                    <a class="btn btn-warning btn-sm" href="/profile-builder-website-application/auth/register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<main class="py-4">
    <div class="container">
        <?php if ($flash): ?>
            <div class="alert alert-<?= e($flash['type']); ?> alert-dismissible fade show" role="alert">
                <?= e($flash['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
