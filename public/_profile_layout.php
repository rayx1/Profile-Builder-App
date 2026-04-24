<?php
$templateClass = render_template_class($profile['template_name'] ?? 'classic');
$contact = $bundle['contact'];
?>
<div class="public-profile-wrapper <?= e($templateClass); ?>">
    <div class="profile-header rounded-4 p-4 mb-4">
        <div class="row align-items-center g-4">
            <div class="col-md-auto">
                <img src="<?= e(profile_photo_url($profile['profile_photo'] ?? null)); ?>" alt="Profile photo" class="profile-avatar">
            </div>
            <div class="col">
                <h2 class="mb-1"><?= e($profile['full_name'] ?? ''); ?></h2>
                <p class="lead mb-2"><?= e($profile['headline'] ?? ''); ?></p>
                <p class="mb-0"><?= nl2br(e($profile['bio'] ?? '')); ?></p>
            </div>
        </div>
    </div>

    <div class="profile-section">
        <h3 class="h5">Education</h3>
        <?php foreach ($bundle['education'] as $item): ?>
            <div class="mb-3">
                <strong><?= e($item['degree']); ?></strong>
                <div><?= e($item['institution']); ?> (<?= e($item['start_year']); ?> - <?= e($item['end_year']); ?>)</div>
                <div class="text-muted"><?= e($item['description']); ?></div>
            </div>
        <?php endforeach; ?>
        <?php if (!$bundle['education']): ?><p class="text-muted mb-0">No education details added.</p><?php endif; ?>
    </div>

    <div class="profile-section">
        <h3 class="h5">Skills</h3>
        <?php foreach ($bundle['skills'] as $item): ?>
            <span class="profile-chip"><?= e($item['skill_name']); ?> - <?= e($item['skill_level']); ?></span>
        <?php endforeach; ?>
        <?php if (!$bundle['skills']): ?><p class="text-muted mb-0">No skills added.</p><?php endif; ?>
    </div>

    <div class="profile-section">
        <h3 class="h5">Projects</h3>
        <?php foreach ($bundle['projects'] as $item): ?>
            <div class="mb-3">
                <strong><?= e($item['title']); ?></strong>
                <p class="mb-1"><?= e($item['description']); ?></p>
                <div class="small text-muted"><?= e($item['technologies']); ?></div>
                <div class="small">
                    <?php if ($item['project_link']): ?><a href="<?= e($item['project_link']); ?>" target="_blank">Project Link</a><?php endif; ?>
                    <?php if ($item['project_link'] && $item['github_link']): ?> | <?php endif; ?>
                    <?php if ($item['github_link']): ?><a href="<?= e($item['github_link']); ?>" target="_blank">GitHub</a><?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (!$bundle['projects']): ?><p class="text-muted mb-0">No projects added.</p><?php endif; ?>
    </div>

    <div class="profile-section">
        <h3 class="h5">Certificates</h3>
        <?php foreach ($bundle['certificates'] as $item): ?>
            <div class="mb-3">
                <strong><?= e($item['title']); ?></strong>
                <div><?= e($item['organization']); ?><?= $item['issue_date'] ? ' | ' . e($item['issue_date']) : ''; ?></div>
                <?php if ($item['certificate_link']): ?><a href="<?= e($item['certificate_link']); ?>" target="_blank">View Certificate</a><?php endif; ?>
            </div>
        <?php endforeach; ?>
        <?php if (!$bundle['certificates']): ?><p class="text-muted mb-0">No certificates added.</p><?php endif; ?>
    </div>

    <div class="profile-section">
        <h3 class="h5">Contact & Social Links</h3>
        <?php if ($contact): ?>
            <div class="row g-2">
                <?php foreach (['email' => 'Email', 'phone' => 'Phone', 'address' => 'Address', 'website' => 'Website', 'linkedin' => 'LinkedIn', 'github' => 'GitHub', 'twitter' => 'Twitter', 'instagram' => 'Instagram'] as $field => $label): ?>
                    <?php if (!empty($contact[$field])): ?>
                        <div class="col-md-6"><strong><?= e($label); ?>:</strong> <?= e($contact[$field]); ?></div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted mb-0">No contact details added.</p>
        <?php endif; ?>
    </div>
</div>
