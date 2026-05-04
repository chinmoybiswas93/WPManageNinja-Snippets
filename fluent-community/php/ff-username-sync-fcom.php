<?php

/**
 * FluentCommunity Username Sync from Fluent Forms
 *
 * Use this snippet to update the FluentCommunity public username
 * after a Fluent Forms submission.
 *
 * This is the safest approach if the Community @username is currently
 * being generated from the email address.
 *
 * What this snippet does:
 * - runs after a specific Fluent Form is submitted
 * - reads the submitted email and username fields
 * - finds the created WordPress user by email
 * - updates the FluentCommunity xprofile username
 *
 * Important:
 * - this updates the FluentCommunity username only
 * - this does not change the actual WordPress user_login
 *
 * Before using this snippet, replace:
 * - 123 with your Fluent Form ID
 * - your_email_field with the email field key
 * - your_username_field with the username field key
 */
add_action('fluentform/submission_inserted', function ($entryId, $formData, $form) {
    if ((int) $form->id !== 123) {
        return;
    }

    $emailFieldKey = 'your_email_field';
    $usernameFieldKey = 'your_username_field';

    $email = isset($formData[$emailFieldKey]) ? sanitize_email($formData[$emailFieldKey]) : '';
    $username = isset($formData[$usernameFieldKey]) ? sanitize_user($formData[$usernameFieldKey], true) : '';

    if (!$email || !$username) {
        return;
    }

    if (!class_exists('\FluentCommunity\App\Models\XProfile') || !class_exists('\FluentCommunity\App\Services\CustomSanitizer')) {
        return;
    }

    $user = get_user_by('email', $email);
    if (!$user) {
        return;
    }

    $username = \FluentCommunity\App\Services\CustomSanitizer::sanitizeUserName($username);

    if (!$username) {
        return;
    }

    $exists = \FluentCommunity\App\Models\XProfile::where('username', $username)
        ->where('user_id', '!=', $user->ID)
        ->exists();

    if ($exists) {
        return;
    }

    $xProfile = \FluentCommunity\App\Models\XProfile::where('user_id', $user->ID)->first();
    if (!$xProfile) {
        return;
    }

    $xProfile->username = $username;
    $xProfile->save();
}, 999, 3);
