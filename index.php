<?php
declare(strict_types=1);

$pageTitle = 'Home - Profile Builder';
require_once __DIR__ . '/includes/header.php';

$stmt = db()->query("SELECT profiles.full_name, profiles.headline, profiles.username, profiles.template_name, users.name
    FROM profiles
    INNER JOIN users ON users.id = profiles.user_id
    WHERE profiles.privacy = 'public'
    ORDER BY profiles.updated_at DESC
    LIMIT 6");
$publicProfiles = $stmt->fetchAll();
?>
<section class="hero-section mb-4">
    <div class="row align-items-center g-4">
        <div class="col-lg-7">
            <span class="badge bg-warning text-dark mb-3">Portfolio + Resume Builder</span>
            <h1 class="display-5 fw-bold">Create a professional profile website without writing code.</h1>
            <p class="lead">Students, freelancers, professionals, and job seekers can build a polished public profile, select a template, and print a resume in minutes.</p>
            <div class="d-flex flex-wrap gap-2">
                <a href="/profile-builder-website-application/auth/register.php" class="btn btn-warning btn-lg">Get Started</a>
                <a href="/profile-builder-website-application/about.php" class="btn btn-outline-light btn-lg">Learn More</a>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="bg-white text-dark rounded-4 p-4 shadow">
                <h5>What you can build</h5>
                <ul class="mb-0">
                    <li>Digital resume website</li>
                    <li>Portfolio and project showcase</li>
                    <li>Printable profile page</li>
                    <li>Shareable public username link</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card dashboard-card h-100 card-hover">
            <div class="card-body">
                <h5>Simple Workflow</h5>
                <p class="mb-0">Register, add your profile details, choose a template, and publish your personal profile page.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card dashboard-card h-100 card-hover">
            <div class="card-body">
                <h5>Three Ready Templates</h5>
                <p class="mb-0">Switch between Classic Resume, Modern Portfolio, and Minimal Professional layouts anytime.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card dashboard-card h-100 card-hover">
            <div class="card-body">
                <h5>Printable Resume</h5>
                <p class="mb-0">Generate a clean resume page that users can print directly from the browser.</p>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">Recent Public Profiles</h2>
        <a href="/profile-builder-website-application/auth/login.php" class="btn btn-sm btn-outline-primary">Login to Build Yours</a>
    </div>
    <div class="row g-4">
        <?php foreach ($publicProfiles as $profile): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card dashboard-card h-100 card-hover">
                    <div class="card-body">
                        <span class="badge bg-light text-dark mb-2"><?= e(get_templates()[$profile['template_name']] ?? 'Classic Resume'); ?></span>
                        <h5><?= e($profile['full_name']); ?></h5>
                        <p class="text-muted"><?= e($profile['headline']); ?></p>
                        <p class="small text-muted mb-3">Managed by <?= e($profile['name']); ?></p>
                        <a class="btn btn-outline-primary btn-sm" href="<?= e(public_profile_link($profile['username'])); ?>">View Public Profile</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (!$publicProfiles): ?>
            <div class="col-12"><div class="alert alert-info mb-0">No public profiles are published yet.</div></div>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
