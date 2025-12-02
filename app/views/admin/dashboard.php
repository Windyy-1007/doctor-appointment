<?php $user = Auth::user(); ?>

<section class="auth-box">
    <h2>Admin Dashboard</h2>
    <?php if ($user): ?>
        <p>Welcome back, <?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?>.</p>
        <p>Your role is <strong><?= htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8') ?></strong>.</p>
    <?php endif; ?>

    <p>This area is protected via <code>Auth::requireRole(['admin'])</code>.</p>
</section>
