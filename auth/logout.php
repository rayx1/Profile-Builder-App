<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

session_unset();
session_destroy();
session_start();
set_flash('success', 'You have been logged out successfully.');
redirect('/profile-builder-website-application/auth/login.php');
