<?php

add_action('fluent_booking/author_landing_head', function () {
    $is_reschedule_page =
        isset($_GET['fluent-booking'], $_GET['type'], $_GET['meeting_hash']) &&
        sanitize_text_field(wp_unslash($_GET['fluent-booking'])) === 'booking' &&
        sanitize_text_field(wp_unslash($_GET['type'])) === 'reschedule';

    if (!$is_reschedule_page) {
        return;
    }
    ?>
    <style>
        .fcal_booking_form_wrap .fcal_booking_form .fcal_form_item button {
            background: #f80000 !important;
            color: #ffffff !important;
        }
    </style>
    <?php
});