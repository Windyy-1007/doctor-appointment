<section class="panel">
    <h2>Your Doctors</h2>

    <?php if (!empty($flash)): ?>
        <div class="form-success">
            <?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <p><a href="<?= BASE_URL ?>/office/doctors/create">Add a new doctor</a></p>

    <?php if (empty($doctors)): ?>
        <p>No doctors yet.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Specialty</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($doctors as $doctor): ?>
                    <tr>
                        <td><?= $doctor['id'] ?></td>
                        <td><?= htmlspecialchars($doctor['name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($doctor['specialty_name'] ?? 'General', ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>/office/doctors/edit/<?= $doctor['id'] ?>">Edit</a> |
                            <a href="<?= BASE_URL ?>/office/doctors/delete/<?= $doctor['id'] ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
