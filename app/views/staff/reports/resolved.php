<section class="panel">
    <h2>Resolved Reports</h2>

    <p><a href="<?= BASE_URL ?>/staff/reports">View open reports</a></p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Resolved By</th>
                <th>Resolved At</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($reports)): ?>
                <tr>
                    <td colspan="6">No resolved reports yet.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($reports as $report): ?>
                    <tr>
                        <td><?= $report['id'] ?></td>
                        <td><?= htmlspecialchars($report['title'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($report['category'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($report['status'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($report['resolved_by'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($report['resolved_at'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>
