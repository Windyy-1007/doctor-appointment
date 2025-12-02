<section class="auth-box">
    <h2>Edit Specialty</h2>

    <?php if (!empty($errors)): ?>
        <div class="form-errors">
            <ul>
                <?php foreach ($errors as $message): ?>
                    <li><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>/admin/specialties/update/<?= $specialty['id'] ?>">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($specialty['name'], ENT_QUOTES, 'UTF-8') ?>" required>

        <label for="description">Description</label>
        <textarea id="description" name="description"><?= htmlspecialchars($specialty['description'], ENT_QUOTES, 'UTF-8') ?></textarea>

        <button type="submit">Save changes</button>
    </form>
</section>
