<?php $this->title = 'Specialties | MediBook'; ?>

<section class="section-card card-hover">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-uppercase text-primary-dark mb-1">Explore</p>
            <h2 class="h4 mb-0">Specialties</h2>
        </div>
        <a class="text-decoration-none text-primary-dark" href="<?= BASE_URL ?>">Return home</a>
    </div>

    <?php if (empty($specialties)): ?>
        <div class="alert alert-custom">No specialties are listed yet.</div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($specialties as $specialty): ?>
                <div class="col-sm-6 col-md-4">
                    <div class="card h-100 border-0 card-hover">
                        <div class="card-body">
                            <p class="text-uppercase text-primary-dark small mb-1">Specialty</p>
                            <h3 class="h5 fw-semibold"><?= htmlspecialchars($specialty['name']) ?></h3>
                            <p class="small mb-3"><?= htmlspecialchars($specialty['description'] ?? 'Description coming soon.') ?></p>
                            <a class="btn btn-outline-primary w-100" href="<?= BASE_URL ?>/patient/offices/specialty/<?= $specialty['id'] ?>">View offices</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
