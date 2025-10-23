<?php
/**
 * Modifying the HTML of the Rangeslider field
 */

add_filter('fluentform/rendering_field_html_rangeslider', function ($html, $data, $form) {
    $modified_html = '<div class="custom-rangeslider-wrapper">' . $html . '</div>';
    return $modified_html;
}, 10, 3);