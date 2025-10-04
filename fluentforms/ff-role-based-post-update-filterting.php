<?php
/**
 * FluentForm Role-Based Post Update Filtering
 * 
 * Editors: Can see and update all posts
 * Authors and other roles: Can only see and update their own posts
 * 
 * Modify the post query parameters dynamically based on user role
 */
function fluentform_role_based_post_filtering($extraParams, $data, $form)
{

    if ($form->id != 1) {
        return $extraParams;
    }

    $currentUser = wp_get_current_user();

    if (!$currentUser->ID) {
        return $extraParams . '&author=0';
    }

    if (current_user_can('edit_others_posts')) {
        $cleanParams = preg_replace('/(&|\?)author=\d+/', '', $extraParams);
        return $cleanParams;
    } else {
        $cleanParams = preg_replace('/(&|\?)author=\d+/', '', $extraParams);
        return $cleanParams . '&author=' . $currentUser->ID;
    }
}
add_filter('fluentform/post_selection_posts_query_args', 'fluentform_role_based_post_filtering', 10, 3);
