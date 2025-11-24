<?php
function ff_email_exists_in_form($form_id, $email_field_name, $email_to_check)
{
    if (!$form_id || !$email_field_name || !$email_to_check) {
        return false;
    }

    // Primary check (fast): fluentform_entry_details table
    $exists = \FluentForm\App\Models\EntryDetails::where('form_id', $form_id)
        ->where('field_name', $email_field_name)
        ->where('field_value', $email_to_check)
        ->exists();

    if ($exists) {
        return true;
    }

    // Fallback (used for payment/pending entries): raw submissions JSON
    $submissions = \FluentForm\App\Models\Submission::where('form_id', $form_id)->pluck('response');
    foreach ($submissions as $responseJson) {
        $response = json_decode($responseJson, true);
        if ($email_to_check === \FluentForm\Framework\Helpers\ArrayHelper::get($response, $email_field_name)) {
            return true;
        }
    }

    return false;
}

add_filter('fluentform/validate_input_item_input_email', function ($errorMessage, $field, $formData, $fields, $form) {
    $target_form_id = 244;

    // Respect existing validation errors.
    if (!empty($errorMessage)) {
        return $errorMessage;
    }

    // Only apply to the desired form.
    if ((int)$form->id !== $target_form_id) {
        return $errorMessage;
    }

    // Only validate the confirmation email field.
    if ($field['name'] !== 'email') {
        return $errorMessage;
    }

    $email_to_check = isset($formData['email']) ? sanitize_email($formData['email']) : '';

    if (!$email_to_check) {
        return $errorMessage;
    }

    // Check whether the email already exists in previous submissions (using the primary email field name `email`).
    if (ff_email_exists_in_form($target_form_id, 'email', $email_to_check)) {
        return __('This email address has already been used. Please use a different email.', 'fluent-forms');
    }

    return '';
}, 10, 5);
