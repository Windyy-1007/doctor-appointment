<section class="panel">
    <h2>Admin Users</h2>
    <?php if (!empty($flash)): ?>
        <div class="form-success">
            <?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= $user['is_active'] ? 'Active' : 'Deactivated' ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>/admin/users/edit/<?= $user['id'] ?>">Edit</a>
                        |
                        <?php if ($user['is_active']): ?>
                            <a href="<?= BASE_URL ?>/admin/users/deactivate/<?= $user['id'] ?>">Deactivate</a>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/admin/users/activate/<?= $user['id'] ?>">Activate</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
