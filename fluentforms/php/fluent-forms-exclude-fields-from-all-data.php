<?php

/**
 * Fluent Forms - Exclude specific fields from {all_data} shortcode output
 * 
 * This filter hook allows you to exclude specific form fields from appearing in the
 * {all_data} shortcode output used in email notifications and entry views.
 * 
 * Usage:
 * - Add field names (name attributes) to the $excludeFields array
 * - Fields specified will be completely removed from the all_data table output
 * - The function rebuilds the HTML table without the excluded fields
 * 
 * @hook fluentform/all_data_shortcode_html
 * @param string $html The original HTML output of the all_data shortcode
 * @param array $formFields The form field definitions
 * @param array $inputLabels Array of field labels keyed by field name
 * @param object $response The form submission response object containing user_inputs
 * @return string Modified HTML with excluded fields removed
 * 
 * @example
 * To exclude fields, modify the $excludeFields array:
 * $excludeFields = ['field_name_1', 'field_name_2', 'unwanted_field'];
 */

add_filter('fluentform/all_data_shortcode_html', function ($html, $formFields, $inputLabels, $response) {
    // Fields to exclude
    $excludeFields = ['numeric_field_1', 'checkbox_1', 'input_radio_1'];

    // Remove from response and labels
    foreach ($excludeFields as $fieldName) {
        unset($response->user_inputs[$fieldName], $inputLabels[$fieldName]);
    }

    // Rebuild HTML
    $html = '<table class="ff_all_data" width="600" cellpadding="0" cellspacing="0"><tbody>';
    foreach ($inputLabels as $inputKey => $label) {
        if (array_key_exists($inputKey, $response->user_inputs) && '' !== $response->user_inputs[$inputKey]) {
            $data = $response->user_inputs[$inputKey];
            if (is_array($data) || is_object($data)) {
                continue;
            }
            $html .= '<tr class="field-label"><th style="padding: 6px 12px; background-color: #f8f8f8; text-align: left;"><strong>' . $label . '</strong></th></tr><tr class="field-value"><td style="padding: 6px 12px 12px 12px;">' . $data . '</td></tr>';
        }
    }
    $html .= '</tbody></table>';

    return $html;
}, 10, 4);
