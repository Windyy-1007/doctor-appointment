<section class="auth-box">
    <h2>Patient Login</h2>

    <?php if (!empty($success)): ?>
        <div class="form-success">
            <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="form-errors">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>/auth/login">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= $old['email'] ?? '' ?>" required autofocus>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Log In</button>
    </form>

    <p class="muted">
        Need an account? <a href="<?= BASE_URL ?>/auth/register">Register</a>
    </p>
</section>
