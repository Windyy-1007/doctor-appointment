<?php $this->title = 'My Appointments'; ?>

<section class="patient-section appointments-list">
    <header class="patient-section__header">
        <h2>My Appointments</h2>
        <?php if (!empty($flash)): ?>
            <div class="alert alert--success"><?= htmlspecialchars($flash) ?></div>
        <?php endif; ?>
        <p>Manage your bookings and keep an eye on upcoming visits.</p>
    </header>

    <?php if (empty($appointments)): ?>
        <p>You have no appointments yet.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Date &amp; Time</th>
                    <th>Doctor</th>
                    <th>Office</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment): ?>
                    <?php $appointmentDate = new DateTime($appointment['appointment_datetime']); ?>
                    <?php $isFuture = $appointmentDate > new DateTime('now'); ?>
                    <tr>
                        <td><?= $appointmentDate->format('F j, Y \a\t H:i') ?></td>
                        <td><?= htmlspecialchars($appointment['doctor_name']) ?></td>
                        <td><?= htmlspecialchars($appointment['office_name']) ?></td>
                        <td><?= htmlspecialchars(ucfirst($appointment['status'])) ?></td>
                        <td>
                            <?php if ($isFuture && $appointment['status'] !== 'cancelled'): ?>
                                <a class="btn btn--link" href="<?= BASE_URL ?>/patient/appointments/cancel/<?= $appointment['id'] ?>">Cancel</a>
                                <a class="btn btn--link" href="<?= BASE_URL ?>/patient/appointments/reschedule/<?= $appointment['id'] ?>">Reschedule</a>
                            <?php else: ?>
                                <span class="muted">No actions available</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
