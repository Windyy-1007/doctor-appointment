<section class="section-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">Office Moderation</h2>
        <p class="text-muted small mb-0">Review and moderate office submissions.</p>
    </div>

    <?php if (!empty($flash)): ?>
        <div class="form-success">
            <?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($offices)): ?>
        <div class="table-wrapper">
            <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Office Name</th>
                    <th>Status</th>
                    <th>Open Reports</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($offices as $office): ?>
                    <tr>
                        <td><?= $office['id'] ?></td>
                        <td><?= htmlspecialchars($office['office_name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($office['status'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= $office['open_reports'] ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>/staff/moderation/offices/edit/<?= $office['id'] ?>">Edit</a>
                            <?php if ($office['status'] !== 'deactivated'): ?>
                                | <a href="<?= BASE_URL ?>/staff/moderation/offices/deactivate/<?= $office['id'] ?>">Deactivate</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No offices available yet.</p>
    <?php endif; ?>
</section>
