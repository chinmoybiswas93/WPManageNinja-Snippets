<?php

/**
 * Keep original file names for Fluent Forms file uploads
 * 
 * This filter removes the prefix that Fluent Forms adds to uploaded file names
 * and restores the original filename.
 */
add_filter('fluentform/uploaded_file_name', function ($file, $originalFileArray, $formData, $form) {
    // Restore the original filename from the original file array
    if (isset($originalFileArray['name'])) {
        $file['name'] = $originalFileArray['name'];
    }
    return $file;
}, 10, 4);
