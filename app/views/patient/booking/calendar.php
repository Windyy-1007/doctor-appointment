<?php $this->title = 'Book Appointment | MediBook'; ?>

<section class="section-card card-hover">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <p class="text-uppercase text-primary-dark mb-1">Doctor</p>
            <h2 class="h4 mb-0">Dr. <?= htmlspecialchars($doctor['name']) ?></h2>
            <p class="text-muted mb-0"><?= htmlspecialchars($office['office_name']) ?>  <?= htmlspecialchars($office['address'] ?? 'Address not available') ?></p>
        </div>
        <a href="<?= BASE_URL ?>/patient/offices/show/<?= $office['id'] ?>" class="text-decoration-none text-primary-dark">Back to profile</a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-custom"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>/patient/booking/confirm">
        <input type="hidden" name="doctor_id" value="<?= $doctor['id'] ?>">
        <input type="hidden" name="office_id" value="<?= $office['id'] ?>">
        <input type="hidden" name="slot_datetime" value="<?= htmlspecialchars($selectedSlot ?? '') ?>">

        <div class="row g-3">
            <?php foreach ($days as $day): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="section-card p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong><?= htmlspecialchars($day['label']) ?></strong>
                            <span class="text-muted small"><?= htmlspecialchars($day['date']) ?></span>
                        </div>
                        <div class="d-grid gap-2">
                            <?php foreach ($day['slots'] as $slot): ?>
                                <?php $isBooked = $slot['is_booked']; ?>
                                <?php $slotLabel = htmlspecialchars($slot['time']); ?>
                                <?php if ($isBooked): ?>
                                    <button type="button" class="calendar-slot booked" disabled><?= $slotLabel ?></button>
                                <?php else: ?>
                                    <?php $selected = ($selectedSlot ?? '') === $slot['datetime']; ?>
                                    <button type="button" class="calendar-slot available<?= $selected ? ' selected' : '' ?>" data-datetime="<?= $slot['datetime'] ?>" data-label="<?= $slot['label'] ?? $slot['time'] ?>"><?= $slotLabel ?></button>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-4">
            <div class="slot-selection-message alert alert-custom d-none" role="alert"></div>
            <button type="submit" class="btn btn-primary-custom w-100">Confirm booking</button>
        </div>
    </form>
</section>
