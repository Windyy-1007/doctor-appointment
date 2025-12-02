<section class="panel">
    <h2>Office Moderation</h2>

    <?php if (!empty($flash)): ?>
        <div class="form-success">
            <?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($offices)): ?>
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
                            |
                            <a href="<?= BASE_URL ?>/staff/moderation/offices/deactivate/<?= $office['id'] ?>">Deactivate</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No offices available yet.</p>
    <?php endif; ?>
</section>
