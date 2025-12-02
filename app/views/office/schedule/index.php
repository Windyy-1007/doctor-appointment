<?php $this->title = 'Schedule Overview'; ?>

<section class="section-card">
    <header class="mb-4">
        <h2 class="h4 mb-1">Schedule Overview</h2>
        <p class="text-muted mb-0">Showing the next 14 days of availability for your doctors.</p>
    </header>

    <?php if (empty($days)): ?>
        <div class="alert alert-custom">No schedule data available.</div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($days as $date => $day): ?>
                <div class="col-lg-6">
                    <article class="office-schedule-card">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h3 class="h6 mb-0"><?= htmlspecialchars($day['label']) ?></h3>
                            <span class="text-muted small"><?= htmlspecialchars($date) ?></span>
                        </div>
                        <?php foreach ($day['doctors'] as $doctor): ?>
                            <div class="doctor-row">
                                <p class="mb-1 fw-semibold"><?= htmlspecialchars($doctor['name']) ?></p>
                                <div class="slot-grid">
                                    <?php foreach ($doctor['slots'] as $slot): ?>
                                        <?php $slotClass = $slot['status'] === 'booked' ? 'slot--booked' : 'slot--available'; ?>
                                        <span class="slot-chip <?= $slotClass ?>" data-datetime="<?= $slot['datetime'] ?>">
                                            <?= htmlspecialchars($slot['time']) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php if (next($day['doctors']) !== false): ?>
                                <hr class="my-3">
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
