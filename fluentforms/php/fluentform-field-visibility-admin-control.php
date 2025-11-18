<?php

/**
 * Hide numeric_field_1 for non-admin users on form ID 232
 */
add_filter('fluentform/before_render_item', function ($item, $form) {
    // Check if this is form ID 232
    if ($form->id != 232) {
        return $item;
    }

    // Check if this is the numeric_field_1 field
    if (isset($item['attributes']['name']) && $item['attributes']['name'] === 'numeric_field_1') {
        // Check if user is not an admin
        if (!current_user_can('administrator')) {
            // Add ff-hidden class to container_class
            $currentClass = isset($item['settings']['container_class']) ? $item['settings']['container_class'] : '';
            $item['settings']['container_class'] = trim($currentClass . ' ff-hidden');
        }
    }

    return $item;
}, 10, 2);
