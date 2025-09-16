<?
// FluentSecurity: Custom button styles for magic login and 2FA buttons
function fluent_security_custom_button_styles()
{
    // Hook into the same action that FluentSecurity uses to add CSS
    add_action('login_enqueue_scripts', function () {
        // Custom CSS for FluentSecurity buttons
        $custom_css = "
        /* Magic Login Submit Button */
        #fls_magic_submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }
        
        #fls_magic_submit:hover {
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4) !important;
        }
        
        /* Magic Login Show Button */
        .fls_magic_show_btn {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
        }
        
        .fls_magic_show_btn:hover {
            background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%) !important;
        }
        
        /* 2FA Confirm Button - targeting common selectors */
        input[type='submit'][value*='Confirm'], 
        input[type='submit'][value*='Verify'],
        .fls_2fa_form input[type='submit'],
        #fls_2fa_confirm_btn,
        button[name*='2fa'],
        button[name*='confirm'] {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
        }
        
        input[type='submit'][value*='Confirm']:hover, 
        input[type='submit'][value*='Verify']:hover,
        .fls_2fa_form input[type='submit']:hover,
        #fls_2fa_confirm_btn:hover,
        button[name*='2fa']:hover,
        button[name*='confirm']:hover {
            box-shadow: 0 6px 20px rgba(79, 172, 254, 0.4) !important;
        }
        ";

        // Add the custom CSS to the fls-login-customizer stylesheet
        wp_add_inline_style('fls-login-customizer', $custom_css);
    }, 15); // Priority 15 to ensure it runs after FluentSecurity's CSS is enqueued
}

// Initialize the custom button styles
add_action('login_init', 'fluent_security_custom_button_styles');
