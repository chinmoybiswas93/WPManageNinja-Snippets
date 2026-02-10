<?php
add_filter('fluentform/email_template_footer_text', function ($footerText, $form, $notification) {
    $option = get_option('_fluentform_global_form_settings');
    $customFooter = isset($option['misc']['email_footer_text']) ? trim($option['misc']['email_footer_text']) : '';
    if (empty($customFooter)) {
        return '';
    }
    return $footerText;
}, 10, 3);
