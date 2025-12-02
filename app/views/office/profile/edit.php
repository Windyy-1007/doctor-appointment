<section class="auth-box">
    <h2>Edit Office Profile</h2>

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
        <p>
            <strong>Current logo:</strong><br>
            <img src="<?= BASE_URL ?>/<?= htmlspecialchars($office['logo'], ENT_QUOTES, 'UTF-8') ?>" alt="Office logo" style="max-width: 200px; height: auto;">
        </p>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>/office/profile/update" enctype="multipart/form-data">
        <label for="office_name">Office Name</label>
        <input type="text" id="office_name" name="office_name" value="<?= htmlspecialchars($office['office_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>

        <label for="address">Address</label>
        <input type="text" id="address" name="address" value="<?= htmlspecialchars($office['address'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>

        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($office['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>

        <label for="website">Website</label>
        <input type="url" id="website" name="website" value="<?= htmlspecialchars($office['website'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <label for="description">Description</label>
        <textarea id="description" name="description"><?= htmlspecialchars($office['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>

        <label for="logo">Upload Logo (JPG/PNG/WEBP, max 2MB)</label>
        <input type="file" id="logo" name="logo" accept="image/png,image/jpeg,image/webp">

        <button type="submit">Save profile</button>
    </form>
</section>
