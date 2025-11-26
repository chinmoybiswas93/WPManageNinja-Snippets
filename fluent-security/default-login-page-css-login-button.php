<?php

// Change Login Button Colors on Fluent Security Login Page

add_action('login_head', function () {
?>
    <style>
        .fls_login_page_wrap .fls_login_form_wrap .fls_form_wrap .submit .button.button-primary {
            background: #0060ff;
            color: var(--fls-btn_primary_color, #fff);
        }

        .fls_login_page_wrap .fls_login_form_wrap .fls_form_wrap .submit .button.button-primary:hover {
            background: #000000;
        }

        .fls_login_page_wrap .fls_login_form_wrap .fls_form_wrap .submit .button.button-primary,
        .fls_login_page_wrap .fls_login_form_wrap .fls_form_wrap .submit .button.wp-generate-pw {
            border: 1px solid var(--fls-btn_primary_bg, #0060ff);
        }

        .fls_login_page_wrap .fls_login_form_wrap .fls_form_wrap .submit .button.button-primary:hover,
        .fls_login_page_wrap .fls_login_form_wrap .fls_form_wrap .submit .button.wp-generate-pw:hover {
            border: 1px solid #000000;
        }
    </style>
<?php
});
