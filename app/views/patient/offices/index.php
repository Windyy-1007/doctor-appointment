<?php $this->title = 'Offices | MediBook'; ?>

<section class="section-card card-hover">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <?php if ($mode === 'specialty' && $specialty !== null): ?>
                <p class="text-uppercase text-primary-dark mb-1">Specialty</p>
                <h2 class="h4 mb-0">Offices offering <?= htmlspecialchars($specialty['name']) ?></h2>
            <?php elseif ($mode === 'search' && $searchTerm !== ''): ?>
                <p class="text-uppercase text-primary-dark mb-1">Search</p>
                <h2 class="h4 mb-0">Results for “<?= htmlspecialchars($searchTerm) ?>”</h2>
            <?php else: ?>
                <p class="text-uppercase text-primary-dark mb-1">Offices</p>
                <h2 class="h4 mb-0">Approved partners</h2>
            <?php endif; ?>
        </div>
        <a class="text-decoration-none text-primary-dark" href="<?= BASE_URL ?>/patient/specialties">Back to specialties</a>
    </div>

    <form class="row g-2 mb-4" action="<?= BASE_URL ?>/patient/offices/search" method="get">
        <div class="col-md-8">
            <input type="search" class="form-control" name="q" placeholder="Search offices (name or address)" value="<?= htmlspecialchars($searchTerm ?? '') ?>">
        </div>
        <div class="col-md-4">
            <button class="btn btn-primary-custom w-100" type="submit">Search</button>
        </div>
    </form>

    <?php if (empty($offices)): ?>
        <div class="alert alert-custom">No offices match your criteria.</div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($offices as $office): ?>
                <div class="col-sm-6 col-md-4">
                    <div class="card h-100 border-0 card-hover rounded-4">
                        <div class="card-body d-flex flex-column">
                            <h3 class="h5 fw-semibold mb-2"><?= htmlspecialchars($office['office_name']) ?></h3>
                            <?php if (!empty($office['address'])): ?>
                                <p class="small text-muted mb-1"><?= htmlspecialchars($office['address']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($office['phone'])): ?>
                                <p class="small mb-2">Phone: <?= htmlspecialchars($office['phone']) ?></p>
                            <?php endif; ?>
                            <p class="small text-truncate mb-3"><?= htmlspecialchars($office['description'] ?? 'No description available yet.') ?></p>
                            <a href="<?= BASE_URL ?>/patient/offices/show/<?= $office['id'] ?>" class="btn btn-outline-primary mt-auto">View profile</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
