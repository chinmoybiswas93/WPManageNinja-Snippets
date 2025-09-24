<?php
/*
* Custom Random Number Editor SmartCode For Fluent Forms Editor
* Upate the $dynamicValue and $prefix as your need
* Check here for Detials: https://fluentforms.com/docs/creating-custom-smartcode-for-form-editor/ 
*/

add_filter('fluentform/editor_shortcodes', function ($smartCodes) {
    $smartCodes[0]['shortcodes']['{custom_random_number}'] = 'Custom Random Number';
    return $smartCodes;
});


add_filter('fluentform/editor_shortcode_callback_custom_random_number', function ($value, $form) {
    $dynamicValue = rand(10000, 99999); // random number five digit
    $prefix = 'Hello'; // set you prefix here if any
    return $prefix.$dynamicValue;
}, 10, 2);