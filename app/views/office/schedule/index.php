<?php $this->title = 'Schedule Overview'; ?>

<div class="office-schedule">
    <header class="office-schedule__header">
        <h1>Schedule Overview</h1>
        <p>Showing the next 14 days of availability for your doctors.</p>
    </header>

    <?php if (empty($days)): ?>
        <p>No schedule data available.</p>
    <?php else: ?>
        <?php foreach ($days as $date => $day): ?>
            <section class="schedule-day">
                <h2><?= htmlspecialchars($day['label']) ?></h2>
                <div class="schedule-day__doctors">
                    <?php foreach ($day['doctors'] as $doctor): ?>
                        <article class="doctor-schedule">
                            <h3><?= htmlspecialchars($doctor['name']) ?></h3>
                            <div class="slot-grid">
                                <?php foreach ($doctor['slots'] as $slot): ?>
                                    <?php $slotClass = $slot['status'] === 'booked' ? 'slot--booked' : 'slot--available'; ?>
                                    <span class="slot <?= $slotClass ?>" data-datetime="<?= $slot['datetime'] ?>">
                                        <?= htmlspecialchars($slot['time']) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
