<section class="auth-box">
    <h2>Office Registration</h2>

    <?php if (!empty($success)): ?>
        <div class="form-success">
            <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
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

    <form method="post" action="<?= BASE_URL ?>/office/register">
        <label for="office_name">Office Name</label>
        <input type="text" id="office_name" name="office_name" value="<?= htmlspecialchars($old['office_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>

        <label for="contact_name">Contact Person</label>
        <input type="text" id="contact_name" name="contact_name" value="<?= htmlspecialchars($old['contact_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <label for="address">Address</label>
        <input type="text" id="address" name="address" value="<?= htmlspecialchars($old['address'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>

        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($old['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>

        <label for="website">Website</label>
        <input type="url" id="website" name="website" value="<?= htmlspecialchars($old['website'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <label for="description">Description</label>
        <textarea id="description" name="description"><?= htmlspecialchars($old['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>

        <button type="submit">Register Office</button>
    </form>

    <p class="muted">
        Already registered? <a href="<?= BASE_URL ?>/auth/login">Go to login</a>
    </p>
</section>
