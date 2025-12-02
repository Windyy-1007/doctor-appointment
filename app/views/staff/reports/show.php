<section class="panel">
    <h2>Report #<?= $report['id'] ?> &mdash; <?= htmlspecialchars($report['title'], ENT_QUOTES, 'UTF-8') ?></h2>

    <?php if (!empty($flash)): ?>
        <div class="form-success">
            <?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <p><strong>Category:</strong> <?= htmlspecialchars($report['category'], ENT_QUOTES, 'UTF-8') ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($report['status'], ENT_QUOTES, 'UTF-8') ?></p>
    <p><strong>Created:</strong> <?= htmlspecialchars($report['created_at'], ENT_QUOTES, 'UTF-8') ?></p>
    <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($report['description'], ENT_QUOTES, 'UTF-8')) ?></p>

    <?php if (!empty($target['details'])): ?>
        <div class="panel">
            <h3>Target Details</h3>
            <p><strong>Type:</strong> <?= htmlspecialchars($target['type'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php foreach ($target['details'] as $label => $value): ?>
                <p><strong><?= ucfirst(str_replace('_', ' ', $label)) ?>:</strong> <?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>/staff/reports/set-status/<?= $report['id'] ?>">
        <label for="status">Update Status</label>
        <select id="status" name="status">
            <?php foreach (['open', 'in_progress', 'resolved', 'closed'] as $statusOption): ?>
                <option value="<?= $statusOption ?>" <?= $report['status'] === $statusOption ? 'selected' : '' ?>><?= ucfirst(str_replace('_', ' ', $statusOption)) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Save status</button>
    </form>

    <p><a href="<?= BASE_URL ?>/staff/reports">Back to reports list</a></p>
</section>
