<section class="admin-edit-user-section">
    <div class="edit-user-container">
        <div class="edit-user-header">
            <a href="<?= BASE_URL ?>/admin/users" class="back-link">← Back to Users</a>
            <h1>Edit User</h1>
            <p class="user-info"><?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?> (ID: <?= $user['id'] ?>)</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div>
                    <strong>Please fix the following errors:</strong>
                    <ul class="error-list">
                        <?php foreach ($errors as $message): ?>
                            <li><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= BASE_URL ?>/admin/users/update/<?= $user['id'] ?>" class="edit-user-form">
            <div class="form-group">
                <label for="name" class="form-label">
                    <span class="label-text">Full Name</span>
                    <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="form-control"
                    value="<?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?>" 
                    required
                    placeholder="Enter full name"
                >
            </div>

            <div class="form-group">
                <label for="email" class="form-label">
                    <span class="label-text">Email Address</span>
                    <span class="required">*</span>
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-control"
                    value="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>" 
                    required
                    placeholder="user@example.com"
                >
                <small class="form-text">Used for login and notifications</small>
            </div>

            <div class="form-group">
                <label for="role" class="form-label">
                    <span class="label-text">User Role</span>
                    <span class="required">*</span>
                </label>
                <select id="role" name="role" class="form-control">
                    <option value="">-- Select Role --</option>
                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin (Full access)</option>
                    <option value="staff" <?= $user['role'] === 'staff' ? 'selected' : '' ?>>Staff (Moderation)</option>
                    <option value="office" <?= $user['role'] === 'office' ? 'selected' : '' ?>>Office (Office management)</option>
                    <option value="patient" <?= $user['role'] === 'patient' ? 'selected' : '' ?>>Patient (Booking)</option>
                </select>
            </div>

            <div class="form-group form-group-checkbox">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_active" value="1" <?= $user['is_active'] ? 'checked' : '' ?>>
                    <span class="checkbox-text">Account is active and can login</span>
                </label>
                <small class="form-text">Uncheck to deactivate the account</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Save Changes
                </button>
                <a href="<?= BASE_URL ?>/admin/users" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</section>
