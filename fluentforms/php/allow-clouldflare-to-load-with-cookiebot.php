<?php
/**
 * Allow Cloudflare Turnstile to load when Cookiebot is active.
 * Add via a custom plugin or theme functions.php
 */
add_filter('script_loader_tag', function ($tag, $handle, $src) {
    // Fluent Forms Turnstile handles (regular + conversational)
    $turnstileHandles = ['turnstile', 'turnstile_conv'];

    if (!in_array($handle, $turnstileHandles, true)) {
        return $tag;
    }

    // Avoid duplicate attribute
    if (strpos($tag, 'data-cookieconsent=') !== false) {
        return $tag;
    }

    // Inject Cookiebot ignore flag
    return str_replace('<script ', '<script data-cookieconsent="ignore" ', $tag);
}, 10, 3);