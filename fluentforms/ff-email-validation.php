<?php
add_filter('fluentform/validate_input_item_input_email', function ($errorMessage, $field, $formData, $fields, $form) {
    $target_form_id = 149;

    // Only apply to form 149
    if ($form->id != $target_form_id) {
        return $errorMessage;
    }

    // Only validate the confirmation email field (email_1), skip the first field
    if ($field['name'] !== 'email_1') {
        return $errorMessage; 
    }

    // Check if both email fields exist and are not empty
    if (empty($formData['email']) || empty($formData['email_1'])) {
        return $errorMessage;
    }

    if ($formData['email'] !== $formData['email_1']) {
        return 'Error. The two emails are not the same. Please try again.'; // Return string, not array
    }

    return '';
}, 10, 5);