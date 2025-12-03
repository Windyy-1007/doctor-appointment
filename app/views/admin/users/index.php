<section class="admin-users-section">
    <div class="admin-header">
        <div>
            <h1>User Management</h1>
            <p class="text-muted">Manage system users, roles, and permissions</p>
        </div>
    </div>

    <?php if (!empty($flash)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="icon-check-circle"></i>
            <?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="users-container">
        <div class="table-responsive">
            <table class="users-table">
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
                        <tr class="user-row">
                            <td class="user-id"><code><?= $user['id'] ?></code></td>
                            <td class="user-name"><?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="user-email"><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <span class="badge badge-role badge-role--<?= strtolower($user['role']) ?>">
                                    <?= ucfirst(htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8')) ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge <?= $user['is_active'] ? 'status-active' : 'status-inactive' ?>">
                                    <?= $user['is_active'] ? '✓ Active' : '✕ Inactive' ?>
                                </span>
                            </td>
                            <td class="user-actions">
                                <a href="<?= BASE_URL ?>/admin/users/edit/<?= $user['id'] ?>" class="btn btn-sm btn-edit">
                                    Edit
                                </a>
                                <?php if ($user['is_active']): ?>
                                    <a href="<?= BASE_URL ?>/admin/users/deactivate/<?= $user['id'] ?>" class="btn btn-sm btn-deactivate" onclick="return confirm('Deactivate this user?')">
                                        Deactivate
                                    </a>
                                <?php else: ?>
                                    <a href="<?= BASE_URL ?>/admin/users/activate/<?= $user['id'] ?>" class="btn btn-sm btn-activate">
                                        Activate
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
