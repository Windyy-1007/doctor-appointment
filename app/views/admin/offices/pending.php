<section class="panel">
    <h2>Pending Offices</h2>

    <?php if (!empty($flash)): ?>
        <div class="form-success">
            <?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Office Name</th>
                <th>Owner</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($offices as $office): ?>
                <tr>
                    <td><?= $office['id'] ?></td>
                    <td><?= htmlspecialchars($office['office_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($office['owner_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($office['owner_email'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($office['phone'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($office['address'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= $office['created_at'] ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>/admin/offices/approve/<?= $office['id'] ?>">Approve</a>
                        |
                        <a href="<?= BASE_URL ?>/admin/offices/reject/<?= $office['id'] ?>">Reject</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
