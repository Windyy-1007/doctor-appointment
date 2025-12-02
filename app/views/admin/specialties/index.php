<section class="panel">
    <h2>Specialties</h2>

    <?php if (!empty($flash)): ?>
        <div class="form-success">
            <?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <p><a href="<?= BASE_URL ?>/admin/specialties/create">Create new specialty</a></p>

    <table>
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
                <tr>
                    <td><?= $specialty['id'] ?></td>
                    <td><?= htmlspecialchars($specialty['name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($specialty['description'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>/admin/specialties/edit/<?= $specialty['id'] ?>">Edit</a>
                        |
                        <a href="<?= BASE_URL ?>/admin/specialties/delete/<?= $specialty['id'] ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
