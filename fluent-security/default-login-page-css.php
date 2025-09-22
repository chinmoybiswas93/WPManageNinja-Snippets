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
        button.fls_magic_show_btn{
            background: red;
            border: red;
            padding: 7px;
        }
        button.fls_magic_show_btn:hover {
            background: red;
            border: red;
            padding: 7px;
        }
        
        /* 2FA Confirm Button */
        button#fls_2fa_confirm {
            background: #417824 !important;
            border: 1px solid #417824 !important;
        }
        
        button#fls_2fa_confirm:hover {
            background: #d64709ff !important;
            border: 1px solid #d64709ff !important;
        }
        ";

        // Add the custom CSS to the fls-login-customizer stylesheet
        wp_add_inline_style('fls-login-customizer', $custom_css);
    }, 15); // Priority 15 to ensure it runs after FluentSecurity's CSS is enqueued
}

// Initialize the custom button styles
add_action('login_init', 'fluent_security_custom_button_styles');
