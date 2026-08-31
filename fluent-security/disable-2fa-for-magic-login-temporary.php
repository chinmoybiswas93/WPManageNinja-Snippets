<?php
/**
 * Plugin Name: FluentAuth Magic Login 2FA Bypass
 * Description: Temporarily skips Email 2FA after a verified FluentAuth Magic Login.
 */

add_action(
    'fluent_auth/login_attempts_checked',
    function ($user) {
        if (!class_exists('\FluentAuth\App\Helpers\Helper')) {
            return;
        }

        // FluentAuth sets this only after validating the Magic Login token.
        if (\FluentAuth\App\Helpers\Helper::getLoginMedia() !== 'magic_login') {
            return;
        }

        global $wp_filter;

        $hook = $wp_filter['fluent_auth/login_attempts_checked'] ?? null;

        if (!$hook instanceof \WP_Hook || empty($hook->callbacks[1])) {
            return;
        }

        foreach ($hook->callbacks[1] as $callback) {
            $function = $callback['function'] ?? null;

            if (
                !is_array($function) ||
                !isset($function[0], $function[1]) ||
                !is_object($function[0])
            ) {
                continue;
            }

            $is_fluentauth_2fa = is_a(
                $function[0],
                'FluentAuth\App\Hooks\Handlers\TwoFaHandler'
            );

            if ($is_fluentauth_2fa && $function[1] === 'maybe2FaRedirect') {
                remove_action(
                    'fluent_auth/login_attempts_checked',
                    $function,
                    1
                );
            }
        }
    },
    0,
    1
);