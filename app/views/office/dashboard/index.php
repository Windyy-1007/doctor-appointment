<?php $this->title = 'Office Dashboard | MediBook'; ?>

<section class="office-dashboard-section">
    <div class="dashboard-header">
        <div>
            <h1>Office Dashboard</h1>
            <p class="text-muted">Welcome back, <?= htmlspecialchars($office['office_name'] ?? 'your office') ?>. Here are your latest stats.</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card stat-card--doctors">
            <div class="stat-card__icon">👨‍⚕️</div>
            <div class="stat-card__content">
                <p class="stat-card__label">Live Doctors</p>
                <h3 class="stat-card__value"><?= htmlspecialchars((string) $doctorCount) ?></h3>
                <p class="stat-card__subtext">Doctors linked to this office</p>
            </div>
        </div>

        <div class="stat-card stat-card--appointments">
            <div class="stat-card__icon">📅</div>
            <div class="stat-card__content">
                <p class="stat-card__label">Total Appointments</p>
                <h3 class="stat-card__value"><?= htmlspecialchars((string) $stats['total']) ?></h3>
                <p class="stat-card__subtext">All appointments recorded</p>
            </div>
        </div>

        <div class="stat-card stat-card--pending">
            <div class="stat-card__icon">⏳</div>
            <div class="stat-card__content">
                <p class="stat-card__label">Pending/Reserved</p>
                <h3 class="stat-card__value"><?= htmlspecialchars((string) $stats['pending']) ?></h3>
                <p class="stat-card__subtext">Awaiting confirmation</p>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Upcoming Confirmed Slots</h3>
            </div>
            <div class="card-body">
                <?php if (empty($upcomingAppointments)): ?>
                    <p class="text-muted">No upcoming confirmed appointments yet.</p>
                <?php else: ?>
                    <div class="appointments-list">
                        <?php foreach ($upcomingAppointments as $appointment): ?>
                            <div class="appointment-item">
                                <div class="appointment-info">
                                    <p class="appointment-datetime"><?= htmlspecialchars($appointment['appointment_datetime']) ?></p>
                                    <p class="appointment-doctor">Dr. <?= htmlspecialchars($appointment['doctor_name'] ?? 'Unknown') ?></p>
                                </div>
                                <span class="badge badge-primary">Confirmed</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-header">
                <h3>Appointment Summary</h3>
            </div>
            <div class="card-body">
                <div class="summary-item">
                    <span class="summary-label">Confirmed:</span>
                    <span class="summary-value"><?= htmlspecialchars((string) $stats['confirmed']) ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Pending:</span>
                    <span class="summary-value"><?= htmlspecialchars((string) $stats['pending']) ?></span>
                </div>
                <p class="text-muted mt-3 mb-0">Go to the schedule page to manage availability and appointments.</p>
            </div>
        </div>
    </div>
</section>
