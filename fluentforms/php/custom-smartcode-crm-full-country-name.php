<?php

// Fluent Forms smartcode: {fluentcrm.country_full} -> full country name
add_filter('fluentform/editor_shortcode_callback_group_fluentcrm', function ($parsedValue, $form, $keys) {
    // We only handle {fluentcrm.country_full}
    if (empty($keys) || $keys[0] !== 'country_full') {
        return $parsedValue;
    }

    if (!function_exists('FluentCrmApi')) {
        return $parsedValue;
    }

    // Get current FluentCRM contact attached to this submission
    $contact = FluentCrmApi('contacts')->getCurrentContact(true, true);
    if (!$contact || empty($contact->country)) {
        return $parsedValue;
    }

    // Map country code -> full name using FluentCRM's country list
    $countries = apply_filters('fluent_crm/countries', []);
    foreach ($countries as $country) {
        if (!empty($country['code']) && $country['code'] === $contact->country) {
            return $country['title'];
        }
    }

    return $parsedValue;
}, 10, 3);

add_filter('fluentform/editor_shortcodes', function ($smartcodes) {
    if (!defined('FLUENTCRM')) {
        return $smartcodes;
    }

    // Add to the first group in the editor list
    if (!empty($smartcodes[0]['shortcodes'])) {
        $smartcodes[0]['shortcodes']['{fluentcrm.country_full}'] = 'FluentCRM Country (full name)';
    }

    return $smartcodes;
}, 110, 1);