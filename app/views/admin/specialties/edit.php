<section class="admin-specialty-form-section">
    <div class="form-container">
        <div class="form-header">
            <a href="<?= BASE_URL ?>/admin/specialties" class="back-link">← Back to Specialties</a>
            <h1>Edit Specialty</h1>
            <p class="form-subtext"><?= htmlspecialchars($specialty['name'], ENT_QUOTES, 'UTF-8') ?> (ID: <?= $specialty['id'] ?>)</p>
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

        <form method="post" action="<?= BASE_URL ?>/admin/specialties/update/<?= $specialty['id'] ?>" class="specialty-form">
            <div class="form-group">
                <label for="name" class="form-label">
                    <span class="label-text">Specialty Name</span>
                    <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="form-control"
                    value="<?= htmlspecialchars($specialty['name'], ENT_QUOTES, 'UTF-8') ?>" 
                    required
                    placeholder="e.g., Cardiology, Pediatrics"
                >
            </div>

            <div class="form-group">
                <label for="description" class="form-label">
                    <span class="label-text">Description</span>
                </label>
                <textarea 
                    id="description" 
                    name="description" 
                    class="form-control"
                    rows="4"
                    placeholder="Describe this medical specialty..."
                ><?= htmlspecialchars($specialty['description'], ENT_QUOTES, 'UTF-8') ?></textarea>
                <small class="form-text">Brief description of the specialty</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Save Changes
                </button>
                <a href="<?= BASE_URL ?>/admin/specialties" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</section>
