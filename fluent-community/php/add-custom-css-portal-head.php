<?php
// Hide Paywalls tab in FluentCommunity course editor
add_action('fluent_community/portal_head', function () {
    echo '<style id="fcom-hide-paywalls-tab">
        .fcom_full_editor_header_wrap ul li:nth-child(2) {
            display: none !important;
        }
    </style>';
}, 999);
