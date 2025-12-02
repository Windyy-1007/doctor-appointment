<section class="auth-box">
    <h2>Edit <?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?></h2>

    <?php if (!empty($errors)): ?>
        <div class="form-errors">
            <ul>
                <?php foreach ($errors as $message): ?>
                    <li><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>/admin/users/update/<?= $user['id'] ?>">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>" required>

        <label for="role">Role</label>
        <select id="role" name="role">
            <?php foreach (['admin', 'staff', 'office', 'patient'] as $roleOption): ?>
                <option value="<?= $roleOption ?>" <?= $user['role'] === $roleOption ? 'selected' : '' ?>><?= ucfirst($roleOption) ?></option>
            <?php endforeach; ?>
        </select>

        <label>
            <input type="checkbox" name="is_active" value="1" <?= $user['is_active'] ? 'checked' : '' ?>> Account is active
        </label>

        <button type="submit">Save changes</button>
    </form>
</section>
