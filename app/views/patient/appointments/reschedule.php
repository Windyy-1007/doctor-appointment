<?php $this->title = 'Reschedule Appointment'; ?>

<section class="patient-section reschedule">
    <header class="patient-section__header">
        <h2>Reschedule Appointment</h2>
        <p>Current slot: <?= (new DateTime($appointment['appointment_datetime']))->format('F j, Y \a\t H:i') ?></p>
    </header>

    <?php if (!empty($error)): ?>
        <div class="alert alert--error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>/patient/appointments/reschedule/<?= $appointment['id'] ?>">
        <div class="slot-calendar">
            <?php foreach ($days as $day): ?>
                <section class="slot-calendar__day">
                    <header>
                        <strong><?= htmlspecialchars($day['label']) ?></strong>
                    </header>
                    <div class="slot-grid">
                        <?php foreach ($day['slots'] as $slot): ?>
                            <?php if ($slot['is_booked'] && $slot['datetime'] !== $appointment['appointment_datetime']): ?>
                                <span class="slot slot--booked"><?= htmlspecialchars($slot['time']) ?></span>
                            <?php else: ?>
                                <?php $isSelected = $selectedSlot === $slot['datetime']; ?>
                                <button type="submit" name="slot_datetime" value="<?= $slot['datetime'] ?>" class="slot <?= $isSelected ? 'slot--selected' : '' ?> <?= $slot['is_booked'] ? 'slot--booked' : 'slot--available' ?>">
                                    <?= htmlspecialchars($slot['time']) ?>
                                </button>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        </div>
    </form>
</section>
