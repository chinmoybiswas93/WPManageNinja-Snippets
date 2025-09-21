<?php

/**
 * Post Taxonomy SmartCodes for FluentForm
 * 
 * This creates custom smartcodes that populate current post's taxonomy data
 * (category and vendor) for forms used on single post pages.
 */


/**
 * Add post taxonomy smartcodes to FluentForm editor
 */
add_filter('fluentform/editor_shortcodes', function ($smartCodes) {
    // Add post taxonomy smartcodes
    $smartCodes[0]['shortcodes']['{post_categories}'] = 'Post: Current Post Categories (comma-separated)';
    $smartCodes[0]['shortcodes']['{post_vendor}'] = 'Post: Current Post Vendor (comma-separated)';

    return $smartCodes;
});

/**
 * Get current post's categories (comma-separated)
 */
function fluentform_get_current_post_categories()
{
    global $post;
    
    if (!$post || !is_singular()) {
        return '';
    }
    
    $categories = get_the_terms($post->ID, 'category');
    
    if (is_wp_error($categories) || empty($categories)) {
        return '';
    }
    
    $category_names = [];
    foreach ($categories as $category) {
        $category_names[] = $category->name;
    }
    
    return implode(', ', $category_names);
}

/**
 * Get current post's vendor taxonomy (comma-separated)
 * Assumes 'vendor' is a custom taxonomy created with MetaBox
 */
function fluentform_get_current_post_vendor()
{
    global $post;
    
    if (!$post || !is_singular()) {
        return '';
    }
    
    // Get vendor terms - adjust 'vendor' to your actual taxonomy name if different
    $vendors = get_the_terms($post->ID, 'vendor');
    
    if (is_wp_error($vendors) || empty($vendors)) {
        return '';
    }
    
    $vendor_names = [];
    foreach ($vendors as $vendor) {
        $vendor_names[] = $vendor->name;
    }
    
    return implode(', ', $vendor_names);
}

// Register transformation callbacks for form builder (editor)

// Post Categories
add_filter('fluentform/editor_shortcode_callback_post_categories', function ($value, $form) {
    return fluentform_get_current_post_categories();
}, 10, 2);

// Post Vendor
add_filter('fluentform/editor_shortcode_callback_post_vendor', function ($value, $form) {
    return fluentform_get_current_post_vendor();
}, 10, 2);