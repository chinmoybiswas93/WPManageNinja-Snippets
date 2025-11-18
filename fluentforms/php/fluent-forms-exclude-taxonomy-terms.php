<?php

/**
 * Exclude specific taxonomy terms from Fluent Forms taxonomy fields
 * This filter modifies the get_terms() arguments before terms are fetched
 */
add_filter('fluentform/post_integrations_terms_args', function ($termsArgs, $data, $form) {
    // Only apply to form ID 231
    if ($form->id != 231) {
        return $termsArgs;
    }

    // Example: Exclude specific category terms by ID
    if (isset($termsArgs['taxonomy']) && $termsArgs['taxonomy'] === 'category') {
        // Replace these IDs with the actual term IDs you want to exclude
        $excludeTermIds = [24, 26]; // IDs of terms to exclude
        $termsArgs['exclude'] = $excludeTermIds;
    }

    return $termsArgs;
}, 10, 3);
