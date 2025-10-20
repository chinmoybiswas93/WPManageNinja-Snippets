<?php

/*
* Custom Smartcode for Address Field in Fluent Forms
* This code adds two custom smartcodes to extract street name and street number from an address field
* in Fluent Forms. The smartcodes are {ff_custom_street_name} and {ff_custom_street_no}. 
* update the 'address_1' key in the code to match your address field key.   
*/

add_filter('fluentform/all_editor_shortcodes', function ($data) {
    $customShortCodes = [
        'title' => 'Custom',
        'shortcodes' => [
            '{ff_custom_street_name}' => 'Custom Street Name',
            '{ff_custom_street_no}' => 'Custom Street Number',
        ]
    ];
    $data[] = $customShortCodes;
    return $data;
}, 10, 1);


add_filter('fluentform/shortcode_parser_callback_ff_custom_street_name', function ($value, $parser) {
    //get entry response
    $entry = $parser::getEntry();
    $response = json_decode($entry->response, true);
    //get address array
    $address = isset($response['address_1']) ? $response['address_1'] : '';
    //get address line 1
    $address_line_1 = isset($address['address_line_1']) ? $address['address_line_1'] : '';
    $street_number = explode(' ', trim($address_line_1))[0];
    //rest of the array
    $street_name = trim(str_replace($street_number, '', $address_line_1));

    return $street_name;
}, 10, 2);

add_filter('fluentform/shortcode_parser_callback_ff_custom_street_no', function ($value, $parser) {
    //get entry response
    $entry = $parser::getEntry();
    $response = json_decode($entry->response, true);
    //get address array
    $address = isset($response['address_1']) ? $response['address_1'] : '';
    //get address line 1
    $address_line_1 = isset($address['address_line_1']) ? $address['address_line_1'] : '';
    $street_number = explode(' ', trim($address_line_1))[0];
    //rest of the array
    $street_name = trim(str_replace($street_number, '', $address_line_1));

    return $street_number;
}, 10, 2);
