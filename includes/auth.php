<?php
declare(strict_types=1);

require_once __DIR__ . '/functions.php';

function require_login(): void
{
    if (!is_logged_in()) {
        set_flash('danger', 'Please login to continue.');
        redirect('/profile-builder-website-application/auth/login.php');
    }

    if (current_user()['status'] !== 'active') {
        session_destroy();
        session_start();
        set_flash('danger', 'Your account is inactive. Contact the administrator.');
        redirect('/profile-builder-website-application/auth/login.php');
    }
}

function require_guest(): void
{
    if (is_logged_in()) {
        redirect('/profile-builder-website-application/user/dashboard.php');
    }
}

function require_admin(): void
{
    require_login();

    if (!is_admin()) {
        set_flash('danger', 'You are not authorized to access the admin area.');
        redirect('/profile-builder-website-application/user/dashboard.php');
    }
}
