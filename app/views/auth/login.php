<?php $this->title = 'Login | MediBook'; ?>

<section class="d-flex justify-content-center align-items-center" style="min-height:70vh;">
    <div class="card shadow-sm border-0 rounded-4" style="max-width: 520px; width: 100%;">
        <div class="card-body p-5">
            <h1 class="h3 fw-bold mb-3 text-primary-dark">Welcome back</h1>
            <p class="text-muted mb-4">Sign in to view available offices and manage your bookings.</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-custom mb-3"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success alert-custom mb-3"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/auth/login" method="post">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary-custom w-100">Login</button>
            </form>

            <div class="mt-4 d-flex justify-content-between small">
                <a href="<?= BASE_URL ?>/auth/register" class="text-decoration-none">Register as patient</a>
                <a href="<?= BASE_URL ?>/office/auth/register" class="text-decoration-none">Register as office</a>
            </div>
        </div>
    </div>
</section>
