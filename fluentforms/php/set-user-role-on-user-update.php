<?php
/**
 * Set user role on user update fluent forms
 */

add_action('fluentform/user_update_completed', function($userId, $feed, $entry, $form) {
    $parsedEntry = \FluentForm\App\Modules\Form\FormDataParser::parseFormEntry($entry, $form, null, true);
    $formData = $parsedEntry->user_inputs ?? [];
    
    // Now access your form fields with the name attribute like 'dropdown'
    $yourFieldValue = $formData['dropdown'] ?? '';
    
    // Set role conditionally
    if ($yourFieldValue === 'Author') {
        $user = new \WP_User($userId);
        $user->set_role('author');
    } else if ($yourFieldValue === 'Customer') {
        $user = new \WP_User($userId);
        $user->set_role('customer');
    }  else if ($yourFieldValue === 'Subscriber') {
        $user = new \WP_User($userId);
        $user->set_role('subscriber');
    }
}, 10, 4);