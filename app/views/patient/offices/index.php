<?php $this->title = $mode === 'search' ? 'Search Offices' : 'Offices'; ?>

<section class="patient-section patient-offices">
    <header class="patient-section__header">
        <h2><?= $mode === 'search' ? 'Search offices' : 'Available offices' ?></h2>
        <p>Use the search box to find an office by name or address.</p>
        <form class="search-form" action="<?= BASE_URL ?>/patient/offices/search" method="get">
            <input type="text" name="q" placeholder="Search offices" value="<?= htmlspecialchars($searchTerm) ?>">
            <button type="submit">Search</button>
        </form>
    </header>

    <?php if ($mode === 'search' && $searchTerm !== ''): ?>
        <p class="search-hint">Search results for “<?= htmlspecialchars($searchTerm) ?>”.</p>
    <?php elseif ($mode === 'specialty' && $specialty !== null): ?>
        <p class="search-hint">Offices offering <?= htmlspecialchars($specialty['name']) ?>.</p>
    <?php endif; ?>

    <?php if (empty($offices)): ?>
        <p>No offices match your criteria.</p>
    <?php else: ?>
        <div class="office-grid">
            <?php foreach ($offices as $office): ?>
                <article class="office-card">
                    <h3><?= htmlspecialchars($office['office_name']) ?></h3>
                    <p><?= htmlspecialchars($office['address']) ?></p>
                    <?php if (!empty($office['phone'])): ?>
                        <p>Phone: <?= htmlspecialchars($office['phone']) ?></p>
                    <?php endif; ?>
                    <p><?= htmlspecialchars($office['description'] ?? 'No description available yet.') ?></p>
                    <a class="btn" href="<?= BASE_URL ?>/patient/offices/show/<?= $office['id'] ?>">View profile</a>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
