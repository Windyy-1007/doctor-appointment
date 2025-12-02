<?php $this->title = $office['office_name'] . ' | MediBook'; ?>

<section class="section-card card-hover mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-md-3 text-center">
            <div class="border rounded-4" style="padding:1rem; background:#fff;">
                <p class="text-uppercase text-primary-dark small mb-1">Office</p>
                <h3 class="h5 fw-semibold">Logo</h3>
            </div>
        </div>
        <div class="col-md-9">
            <h2 class="h4 fw-bold mb-2"><?= htmlspecialchars($office['office_name']) ?></h2>
            <?php if (!empty($office['address'])): ?>
                <p class="mb-1"><strong>Address:</strong> <?= htmlspecialchars($office['address']) ?></p>
            <?php endif; ?>
            <?php if (!empty($office['phone'])): ?>
                <p class="mb-1"><strong>Phone:</strong> <?= htmlspecialchars($office['phone']) ?></p>
            <?php endif; ?>
            <?php if (!empty($office['website'])): ?>
                <p class="mb-1"><strong>Website:</strong> <a class="text-decoration-none" href="<?= htmlspecialchars($office['website']) ?>" target="_blank" rel="noopener"><?= htmlspecialchars($office['website']) ?></a></p>
            <?php endif; ?>
            <p class="text-muted mb-0"><?= htmlspecialchars($office['description'] ?? 'Description coming soon.') ?></p>
        </div>
    </div>
</section>

<section class="section-card card-hover">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <p class="text-uppercase text-primary-dark mb-1">Doctors</p>
            <h2 class="h4 mb-0">Available doctors</h2>
        </div>
        <a href="<?= BASE_URL ?>/patient/specialties" class="text-decoration-none text-primary-dark">Back to specialties</a>
    </div>
    <?php if (empty($doctors)): ?>
        <div class="alert alert-custom">This office has no listed doctors yet.</div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($doctors as $doctor): ?>
                <div class="col-md-6">
                    <div class="card h-100 border-0 card-hover rounded-4">
                        <div class="card-body d-flex flex-column">
                            <div class="mb-3">
                                <h3 class="h5 fw-semibold mb-1"><?= htmlspecialchars($doctor['name']) ?></h3>
                                <p class="small text-primary-dark mb-0"><?= htmlspecialchars($doctor['specialty_name'] ?? 'Specialty not set') ?></p>
                            </div>
                            <p class="small text-muted mb-4"><?= htmlspecialchars($doctor['bio'] ?? 'Bio not available yet.') ?></p>
                            <a href="<?= BASE_URL ?>/patient/booking/calendar?doctor_id=<?= $doctor['id'] ?>&office_id=<?= $office['id'] ?>" class="btn btn-primary-custom mt-auto">Book Appointment</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
