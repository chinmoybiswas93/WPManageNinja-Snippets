<?php
/**
 * Rewrite Fluent Forms Double Opt-in links to /b2c-portal/.
 */
add_filter('wp_mail', function ($args) {
    $is_double_optin_email = false;

    foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 12) as $frame) {
        if (
            ($frame['class'] ?? '') === 'FluentFormPro\\classes\\DoubleOptin' &&
            ($frame['function'] ?? '') === 'processOnSubmission'
        ) {
            $is_double_optin_email = true;
            break;
        }
    }

    if (!$is_double_optin_email || empty($args['message'])) {
        return $args;
    }

    $args['message'] = preg_replace_callback(
        '#https?://[^"\s<]+/\?ff_landing=[^"\s<&]+(?:&|&amp;)entry_confirmation=[^"\s<]+#',
        function ($match) {
            $query = wp_parse_url(
                html_entity_decode($match[0], ENT_QUOTES, 'UTF-8'),
                PHP_URL_QUERY
            );

            return esc_url(home_url('/b2c-portal/?' . $query));
        },
        $args['message']
    );

    return $args;
});