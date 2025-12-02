$(function () {
    // Confirm dialog for destructive actions
    $(document).on('click', '[data-confirm]', function (event) {
        const message = $(this).data('confirm') || 'Are you sure?';
        if (!window.confirm(message)) {
            event.preventDefault();
        }
    });

    // Slot selection logic (for booking/rescheduling)
    $(document).on('click', '.calendar-slot.available', function (event) {
        event.preventDefault();
        const $button = $(this);
        const datetime = $button.data('datetime');
        const form = $button.closest('form');

        form.find('.calendar-slot.selected').removeClass('selected');
        $button.addClass('selected');
        form.find('input[name="slot_datetime"]').val(datetime);

        const label = $button.data('label') || datetime;
        let $message = form.find('.slot-selection-message');
        if ($message.length === 0) {
            $message = $('<div class="alert alert-custom mt-3 slot-selection-message" role="alert"></div>');
            form.append($message);
        } else {
            $message.removeClass('d-none');
        }
        $message.text(`Selected slot: ${label}. Ready to confirm.`);
    });
});
