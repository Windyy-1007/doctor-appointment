<section class="auth-box">
    <h2>Patient Registration</h2>

    <?php if (!empty($errors)): ?>
        <div class="form-errors">
            <ul>
                <?php foreach ($errors as $message): ?>
                    <li><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>/auth/register">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" value="<?= $old['name'] ?? '' ?>" required autofocus>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= $old['email'] ?? '' ?>" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <button type="submit">Create Account</button>
    </form>

    <p class="muted">
        Already have an account? <a href="<?= BASE_URL ?>/auth/login">Log in</a>
    </p>
</section>
