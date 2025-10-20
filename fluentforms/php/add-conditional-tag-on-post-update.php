<?php
/**
 * Add WordPress tag to post after FluentForm post integration success
 */
function add_tag_after_fluentform_post_integration($postId, $postData, $entryId, $form, $feed)
{
    $target_form_ids = [8, 9, 10, 11, 12];

    if (!in_array($form->id, $target_form_ids) || empty($postId)) {
        return;
    }

    $entry = wpFluent()->table('fluentform_submissions')->where('id', $entryId)->first();
    $formData = json_decode($entry->response, true);

    $tags_to_assign = array();

    if (isset($formData['input_radio'])) {
        if ($formData['input_radio'] === 'public') {
            $tags_to_assign[] = get_term(44, 'post_tag');
        } elseif ($formData['input_radio'] === 'private') {
            $tags_to_assign[] = get_term(43, 'post_tag');
        }
    }

    if (!empty($tags_to_assign)) {
        $valid_tags = array_filter($tags_to_assign, function($tag) {
            return $tag && !is_wp_error($tag);
        });
        
        if (!empty($valid_tags)) {
            $tag_names = array_map(function($tag) {
                return $tag->name;
            }, $valid_tags);
            
            wp_set_post_tags($postId, $tag_names, true);
        }
    }
}

add_action('fluentform/post_integration_success', 'add_tag_after_fluentform_post_integration', 10, 5);
