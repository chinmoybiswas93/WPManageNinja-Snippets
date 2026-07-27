<?php

/**
 * Create an active FluentAffiliate profile for a Tutor LMS user.
 *
 * Existing affiliate profiles are left unchanged.
 *
 * @param int $user_id WordPress user ID.
 * @return void
 */
function srm_maybe_create_tutor_affiliate($user_id)
{
    $user_id = absint($user_id);

    if (
        !$user_id ||
        !class_exists('\FluentAffiliate\App\Models\Affiliate') ||
        !class_exists('\FluentAffiliate\App\Models\User')
    ) {
        return;
    }

    $existing_affiliate = \FluentAffiliate\App\Models\Affiliate::where('user_id', $user_id)->first();

    if ($existing_affiliate) {
        return;
    }

    $affiliate_user = \FluentAffiliate\App\Models\User::find($user_id);

    if (!$affiliate_user) {
        return;
    }

    $affiliate_user->syncAffiliateProfile(
        array(
            'status' => 'active',
            'rate_type' => 'default',
        )
    );
}

/**
 * Create an affiliate when an instructor is created as approved.
 */
add_action('tutor_add_new_instructor_after', 'srm_maybe_create_tutor_affiliate', 10, 1);

/**
 * Create an affiliate when a pending instructor is approved.
 */
add_action('tutor_after_approved_instructor', 'srm_maybe_create_tutor_affiliate', 10, 1);

/**
 * Support instructor registration when Tutor is configured for auto-approval.
 */
function srm_maybe_create_auto_approved_instructor_affiliate($user_id)
{
    if ('approved' === get_user_meta($user_id, '_tutor_instructor_status', true)) {
        srm_maybe_create_tutor_affiliate($user_id);
    }
}
add_action('tutor_new_instructor_after', 'srm_maybe_create_auto_approved_instructor_affiliate', 10, 1);

/**
 * Create an affiliate after a student completes a course enrollment.
 *
 * @param int $course_id   Tutor course ID.
 * @param int $user_id     Student's WordPress user ID.
 * @param int $enrolled_id Tutor enrollment ID.
 */
function srm_create_student_affiliate_after_enrollment($course_id, $user_id, $enrolled_id)
{
    srm_maybe_create_tutor_affiliate($user_id);
}
add_action('tutor_after_enrolled', 'srm_create_student_affiliate_after_enrollment', 10, 3);
