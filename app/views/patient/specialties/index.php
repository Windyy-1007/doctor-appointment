<?php $this->title = 'Browse Specialties'; ?>

<section class="patient-section">
    <header class="patient-section__header">
        <h2>Browse Specialties</h2>
        <p>Find the right doctor by choosing a specialty and exploring approved offices.</p>
    </header>

    <?php if (empty($specialties)): ?>
        <p>No specialties are available at this time.</p>
    <?php else: ?>
        <div class="specialty-grid">
            <?php foreach ($specialties as $specialty): ?>
                <article class="specialty-card">
                    <h3><?= htmlspecialchars($specialty['name']) ?></h3>
                    <p><?= htmlspecialchars($specialty['description'] ?? 'No description yet.') ?></p>
                    <a class="btn" href="<?= BASE_URL ?>/patient/offices/specialty/<?= $specialty['id'] ?>">View offices</a>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
