<section class="panel">
    <h2>Admin Dashboard</h2>

    <div class="stats-grid">
        <div>
            <strong>Total Appointments</strong>
            <p><?= $totals['appointments'] ?></p>
        </div>
        <div>
            <strong>Total Offices</strong>
            <p><?= $totals['offices'] ?></p>
        </div>
        <div>
            <strong>Total Patients</strong>
            <p><?= $totals['patients'] ?></p>
        </div>
    </div>

    <h3>Appointments per week (last 4 weeks)</h3>
    <table>
        <thead>
            <tr>
                <th>Week</th>
                <th>Total Appointments</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($stats)): ?>
                <tr>
                    <td colspan="2">No data available yet.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($stats as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['week_start'], ENT_QUOTES, 'UTF-8') ?> to <?= htmlspecialchars($row['week_end'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= $row['total_appointments'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>
