<?php $this->title = 'My Appointments | MediBook'; ?>

<section class="section-card card-hover">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <p class="text-uppercase text-primary-dark mb-1">Appointments</p>
            <h2 class="h4 mb-0">My appointments</h2>
        </div>
        <a href="<?= BASE_URL ?>/patient/specialties" class="text-decoration-none text-primary-dark">New booking</a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="text-uppercase small text-muted">
                <tr>
                    <th>Date &amp; Time</th>
                    <th>Doctor</th>
                    <th>Office</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($appointments)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5">No appointments found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($appointments as $appointment): ?>
                        <?php $appointmentDate = new DateTime($appointment['appointment_datetime']); ?>
                        <?php $isFuture = $appointmentDate > new DateTime('now'); ?>
                        <tr>
                            <td><?= $appointmentDate->format('F j, Y \a\t H:i') ?></td>
                            <td><?= htmlspecialchars($appointment['doctor_name']) ?></td>
                            <td><?= htmlspecialchars($appointment['office_name']) ?></td>
                            <td><?= htmlspecialchars(ucfirst($appointment['status'])) ?></td>
                            <td class="text-end">
                                <?php if ($isFuture && $appointment['status'] !== 'cancelled'): ?>
                                    <a href="<?= BASE_URL ?>/patient/appointments/cancel/<?= $appointment['id'] ?>" class="btn btn-outline-danger btn-sm me-2" data-confirm="Cancel this appointment?"><span>Cancel</span></a>
                                    <a href="<?= BASE_URL ?>/patient/appointments/reschedule/<?= $appointment['id'] ?>" class="btn btn-outline-primary btn-sm">Reschedule</a>
                                <?php else: ?>
                                    <small class="text-muted">No actions</small>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
