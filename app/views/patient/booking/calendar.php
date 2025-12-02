<?php $this->title = 'Book Appointment'; ?>

<section class="patient-section booking-calendar">
    <header class="patient-section__header">
        <h2>Book with Dr. <?= htmlspecialchars($doctor['name']) ?></h2>
        <p><?= htmlspecialchars($office['office_name']) ?> · <?= htmlspecialchars($office['address']) ?></p>
    </header>

    <?php if (!empty($error)): ?>
        <div class="alert alert--error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>/patient/booking/confirm">
        <input type="hidden" name="doctor_id" value="<?= $doctor['id'] ?>">
        <input type="hidden" name="office_id" value="<?= $office['id'] ?>">

        <div class="slot-calendar">
            <?php foreach ($days as $day): ?>
                <section class="slot-calendar__day">
                    <header>
                        <strong><?= htmlspecialchars($day['label']) ?></strong>
                    </header>
                    <div class="slot-grid">
                        <?php foreach ($day['slots'] as $slot): ?>
                            <?php if ($slot['is_booked']): ?>
                                <span class="slot slot--booked"><?= htmlspecialchars($slot['time']) ?></span>
                            <?php else: ?>
                                <button type="submit" name="slot_datetime" value="<?= $slot['datetime'] ?>" class="slot slot--available <?= $selectedSlot === $slot['datetime'] ? 'slot--selected' : '' ?>">
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
