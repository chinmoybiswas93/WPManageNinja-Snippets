<?php

add_action( 'fluentform/submission_inserted', 'generate_fluentpdf_from_template', 10, 3 );

function generate_fluentpdf_from_template( $entry_id, $form_data, $form ) {
    // Limit to a specific form ID.
    if ( (int) $form->id !== 380 ) {
        return;
    }

    error_log( 'PDF hook fired for entry ' . $entry_id );

    // Make sure Fluent Forms + FluentPDF are active.
    if ( ! function_exists( 'wpFluent' ) || ! class_exists( '\FluentFormPdf\Classes\Controller\GlobalPdfManager' ) ) {
        error_log( 'Fluent Forms PDF not available' );
        return;
    }

    try {
        // Find the first PDF feed for this form.
        $feed = wpFluent()
            ->table( 'fluentform_form_meta' )
            ->where( 'form_id', $form->id )
            ->where( 'meta_key', '_pdf_feeds' )
            ->first();

        if ( ! $feed ) {
            error_log( 'No PDF feed found for form ' . $form->id );
            return;
        }

        $settings           = json_decode( $feed->value, true );
        $settings['id']     = $feed->id;
        $templates          = ( new \FluentFormPdf\Classes\Controller\GlobalPdfManager( wpFluentForm() ) )
            ->getAvailableTemplates( $form );
        $template_key       = \FluentForm\Framework\Helpers\ArrayHelper::get( $settings, 'template_key' );

        if ( empty( $template_key ) || empty( $templates[ $template_key ]['class'] ) ) {
            error_log( 'No valid PDF template class for feed ' . $feed->id );
            return;
        }

        $class     = $templates[ $template_key ]['class'];
        $instance  = new $class( wpFluentForm() );

        // Build a file name, similar to email attachments logic.
        $file_name = $settings['name'] . '_' . $entry_id . '_' . $feed->id;
        $file_name = \FluentForm\App\Services\FormBuilder\ShortCodeParser::parse( $file_name, $entry_id, $form_data );
        $file_name = sanitize_title( $file_name, 'pdf-file', 'display' );

        // Generate the PDF and get the file path (does NOT force download).
        $pdf_file_path = $instance->outputPDF( $entry_id, $settings, $file_name, false );

        if ( ! $pdf_file_path ) {
            error_log( 'PDF generation returned empty path for entry ' . $entry_id );
            return;
        }

        error_log( 'PDF generated at ' . $pdf_file_path );

        // OPTIONAL: Copy to wp-content if you need it there.
        $target = WP_CONTENT_DIR . '/fluentform-' . $entry_id . '.pdf';
        if ( @copy( $pdf_file_path, $target ) ) {
            error_log( 'PDF copied to ' . $target );
        } else {
            error_log( 'Failed to copy PDF to ' . $target );
        }

    } catch ( \Exception $e ) {
        error_log( 'PDF error: ' . $e->getMessage() );
    }
}