<section class="admin-specialties-section">
    <div class="admin-header">
        <div>
            <h1>Specialties</h1>
            <p class="text-muted">Manage medical specialties and categories</p>
        </div>
    </div>

    <?php if (!empty($flash)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="specialty-actions">
        <a href="<?= BASE_URL ?>/admin/specialties/create" class="btn btn-primary">
            + Create New Specialty
        </a>
    </div>

    <div class="specialties-container">
        <div class="table-responsive">
            <table class="specialties-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($specialties as $specialty): ?>
                        <tr class="specialty-row">
                            <td class="specialty-id"><code><?= $specialty['id'] ?></code></td>
                            <td class="specialty-name"><?= htmlspecialchars($specialty['name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="specialty-description"><?= htmlspecialchars($specialty['description'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="specialty-actions">
                                <a href="<?= BASE_URL ?>/admin/specialties/edit/<?= $specialty['id'] ?>" class="btn btn-sm btn-edit">
                                    Edit
                                </a>
                                <a href="<?= BASE_URL ?>/admin/specialties/delete/<?= $specialty['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this specialty?')">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
