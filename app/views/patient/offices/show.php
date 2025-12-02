<?php $this->title = $office['office_name'] . ' – Office Profile'; ?>

<section class="patient-section office-profile">
    <header class="patient-section__header">
        <h2><?= htmlspecialchars($office['office_name']) ?></h2>
        <p><?= htmlspecialchars($office['address']) ?></p>
    </header>

    <?php if (!empty($office['logo'])): ?>
        <div class="office-profile__logo">
            <img src="<?= BASE_URL ?>/uploads/logos/<?= htmlspecialchars($office['logo']) ?>" alt="<?= htmlspecialchars($office['office_name']) ?>">
        </div>
    <?php endif; ?>

    <div class="office-profile__details">
        <?php if (!empty($office['phone'])): ?>
            <p><strong>Phone:</strong> <?= htmlspecialchars($office['phone']) ?></p>
        <?php endif; ?>
        <?php if (!empty($office['website'])): ?>
            <p><strong>Website:</strong> <a href="<?= htmlspecialchars($office['website']) ?>" target="_blank" rel="noreferrer">Visit site</a></p>
        <?php endif; ?>
        <p><?= nl2br(htmlspecialchars($office['description'] ?? 'No additional description.')) ?></p>
    </div>

    <section class="doctor-list">
        <h3>Doctors</h3>
        <?php if (empty($doctors)): ?>
            <p>No doctors are listed for this office yet.</p>
        <?php else: ?>
            <div class="doctor-list__grid">
                <?php foreach ($doctors as $doctor): ?>
                    <article class="doctor-card">
                        <header>
                            <h4><?= htmlspecialchars($doctor['name']) ?></h4>
                            <?php if (!empty($doctor['specialty_name'])): ?>
                                <span class="doctor-card__specialty"><?= htmlspecialchars($doctor['specialty_name']) ?></span>
                            <?php endif; ?>
                        </header>
                        <p><?= htmlspecialchars($doctor['bio'] ?? 'No biography available.') ?></p>
                        <a class="btn" href="<?= BASE_URL ?>/patient/booking/calendar?doctor_id=<?= $doctor['id'] ?>&office_id=<?= $office['id'] ?>">Book Appointment</a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</section>
