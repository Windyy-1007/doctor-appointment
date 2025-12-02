<?php $this->title = 'Office Dashboard | MediBook'; ?>

<section class="section-card">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="h4 mb-0">Office Dashboard</h2>
            <p class="text-muted">Welcome back, <?= htmlspecialchars($office['office_name'] ?? 'your office') ?>. Here are your latest stats.</p>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <p class="small text-uppercase text-primary-dark mb-1">Live doctors</p>
                    <h3 class="h2 mb-0"><?= htmlspecialchars((string) $doctorCount) ?></h3>
                    <p class="small text-muted mb-0">Doctors linked to this office</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <p class="small text-uppercase text-primary-dark mb-1">Appointments</p>
                    <h3 class="h2 mb-0"><?= htmlspecialchars((string) $stats['total']) ?></h3>
                    <p class="small text-muted mb-0">Total appointments recorded</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <p class="small text-uppercase text-primary-dark mb-1">Pending/reserved</p>
                    <h3 class="h2 mb-0"><?= htmlspecialchars((string) $stats['pending']) ?></h3>
                    <p class="small text-muted mb-0">Pending appointments waiting confirmation</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h4 class="h6 mb-3">Upcoming confirmed slots</h4>
                    <?php if (empty($upcomingAppointments)): ?>
                        <p class="text-muted">No upcoming confirmed appointments yet.</p>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($upcomingAppointments as $appointment): ?>
                                <li class="list-group-item border-0 d-flex justify-content-between align-items-center">
                                    <span><?= htmlspecialchars($appointment['appointment_datetime']) ?></span>
                                    <span class="badge bg-primary bg-opacity-10 text-primary">Dr. <?= htmlspecialchars($appointment['doctor_name'] ?? 'Unknown') ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h4 class="h6 mb-3">Confirmed appointments</h4>
                    <p class="mb-2">Confirmed: <strong><?= htmlspecialchars((string) $stats['confirmed']) ?></strong></p>
                    <p class="mb-0 text-muted">Go to the schedule page to manage availability and approved appointments.</p>
                </div>
            </div>
        </div>
    </div>
</section>
