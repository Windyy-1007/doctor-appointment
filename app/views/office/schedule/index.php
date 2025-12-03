<?php $this->title = 'Schedule Overview | MediBook'; ?>

<section class="section-card mb-4">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h2 class="h4 mb-1">Schedule Overview</h2>
            <p class="text-muted mb-0">Manage working slots, cancel appointments, and mark attendance in one screen.</p>
        </div>
        <span class="badge bg-dark text-white">Office View</span>
    </div>

    <?php if (!empty($flash)): ?>
        <div class="form-success mb-3"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form class="row g-3" method="post" action="<?= BASE_URL ?>/office/schedule/addSlot">
        <div class="col-md-4">
            <label class="form-label" for="slot_doctor">Doctor</label>
            <select class="form-select" id="slot_doctor" name="doctor_id" required>
                <option value="">Select doctor</option>
                <?php foreach ($doctors as $doctor): ?>
                    <option value="<?= htmlspecialchars($doctor['id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($doctor['name'], ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="slot_datetime">Slot time</label>
            <input class="form-control" type="datetime-local" id="slot_datetime" name="slot_datetime" required>
        </div>
        <div class="col-md-3">
            <label class="form-label" for="slot_note">Note (optional)</label>
            <input class="form-control" type="text" id="slot_note" name="note" placeholder="e.g. extra clinic hours">
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button class="btn btn-primary-custom w-100" type="submit">Add slot</button>
        </div>
    </form>
</section>

<?php if (empty($days)): ?>
    <section class="section-card">
        <p class="text-muted mb-0">No schedule data is available for the next two weeks.</p>
    </section>
<?php else: ?>
    <?php foreach ($days as $day): ?>
        <section class="section-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h3 class="h5 mb-0"><?= htmlspecialchars($day['label']) ?></h3>
                    <p class="text-muted small mb-0">Slots for <?= htmlspecialchars($day['date'] ?? $day['label']) ?></p>
                </div>
            </div>

            <?php foreach ($day['doctors'] as $doctor): ?>
                <article class="office-schedule-card mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="h6 mb-0">Dr. <?= htmlspecialchars($doctor['name']) ?></h4>
                        <?php if (count($doctor['slots']) > 4): ?>
                            <button type="button" class="slot-toggle" aria-expanded="false" aria-label="Show slots">
                                <span class="slot-toggle__icon">▶</span>
                            </button>
                        <?php endif; ?>
                    </div>

                    <div class="slot-list <?= count($doctor['slots']) > 4 ? 'slot-list--collapsed' : '' ?>">
                        <?php foreach ($doctor['slots'] as $slot): ?>
                            <div class="slot-row slot-row--<?= htmlspecialchars($slot['status'] ?? 'available') ?> mb-2">
                                <div>
                                    <strong class="slot-row__time"><?= htmlspecialchars($slot['time']) ?></strong>
                                    <?php if (!empty($slot['slot_entry']['note'])): ?>
                                        <p class="text-muted small mb-0"><?= htmlspecialchars($slot['slot_entry']['note']) ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($slot['appointment'])): ?>
                                        <p class="text-muted small mb-0">Patient: <?= htmlspecialchars($slot['appointment']['patient_name'] ?? $slot['appointment']['patient_email'] ?? 'Patient') ?> · <?= htmlspecialchars($slot['appointment']['status']) ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex gap-2 flex-wrap slot-row__actions">
                                    <?php if (isset($slot['status']) && $slot['status'] === 'booked'): ?>
                                        <form method="post" action="<?= BASE_URL ?>/office/schedule/cancelAppointment">
                                            <input type="hidden" name="appointment_id" value="<?= htmlspecialchars($slot['appointment']['id']) ?>">
                                            <button class="btn btn-sm btn-outline-warning" type="submit">Cancel</button>
                                        </form>
                                        <form method="post" action="<?= BASE_URL ?>/office/schedule/markAttendance">
                                            <input type="hidden" name="appointment_id" value="<?= htmlspecialchars($slot['appointment']['id']) ?>">
                                            <button class="btn btn-sm btn-outline-success" type="submit">Mark attended</button>
                                        </form>
                                    <?php elseif (isset($slot['status']) && $slot['status'] === 'blocked'): ?>
                                        <form method="post" action="<?= BASE_URL ?>/office/schedule/unblockSlot">
                                            <input type="hidden" name="slot_id" value="<?= htmlspecialchars($slot['slot_entry']['id']) ?>">
                                            <button class="btn btn-sm btn-outline-secondary" type="submit">Unblock</button>
                                        </form>
                                    <?php else: ?>
                                        <form method="post" action="<?= BASE_URL ?>/office/schedule/blockSlot">
                                            <input type="hidden" name="doctor_id" value="<?= htmlspecialchars($slot['doctor_id']) ?>">
                                            <input type="hidden" name="slot_datetime" value="<?= htmlspecialchars($slot['datetime']) ?>">
                                            <button class="btn btn-sm btn-outline-danger" type="submit">Block slot</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endforeach; ?>
<?php endif; ?>

    <script>
        document.querySelectorAll('.slot-toggle').forEach(button => {
            const list = button.closest('article')?.querySelector('.slot-list');
            const icon = button.querySelector('.slot-toggle__icon');
            if (!list || !icon) {
                return;
            }

            const updateState = () => {
                const collapsed = list.classList.contains('slot-list--collapsed');
                button.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
                icon.textContent = collapsed ? '▶' : '▼';
            };

            updateState();

            button.addEventListener('click', () => {
                list.classList.toggle('slot-list--collapsed');
                updateState();
            });
        });
    </script>
