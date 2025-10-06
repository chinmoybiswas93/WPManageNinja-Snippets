<?php
/**
 * Add fixed width and height to images in Ninja Tables WooCommerce product feature images.
 */
function ninja_tables_add_image_dimensions($formatted_data, $table_id)
{

    //check the table ID if you want to target a specific table
    if ($table_id != 1703) { // Replace 1703 with your specific table ID
        return $formatted_data;
    }

    // Process each row of data
    foreach ($formatted_data as $row_index => $row) {

        // Process each column in the row
        foreach ($row as $column_key => $column_value) {

            // Check if this column contains an img tag
            if (is_string($column_value) && strpos($column_value, '<img') !== false) {

                // Add width and height attributes to img tags
                $formatted_data[$row_index][$column_key] = preg_replace(
                    '/<img([^>]*?)>/i',
                    '<img$1 width="250" height="250" style="object-fit: cover; border-radius: 4px;">',
                    $column_value
                );
            }
        }
    }

    return $formatted_data;
}
add_filter('ninja_tables_get_public_data', 'ninja_tables_add_image_dimensions', 10, 2);
