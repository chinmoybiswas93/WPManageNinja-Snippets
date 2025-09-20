<?php
/**
 * FluentCRM User Tags SmartCode for FluentForm
 * 
 * This creates custom smartcodes that populate FluentCRM contact data
 * for logged-in users in FluentForm form fields.
 */

// Check if both plugins are active before adding hooks
function fluentcrm_tags_check_plugins()
{
    return class_exists('FluentForm\App\App') && class_exists('FluentCrm\App\Models\Tag');
}

// Only proceed if both plugins are active
if (!fluentcrm_tags_check_plugins()) {
    return;
}

/**
 * Add FluentCRM user-specific smartcodes to FluentForm editor
 */
add_filter('fluentform/editor_shortcodes', function ($smartCodes) {
    // Add user-specific FluentCRM smartcodes
    $smartCodes[0]['shortcodes']['{fluentcrm_user_lists}'] = 'FluentCRM: Current User Lists (comma-separated)';
    $smartCodes[0]['shortcodes']['{fluentcrm_user_tags}'] = 'FluentCRM: Current User Tags (comma-separated)';
    $smartCodes[0]['shortcodes']['{fluentcrm_user_status}'] = 'FluentCRM: Current User Status';
    $smartCodes[0]['shortcodes']['{fluentcrm_user_contact_type}'] = 'FluentCRM: Current User Contact Type';
    $smartCodes[0]['shortcodes']['{fluentform_last_submission_id}'] = 'FluentForm: User Last Submission ID';
    $smartCodes[0]['shortcodes']['{fluentform_last_form_title}'] = 'FluentForm: User Last Form Title';

    return $smartCodes;
});

/**
 * Add FluentCRM user-specific smartcodes for email/confirmation settings
 */
add_filter('fluentform/all_editor_shortcodes', function ($data) {
    $userCRMShortCodes = [
        'title' => 'FluentCRM User Data',
        'shortcodes' => [
            '{fluentcrm_user_lists}' => 'Current User Lists (comma-separated)',
            '{fluentcrm_user_tags}' => 'Current User Tags (comma-separated)',
            '{fluentcrm_user_status}' => 'Current User Status',
            '{fluentcrm_user_contact_type}' => 'Current User Contact Type',
            '{fluentform_last_submission_id}' => 'Last Submission ID',
            '{fluentform_last_form_title}' => 'Last Form Title',
        ]
    ];
    $data[] = $userCRMShortCodes;
    return $data;
});

/**
 * Get FluentCRM subscriber data for current logged-in user
 */
function fluentcrm_get_current_user_subscriber()
{
    // Check if user is logged in
    if (!is_user_logged_in()) {
        return null;
    }

    $current_user = wp_get_current_user();
    $email = $current_user->user_email;

    if (empty($email)) {
        return null;
    }

    try {
        // Find subscriber by email
        $subscriber = \FluentCrm\App\Models\Subscriber::where('email', $email)->first();
        return $subscriber;
    } catch (Exception $e) {
        error_log('FluentCRM Subscriber Query Error: ' . $e->getMessage());
        return null;
    }
}

/**
 * Get current user's FluentCRM lists
 */
function fluentcrm_get_current_user_lists()
{
    $subscriber = fluentcrm_get_current_user_subscriber();

    if (!$subscriber) {
        return [];
    }

    try {
        // Get subscriber's lists
        $user_lists = $subscriber->lists()->orderBy('title', 'ASC')->get();
        $list_titles = [];

        foreach ($user_lists as $list) {
            $list_titles[] = $list->title;
        }

        return $list_titles;
    } catch (Exception $e) {
        error_log('FluentCRM User Lists Error: ' . $e->getMessage());
        return [];
    }
}

/**
 * Get current user's FluentCRM tags
 */
function fluentcrm_get_current_user_tags()
{
    $subscriber = fluentcrm_get_current_user_subscriber();

    if (!$subscriber) {
        return [];
    }

    try {
        // Get subscriber's tags
        $user_tags = $subscriber->tags()->orderBy('title', 'ASC')->get();
        $tag_titles = [];

        foreach ($user_tags as $tag) {
            $tag_titles[] = $tag->title;
        }

        return $tag_titles;
    } catch (Exception $e) {
        error_log('FluentCRM User Tags Error: ' . $e->getMessage());
        return [];
    }
}

/**
 * Get current user's last FluentForm submission
 */
function fluentform_get_current_user_last_submission()
{
    if (!is_user_logged_in()) {
        return null;
    }

    $current_user = wp_get_current_user();
    $email = $current_user->user_email;
    $user_id = $current_user->ID;

    if (empty($email) || !class_exists('FluentForm\App\Models\Submission')) {
        return null;
    }

    try {
        // First try to find by user_id if user is logged in (most reliable)
        $submission = \FluentForm\App\Models\Submission::where('user_id', $user_id)
            ->orderBy('created_at', 'DESC')
            ->first();

        // If no submission found by user_id, fallback to email search
        if (!$submission) {
            $submission = \FluentForm\App\Models\Submission::where('response', 'LIKE', '%"' . $email . '"%')
                ->orderBy('created_at', 'DESC')
                ->first();
        }

        return $submission;
    } catch (Exception $e) {
        error_log('FluentForm Last Submission Error: ' . $e->getMessage());
        return null;
    }
}

// Register transformation callbacks for form builder (editor)

// Current User Lists (comma-separated)
add_filter('fluentform/editor_shortcode_callback_fluentcrm_user_lists', function ($value, $form) {
    $user_lists = fluentcrm_get_current_user_lists();
    return implode(', ', $user_lists);
}, 10, 2);

// Current User Tags (comma-separated)
add_filter('fluentform/editor_shortcode_callback_fluentcrm_user_tags', function ($value, $form) {
    $user_tags = fluentcrm_get_current_user_tags();
    return implode(', ', $user_tags);
}, 10, 2);

// Current User Status
add_filter('fluentform/editor_shortcode_callback_fluentcrm_user_status', function ($value, $form) {
    $subscriber = fluentcrm_get_current_user_subscriber();
    return $subscriber ? $subscriber->status : '';
}, 10, 2);

// Current User Contact Type
add_filter('fluentform/editor_shortcode_callback_fluentcrm_user_contact_type', function ($value, $form) {
    $subscriber = fluentcrm_get_current_user_subscriber();
    return $subscriber ? $subscriber->contact_type : '';
}, 10, 2);

// FluentForm Last Submission ID
add_filter('fluentform/editor_shortcode_callback_fluentform_last_submission_id', function ($value, $form) {
    $submission = fluentform_get_current_user_last_submission();
    return $submission ? (string) $submission->id : '';
}, 10, 2);

// FluentForm Last Form Title
add_filter('fluentform/editor_shortcode_callback_fluentform_last_form_title', function ($value, $form) {
    $submission = fluentform_get_current_user_last_submission();
    if ($submission && class_exists('FluentForm\App\Models\Form')) {
        try {
            $form_data = \FluentForm\App\Models\Form::find($submission->form_id);
            return $form_data ? $form_data->title : '';
        } catch (Exception $e) {
            error_log('FluentForm Form Title Error: ' . $e->getMessage());
            return '';
        }
    }
    return '';
}, 10, 2);

// Register transformation callbacks for email/confirmation settings

// Current User Lists (comma-separated)
add_filter('fluentform/shortcode_parser_callback_fluentcrm_user_lists', function ($value, $parser) {
    $user_lists = fluentcrm_get_current_user_lists();
    return implode(', ', $user_lists);
}, 10, 2);

// Current User Tags (comma-separated)
add_filter('fluentform/shortcode_parser_callback_fluentcrm_user_tags', function ($value, $parser) {
    $user_tags = fluentcrm_get_current_user_tags();
    return implode(', ', $user_tags);
}, 10, 2);

// Current User Status
add_filter('fluentform/shortcode_parser_callback_fluentcrm_user_status', function ($value, $parser) {
    $subscriber = fluentcrm_get_current_user_subscriber();
    return $subscriber ? $subscriber->status : '';
}, 10, 2);

// Current User Contact Type
add_filter('fluentform/shortcode_parser_callback_fluentcrm_user_contact_type', function ($value, $parser) {
    $subscriber = fluentcrm_get_current_user_subscriber();
    return $subscriber ? $subscriber->contact_type : '';
}, 10, 2);

// FluentForm Last Submission ID
add_filter('fluentform/shortcode_parser_callback_fluentform_last_submission_id', function ($value, $parser) {
    $submission = fluentform_get_current_user_last_submission();
    return $submission ? (string) $submission->id : '';
}, 10, 2);

// FluentForm Last Form Title
add_filter('fluentform/shortcode_parser_callback_fluentform_last_form_title', function ($value, $parser) {
    $submission = fluentform_get_current_user_last_submission();
    if ($submission && class_exists('FluentForm\App\Models\Form')) {
        try {
            $form_data = \FluentForm\App\Models\Form::find($submission->form_id);
            return $form_data ? $form_data->title : '';
        } catch (Exception $e) {
            error_log('FluentForm Form Title Error: ' . $e->getMessage());
            return '';
        }
    }
    return '';
}, 10, 2);
