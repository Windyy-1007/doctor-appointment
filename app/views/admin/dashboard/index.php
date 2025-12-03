<section class="admin-dashboard-section">
    <div class="dashboard-header">
        <div>
            <h1>Admin Dashboard</h1>
            <p class="text-muted">System overview and key metrics</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card__content">
                <p class="stat-card__label">Total Appointments</p>
                <h3 class="stat-card__value"><?= $totals['appointments'] ?></h3>
                <p class="stat-card__subtext">All appointments in system</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card__content">
                <p class="stat-card__label">Total Offices</p>
                <h3 class="stat-card__value"><?= $totals['offices'] ?></h3>
                <p class="stat-card__subtext">Active medical offices</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card__content">
                <p class="stat-card__label">Total Patients</p>
                <h3 class="stat-card__value"><?= $totals['patients'] ?></h3>
                <p class="stat-card__subtext">Registered patient accounts</p>
            </div>
        </div>
    </div>

    <div class="dashboard-chart">
        <div class="chart-header">
            <h2>Appointments per week (last 4 weeks)</h2>
        </div>

        <div class="table-responsive">
            <table class="appointments-table">
                <thead>
                    <tr>
                        <th>Week</th>
                        <th>Total Appointments</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($stats)): ?>
                        <tr>
                            <td colspan="2" class="text-center text-muted">No data available yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($stats as $row): ?>
                            <tr>
                                <td class="week-range"><?= htmlspecialchars($row['week_start'], ENT_QUOTES, 'UTF-8') ?> to <?= htmlspecialchars($row['week_end'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="appointment-count">
                                    <span class="badge badge-count"><?= $row['total_appointments'] ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
