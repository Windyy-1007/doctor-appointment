<section class="panel">
    <h2>Open Reports</h2>

    <?php if (!empty($flash)): ?>
        <div class="form-success">
            <?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <p><a href="<?= BASE_URL ?>/staff/reports/resolved">View resolved reports</a></p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Category</th>
                <th>Target</th>
                <th>Status</th>
                <th>Reported</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($reports)): ?>
                <tr>
                    <td colspan="7">No open reports at this time.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($reports as $report): ?>
                    <tr>
                        <td><?= $report['id'] ?></td>
                        <td><?= htmlspecialchars($report['title'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($report['category'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($report['target_type'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($report['status'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= $report['created_at'] ?></td>
                        <td><a href="<?= BASE_URL ?>/staff/reports/show/<?= $report['id'] ?>">View</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>
