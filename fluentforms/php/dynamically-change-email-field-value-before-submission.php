<?php

/**
 * Dynamically Change Email Field Value Before Submission is Saved
 */
add_filter('fluentform/insert_response_data', function ($formData, $formId, $inputConfigs) {
    $targetFormId = 1;
    if ($formId != $targetFormId) {
        return $formData;
    }

    if (!isset($formData['email'])) { // change email field name if needed
        return $formData;
    }

    $currentEmail = isset($formData['email']) ? $formData['email'] : '';

    $newEmail = 'test@test.com'; // Replace with your email

    $formData['email'] = $newEmail;

    return $formData;
}, 10, 3);
