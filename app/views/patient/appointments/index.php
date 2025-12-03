<?php $this->title = 'My Appointments | MediBook'; ?>

<section class="appointments-section">
    <div class="appointments-header">
        <div>
            <p class="section-label">Your Schedule</p>
            <h1>My Appointments</h1>
        </div>
        <a href="<?= BASE_URL ?>/patient/specialties" class="btn btn-primary">+ New Booking</a>
    </div>

    <div class="table-responsive">
        <table class="appointments-table">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Doctor</th>
                    <th>Office</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($appointments)): ?>
                    <tr>
                        <td colspan="5" class="empty-state">
                            <p>No appointments found.</p>
                            <a href="<?= BASE_URL ?>/patient/specialties" class="btn btn-primary btn-sm">Book Your First Appointment</a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($appointments as $appointment): ?>
                        <?php $appointmentDate = new DateTime($appointment['appointment_datetime']); ?>
                        <?php $isFuture = $appointmentDate > new DateTime('now'); ?>
                        <tr class="appointment-row appointment-row--<?= strtolower($appointment['status']) ?>">
                            <td class="col-datetime"><?= $appointmentDate->format('M d, Y · H:i') ?></td>
                            <td class="col-doctor">Dr. <?= htmlspecialchars($appointment['doctor_name']) ?></td>
                            <td class="col-office"><?= htmlspecialchars($appointment['office_name']) ?></td>
                            <td>
                                <span class="status-badge status-<?= strtolower($appointment['status']) ?>">
                                    <?= htmlspecialchars(ucfirst($appointment['status'])) ?>
                                </span>
                            </td>
                            <td class="col-actions">
                                <?php if ($isFuture && $appointment['status'] !== 'cancelled'): ?>
                                    <a href="<?= BASE_URL ?>/patient/appointments/reschedule/<?= $appointment['id'] ?>" class="action-btn reschedule">Reschedule</a>
                                    <a href="<?= BASE_URL ?>/patient/appointments/cancel/<?= $appointment['id'] ?>" class="action-btn cancel" onclick="return confirm('Cancel this appointment?')">Cancel</a>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
