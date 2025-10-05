<?php
add_filter('fluentform/validate_input_item_input_password', function ($errorMessage, $field, $formData, $fields, $form) {
    $target_form_id = 149;
    
    // Only apply to form 149
    if ($form->id != $target_form_id) {
        return $errorMessage;
    }

    // Handle password confirmation field (password_1)
    if ($field['name'] === 'password_1') {
        // Check if both password fields exist and are not empty
        if (empty($formData['password']) || empty($formData['password_1'])) {
            return $errorMessage;
        }
        
        // Only check if passwords match
        if ($formData['password'] !== $formData['password_1']) {
            return 'Error. The two passwords are not the same. Please try again.';
        }
        
        return '';
    }

    // Handle main password field (password) - validate requirements
    if ($field['name'] === 'password') {
        // If field is empty, let FluentForm handle it
        if (empty($formData['password'])) {
            return $errorMessage;
        }
        
        $password = $formData['password'];
        $errors = [];

        // Check for numbers
        if (!preg_match("/[0-9]/", $password)) {
            $errors[] = 'Password must contain at least 1 number.';
        }

        // Check minimum 12 characters
        if (strlen($password) < 12) {
            $errors[] = 'Password must be at least 12 characters long.';
        }

        // Check for lowercase and uppercase letters
        if (!preg_match("/(?=.*[a-z])(?=.*[A-Z])/", $password)) {
            $errors[] = 'Password must contain at least 1 lowercase and 1 uppercase letter.';
        }

        // If there are validation errors, return them as a single string
        if (!empty($errors)) {
            return 'Error. ' . implode(' ', $errors);
        }

        return '';
    }

    // For any other password fields, return original error
    return $errorMessage;
}, 10, 5);
