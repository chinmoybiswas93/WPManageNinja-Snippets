<?php

/**
 * Remove aria-label from form field labels before rendering
 */
add_filter('fluentform/rendering_field_html_input_text', 'remove_aria_label_from_field', 10, 3);
add_filter('fluentform/rendering_field_html_textarea', 'remove_aria_label_from_field', 10, 3);
add_filter('fluentform/rendering_field_html_select', 'remove_aria_label_from_field', 10, 3);
add_filter('fluentform/rendering_field_html_input_email', 'remove_aria_label_from_field', 10, 3);
add_filter('fluentform/rendering_field_html_input_number', 'remove_aria_label_from_field', 10, 3);
add_filter('fluentform/rendering_field_html_input_url', 'remove_aria_label_from_field', 10, 3);
add_filter('fluentform/rendering_field_html_input_date', 'remove_aria_label_from_field', 10, 3);

function remove_aria_label_from_field($html, $data, $form)
{
    // Remove aria-label attribute from label tags
    $html = preg_replace('/<label([^>]*)\s+aria-label=["\'][^"\']*["\']([^>]*)>/i', '<label$1$2>', $html);
    return $html;
}
