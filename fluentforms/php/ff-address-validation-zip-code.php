<?php

add_filter('fluentform/validate_input_item_address', function ($error, $field, $formData) {
    $fieldName = isset($field['name']) ? $field['name'] : \FluentForm\Framework\Helpers\ArrayHelper::get($field, 'raw.attributes.name');

    if (!$fieldName) {
        return $error;
    }

    $addressData = \FluentForm\Framework\Helpers\ArrayHelper::get($formData, $fieldName);

    if (!$addressData || !is_array($addressData)) {
        return $error;
    }

    $zipCode = \FluentForm\Framework\Helpers\ArrayHelper::get($addressData, 'zip');

    if ($zipCode && !preg_match('/^\d{5}$/', $zipCode)) {
        return 'Zip code must be exactly 5 digits';
    }

    return $error;
}, 10, 3);
