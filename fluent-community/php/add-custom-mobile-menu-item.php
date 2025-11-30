<?php

/**
 * Add custom menu items to Fluent Community mobile bottom navigation
 * 
 * This filter allows you to add custom links to the mobile menu that appears
 * at the bottom of community pages. Each menu item requires a route or permalink,
 * an SVG icon, and optionally additional HTML content.
 * 
 * @file wp-content/themes/kadence/functions.php
 * @filter fluent_community/mobile_menu
 * @param array $items Array of existing menu items
 * @param object|null $xprofile Current user profile object or null
 * @param string $context Context string ('headless', 'wp', etc.)
 * @return array Modified array of menu items
 */
add_filter('fluent_community/mobile_menu', function ($items, $xprofile = null, $context = 'headless') {
    // Example: Add a "Notifications" link
    $items[] = [
        'route' => [
            'name' => 'notifications'
        ],
        'icon_svg' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M10 2C7.24 2 5 4.24 5 7v3.59l-1.29 1.29c-.63.63-.19 1.71.7 1.71h11.18c.89 0 1.33-1.08.7-1.71L15 10.59V7c0-2.76-2.24-5-5-5z" stroke="currentColor" stroke-width="1.5"/>
            <path d="M8 15c0 1.1.9 2 2 2s2-.9 2-2" stroke="currentColor" stroke-width="1.5"/>
        </svg>',
    ];

    return $items;
}, 10, 3);
