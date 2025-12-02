<section class="auth-box">
    <h2>Edit Office</h2>

    <?php if (!empty($errors)): ?>
        <div class="form-errors">
            <ul>
                <?php foreach ($errors as $message): ?>
                    <li><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>/staff/moderation/offices/update/<?= $office['id'] ?>">
        <label for="office_name">Name</label>
        <input type="text" id="office_name" name="office_name" value="<?= htmlspecialchars($office['office_name'], ENT_QUOTES, 'UTF-8') ?>" required>

        <label for="address">Address</label>
        <input type="text" id="address" name="address" value="<?= htmlspecialchars($office['address'], ENT_QUOTES, 'UTF-8') ?>" required>

        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($office['phone'], ENT_QUOTES, 'UTF-8') ?>" required>

        <label for="description">Description</label>
        <textarea id="description" name="description"><?= htmlspecialchars($office['description'], ENT_QUOTES, 'UTF-8') ?></textarea>

        <label for="status">Status</label>
        <select id="status" name="status">
            <?php foreach (['pending', 'approved', 'deactivated'] as $statusOption): ?>
                <option value="<?= $statusOption ?>" <?= $office['status'] === $statusOption ? 'selected' : '' ?>><?= ucfirst($statusOption) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Save office</button>
    </form>
</section>
