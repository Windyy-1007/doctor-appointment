<?php $this->title = 'Patient Registration | MediBook'; ?>

<section class="d-flex justify-content-center align-items-center" style="min-height:70vh;">
    <div class="card shadow-sm border-0 rounded-4" style="max-width: 520px; width: 100%;">
        <div class="card-body p-5">
            <h1 class="h4 fw-bold mb-3 text-primary-dark">Patient Registration</h1>
            <p class="text-muted mb-4">Create an account to browse offices and book appointments.</p>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-custom mb-3">
                    <ul class="mb-0">
                        <?php foreach ($errors as $message): ?>
                            <li><?= htmlspecialchars($message) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= BASE_URL ?>/auth/register">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Full Name</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($old['name'] ?? '') ?>" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary-custom w-100">Create account</button>
            </form>

            <p class="text-center text-muted mt-4 small">
                Already have an account? <a href="<?= BASE_URL ?>/auth/login" class="text-decoration-none">Login</a>
            </p>
        </div>
    </div>
</section>
