<?php

use FluentCrm\App\Models\Subscriber;
use FluentCommunity\App\Services\ProfileHelper;

function fcom_hello_from_profiles_shortcode()
{
    if (! is_user_logged_in()) {
        return '';
    }

    $user_id = get_current_user_id();
    $name    = '';

    // 1) FluentCRM contact
    if (class_exists('\FluentCrm\App\Models\Subscriber')) {
        $contact = Subscriber::where('user_id', $user_id)->first();
        if ($contact && ! empty($contact->first_name)) {
            $name = $contact->first_name;
        }
    }

    // 2) FluentCommunity XProfile
    if (! $name && class_exists('\FluentCommunity\App\Services\ProfileHelper')) {
        $profile = ProfileHelper::getProfile($user_id);
        if ($profile && ! empty($profile->display_name)) {
            $name = $profile->display_name;
        }
    }

    // 3) Fallback to WP user meta
    if (! $name) {
        $user  = wp_get_current_user();
        $name  = get_user_meta($user_id, 'first_name', true);
        if (! $name) {
            $name = $user->display_name;
        }
    }

    if (! $name) {
        return '';
    }

    return 'Hello ' . esc_html($name);
}
add_shortcode('fcom_hello_name', 'fcom_hello_from_profiles_shortcode');
