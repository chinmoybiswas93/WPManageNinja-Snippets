<?php

/**
 * Add custom parameter to email resume link
 */
add_filter('fluentform/email_resume_link_body', 'add_test_parameter_to_resume_link', 10, 3);

function add_test_parameter_to_resume_link($emailBody, $form, $link)
{
    // Add test=1234 parameter to the link
    $modifiedLink = add_query_arg('test', '1234', $link);

    // Replace the original link with the modified one in the email body
    $emailBody = str_replace($link, $modifiedLink, $emailBody);

    return $emailBody;
}
