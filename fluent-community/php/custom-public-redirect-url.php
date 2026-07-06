<?php
add_action('fluent_community/portal/not_logged_in', function ($auth_url) {
    if (is_user_logged_in()) {
        return;
    }

    $login_page = home_url('/custom-url/'); // Change this to your custom login/signup page.

    $redirect_to = '/';
    $parsed_url = wp_parse_url($auth_url);

    if (!empty($parsed_url['query'])) {
        parse_str($parsed_url['query'], $query_args);

        if (!empty($query_args['redirect_to'])) {
            $redirect_to = wp_validate_redirect(
                sanitize_url($query_args['redirect_to']),
                '/'
            );
        }
    }

    wp_safe_redirect(add_query_arg('redirect_to', $redirect_to, $login_page));
    exit;
}, 1);