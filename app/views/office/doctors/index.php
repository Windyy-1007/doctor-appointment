<?php $this->title = 'My Doctors | MediBook'; ?>

<section class="section-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="h4 mb-1">Your Doctors</h2>
            <p class="text-muted mb-0">Manage your care team and specialties.</p>
        </div>
        <a class="btn btn-primary-custom" href="<?= BASE_URL ?>/office/doctors/create">Add new doctor</a>
    </div>

    <?php if (!empty($flash)): ?>
        <div class="form-success">
            <?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if (empty($doctors)): ?>
        <div class="alert alert-custom">No doctors added yet.</div>
    <?php else: ?>
        <div class="office-panel mt-3">
            <table class="office-doctors-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Specialty</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($doctors as $doctor): ?>
                        <tr>
                            <td><?= htmlspecialchars($doctor['id'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($doctor['name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($doctor['specialty_name'] ?? 'General', ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="<?= BASE_URL ?>/office/doctors/edit/<?= $doctor['id'] ?>">Edit</a>
                                <a class="btn btn-sm btn-outline-danger" href="<?= BASE_URL ?>/office/doctors/delete/<?= $doctor['id'] ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
