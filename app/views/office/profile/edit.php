<?php $this->title = 'Edit Office Profile | MediBook'; ?>

<section class="section-card">
    <h2 class="h4 mb-3">Edit Office Profile</h2>

    <?php if (!empty($flash)): ?>
        <div class="form-success">
            <?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="form-errors">
            <ul>
                <?php foreach ($errors as $message): ?>
                    <li><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($office['logo'])): ?>
        <div class="mb-3">
            <strong>Current logo:</strong><br>
            <img src="<?= BASE_URL ?>/<?= htmlspecialchars($office['logo'], ENT_QUOTES, 'UTF-8') ?>" alt="Office logo" class="img-fluid" style="max-width: 200px; height: auto;">
        </div>
    <?php endif; ?>

    <form class="row g-3" method="post" action="<?= BASE_URL ?>/office/profile/update" enctype="multipart/form-data">
        <div class="col-md-6">
            <label class="form-label" for="office_name">Office Name</label>
            <input class="form-control" type="text" id="office_name" name="office_name" value="<?= htmlspecialchars($office['office_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="phone">Phone</label>
            <input class="form-control" type="text" id="phone" name="phone" value="<?= htmlspecialchars($office['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="col-12">
            <label class="form-label" for="address">Address</label>
            <input class="form-control" type="text" id="address" name="address" value="<?= htmlspecialchars($office['address'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="col-12">
            <label class="form-label" for="website">Website</label>
            <input class="form-control" type="url" id="website" name="website" value="<?= htmlspecialchars($office['website'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="col-12">
            <label class="form-label" for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="4"><?= htmlspecialchars($office['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="logo">Upload Logo (JPG/PNG/WEBP, max 2MB)</label>
            <input class="form-control" type="file" id="logo" name="logo" accept="image/png,image/jpeg,image/webp">
        </div>
        <div class="col-12">
            <button class="btn btn-primary-custom" type="submit">Save profile</button>
        </div>
    </form>
</section>
