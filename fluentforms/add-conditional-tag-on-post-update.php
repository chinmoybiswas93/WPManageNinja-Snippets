<?php

/**
 * Add WordPress tag to post after FluentForm post integration success
 */
function add_tag_after_fluentform_post_integration($postId, $postData, $entryId, $form, $feed) {
    // Check if post ID is valid
    if (!$postId || !is_numeric($postId)) {
        return;
    }
    
    // Use FluentForm method to get entry form data
    $entry = wpFluent()->table('fluentform_submissions')->where('id', $entryId)->first();
    $formData = json_decode($entry->response, true);

    // Define the tag(s) you want to assign based on radio button value
    $tags_to_assign = array();
    
    // Check the radio input with name 'input_radio' value and assign appropriate tag
    if (isset($formData['input_radio'])) { // Update the radio field name here
        if ($formData['input_radio'] === 'public') { // Update the radio value name here
            $tags_to_assign[] = 'Public';
        } elseif ($formData['input_radio'] === 'private') { // Update the radio field value here
            $tags_to_assign[] = 'Private';
        }
    }
    
    // Only proceed if we have tags to assign
    if (!empty($tags_to_assign)) {
        // Assign the tags to the post
        wp_set_post_tags($postId, $tags_to_assign, true); // true means append to existing tags
    } 
}

// Hook the function to the FluentForm action
add_action('fluentform/post_integration_success', 'add_tag_after_fluentform_post_integration', 10, 5);