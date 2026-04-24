<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

function db(): PDO
{
    global $pdo;
    return $pdo;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header("Location: {$path}");
    exit;
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function old(string $key, string $default = ''): string
{
    return $_POST[$key] ?? $default;
}

function is_post(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool
{
    return current_user() !== null;
}

function is_admin(): bool
{
    return is_logged_in() && current_user()['role'] === 'admin';
}

function update_session_user(): void
{
    if (!is_logged_in()) {
        return;
    }

    $stmt = db()->prepare('SELECT id, name, email, role, status, created_at FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([current_user()['id']]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user'] = $user;
    }
}

function validate_required(array $fields): array
{
    $errors = [];

    foreach ($fields as $name => $label) {
        if (trim((string) ($_POST[$name] ?? '')) === '') {
            $errors[] = "{$label} is required.";
        }
    }

    return $errors;
}

function get_user_profile(int $userId): ?array
{
    $stmt = db()->prepare('SELECT * FROM profiles WHERE user_id = ? LIMIT 1');
    $stmt->execute([$userId]);
    return $stmt->fetch() ?: null;
}

function get_user_contact(int $userId): ?array
{
    $stmt = db()->prepare('SELECT * FROM contacts WHERE user_id = ? LIMIT 1');
    $stmt->execute([$userId]);
    return $stmt->fetch() ?: null;
}

function get_records(string $table, int $userId, string $orderBy = 'id DESC'): array
{
    $allowed = ['education', 'skills', 'projects', 'certificates'];
    if (!in_array($table, $allowed, true)) {
        return [];
    }

    $stmt = db()->prepare("SELECT * FROM {$table} WHERE user_id = ? ORDER BY {$orderBy}");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

function find_record(string $table, int $userId, int $id): ?array
{
    $allowed = ['education', 'skills', 'projects', 'certificates'];
    if (!in_array($table, $allowed, true)) {
        return null;
    }

    $stmt = db()->prepare("SELECT * FROM {$table} WHERE id = ? AND user_id = ? LIMIT 1");
    $stmt->execute([$id, $userId]);
    return $stmt->fetch() ?: null;
}

function delete_record(string $table, int $userId, int $id): bool
{
    $allowed = ['education', 'skills', 'projects', 'certificates'];
    if (!in_array($table, $allowed, true)) {
        return false;
    }

    $stmt = db()->prepare("DELETE FROM {$table} WHERE id = ? AND user_id = ?");
    return $stmt->execute([$id, $userId]);
}

function profile_completion(int $userId): int
{
    $checks = 0;
    $completed = 0;

    $profile = get_user_profile($userId);
    $contact = get_user_contact($userId);
    $education = get_records('education', $userId);
    $skills = get_records('skills', $userId);
    $projects = get_records('projects', $userId);
    $certificates = get_records('certificates', $userId);

    $checks++;
    if ($profile && $profile['full_name'] && $profile['headline']) {
        $completed++;
    }

    $checks++;
    if (!empty($education)) {
        $completed++;
    }

    $checks++;
    if (!empty($skills)) {
        $completed++;
    }

    $checks++;
    if (!empty($projects)) {
        $completed++;
    }

    $checks++;
    if (!empty($certificates)) {
        $completed++;
    }

    $checks++;
    if ($contact && ($contact['email'] || $contact['phone'] || $contact['website'])) {
        $completed++;
    }

    $checks++;
    if ($profile && $profile['template_name']) {
        $completed++;
    }

    return (int) round(($completed / max($checks, 1)) * 100);
}

function upload_profile_photo(array $file): array
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return ['success' => true, 'filename' => null];
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Profile photo upload failed.'];
    }

    if (($file['size'] ?? 0) > 2 * 1024 * 1024) {
        return ['success' => false, 'message' => 'Image size must be below 2MB.'];
    }

    $allowedMime = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!isset($allowedMime[$mime])) {
        return ['success' => false, 'message' => 'Only JPG, PNG, GIF, and WEBP images are allowed.'];
    }

    $filename = uniqid('profile_', true) . '.' . $allowedMime[$mime];
    $destination = __DIR__ . '/../uploads/profiles/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => false, 'message' => 'Failed to save uploaded image.'];
    }

    return ['success' => true, 'filename' => $filename];
}

function profile_photo_url(?string $filename): string
{
    if ($filename && file_exists(__DIR__ . '/../uploads/profiles/' . $filename)) {
        return '/profile-builder-website-application/uploads/profiles/' . rawurlencode($filename);
    }

    return '/profile-builder-website-application/assets/images/default-avatar.png';
}

function get_templates(): array
{
    return [
        'classic' => 'Classic Resume',
        'modern' => 'Modern Portfolio',
        'minimal' => 'Minimal Professional',
    ];
}

function get_profile_bundle(int $userId): array
{
    return [
        'profile' => get_user_profile($userId),
        'education' => get_records('education', $userId, 'start_year DESC'),
        'skills' => get_records('skills', $userId, 'skill_name ASC'),
        'projects' => get_records('projects', $userId, 'created_at DESC'),
        'certificates' => get_records('certificates', $userId, 'issue_date DESC'),
        'contact' => get_user_contact($userId),
    ];
}

function render_template_class(?string $template): string
{
    return 'template-' . ($template ?: 'classic');
}

function public_profile_link(string $username): string
{
    return '/profile-builder-website-application/public/view.php?u=' . urlencode($username);
}

function profile_visibility_badge(string $privacy): string
{
    return $privacy === 'public' ? 'success' : 'secondary';
}

function dashboard_stat(string $query, array $params = []): int
{
    $stmt = db()->prepare($query);
    $stmt->execute($params);
    return (int) $stmt->fetchColumn();
}
