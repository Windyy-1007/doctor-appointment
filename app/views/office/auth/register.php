<?php $this->title = 'Doctor\'s Office Registration | MediBook'; ?>

<section class="d-flex justify-content-center align-items-center" style="min-height:70vh;">
    <div class="card shadow-sm border-0 rounded-4" style="max-width: 760px; width: 100%;">
        <div class="card-body p-5">
            <h1 class="h4 fw-bold mb-3 text-primary-dark">Doctor's Office Registration</h1>
            <p class="text-muted mb-4">Tell us about your practice and start managing doctors and schedules.</p>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success alert-custom mb-3"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-custom mb-3">
                    <ul class="mb-0">
                        <?php foreach ($errors as $message): ?>
                            <li><?= htmlspecialchars($message) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= BASE_URL ?>/office/register" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Office Name</label>
                    <input type="text" name="office_name" class="form-control" value="<?= htmlspecialchars($old['office_name'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Contact Person</label>
                    <input type="text" name="contact_name" class="form-control" value="<?= htmlspecialchars($old['contact_name'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Phone</label>
                    <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($old['phone'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Address</label>
                    <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($old['address'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Website</label>
                    <input type="url" name="website" class="form-control" value="<?= htmlspecialchars($old['website'] ?? '') ?>">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" rows="3" class="form-control"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary-custom w-100">Register Office</button>
                </div>
            </form>

            <p class="text-center text-muted mt-3 small">
                Already registered? <a href="<?= BASE_URL ?>/auth/login" class="text-decoration-none">Go to login</a>
            </p>
        </div>
    </div>
</section>
