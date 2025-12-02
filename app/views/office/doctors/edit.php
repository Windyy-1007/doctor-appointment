<section class="auth-box">
    <h2>Edit Doctor</h2>

    <?php if (!empty($errors)): ?>
        <div class="form-errors">
            <ul>
                <?php foreach ($errors as $message): ?>
                    <li><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>/office/doctors/update/<?= $doctor['id'] ?>">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($doctor['name'], ENT_QUOTES, 'UTF-8') ?>" required>

        <label for="specialty_id">Specialty</label>
        <select id="specialty_id" name="specialty_id" required>
            <option value="">Select specialty</option>
            <?php foreach ($specialties as $specialty): ?>
                <option value="<?= $specialty['id'] ?>" <?= $doctor['specialty_id'] == $specialty['id'] ? 'selected' : '' ?>><?= htmlspecialchars($specialty['name'], ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
        </select>

        <label for="bio">Bio</label>
        <textarea id="bio" name="bio"><?= htmlspecialchars($doctor['bio'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>

        <button type="submit">Update doctor</button>
    </form>
</section>
