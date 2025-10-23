<?php

add_filter('fluentform/webhook_request_args', function ($payload) {

    // Parse the JSON body to modify field types
    if (isset($payload['body'])) {
        $body_data = json_decode($payload['body'], true);

        if ($body_data !== null) {
            // Convert string values to proper data types
            if (isset($body_data['custom_mode'])) {
                $body_data['custom_mode'] = filter_var($body_data['custom_mode'], FILTER_VALIDATE_BOOLEAN);
            }

            if (isset($body_data['style_weight'])) {
                $body_data['style_weight'] = floatval($body_data['style_weight']);
            }

            if (isset($body_data['weirdness_constraint'])) {
                $body_data['weirdness_constraint'] = floatval($body_data['weirdness_constraint']);
            }

            if (isset($body_data['make_instrumental'])) {
                $body_data['make_instrumental'] = filter_var($body_data['make_instrumental'], FILTER_VALIDATE_BOOLEAN);
            }

            // Update the payload body with modified data
            $payload['body'] = json_encode($body_data);
        }
    }

    return $payload;
}, 10, 1);
