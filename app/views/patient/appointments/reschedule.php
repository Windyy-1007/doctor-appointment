<?php $this->title = 'Reschedule Appointment | MediBook'; ?>

<section class="section-card card-hover">
    <div class="mb-4">
        <p class="text-uppercase text-primary-dark mb-1">Reschedule</p>
        <h2 class="h4 mb-2">Adjust your appointment</h2>
        <p class="text-muted mb-0">Current slot: <?= (new DateTime($appointment['appointment_datetime']))->format('F j, Y \a\t H:i') ?></p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-custom"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>/patient/appointments/reschedule/<?= $appointment['id'] ?>">
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
                                <?php $isBooked = $slot['is_booked'] && $slot['datetime'] !== $appointment['appointment_datetime']; ?>
                                <?php $label = htmlspecialchars($slot['time']); ?>
                                <?php if ($isBooked): ?>
                                    <button type="button" class="calendar-slot booked" disabled><?= $label ?></button>
                                <?php else: ?>
                                    <?php $selected = ($selectedSlot ?? '') === $slot['datetime']; ?>
                                    <button type="button" class="calendar-slot available<?= $selected ? ' selected' : '' ?>" data-datetime="<?= $slot['datetime'] ?>" data-label="<?= $slot['label'] ?? $slot['time'] ?>"><?= $label ?></button>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-4">
            <div class="slot-selection-message alert alert-custom d-none" role="alert"></div>
            <button type="submit" class="btn btn-primary-custom w-100">Confirm new slot</button>
        </div>
    </form>
</section>
