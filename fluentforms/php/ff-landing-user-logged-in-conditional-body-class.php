<?php
// Add custom body class to Fluent Forms landing pages
add_filter('fluentform/landing_vars', function ($vars, $form_id) {
    if ($form_id === 231) {
        // Check if user is logged in
        $is_logged_in = is_user_logged_in();

        // Add JavaScript to inject body class via wp_head
        add_action('wp_head', function () use ($is_logged_in) {
            $logged_in_class = $is_logged_in ? 'ff-landing-user-logged-in' : 'ff-landing-user-not-logged-in';
?>
            <script type="text/javascript">
                (function() {
                    var addClass = function() {
                        document.body.classList.add('ff-landing-has-banner');
                        document.body.classList.add('<?php echo esc_js($logged_in_class); ?>');
                    };

                    if (document.body) {
                        addClass();
                    } else {
                        document.addEventListener('DOMContentLoaded', addClass);
                    }
                })();
            </script>
<?php
        }, 999);
    }
    return $vars;
}, 10, 2);
