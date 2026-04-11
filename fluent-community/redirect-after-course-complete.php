<?php

/**
 * Fluent Community — redirect after final lesson / course completion (SPA REST).
 * Set a real URL or filter `my_fcom_course_completed_redirect_url`.
 */
if (! defined('MY_FCOM_COURSE_DONE_URL')) {
    define('MY_FCOM_COURSE_DONE_URL', home_url('/your-custom-page'));
}

/**
 * Front-end redirect when the completion REST call returns is_completed: true.
 *
 * Hook `fluent_community/before_js_loaded` — required when the portal is headless: portal_page.php
 * does not call wp_footer() if $isHeadless is true, so theme `wp_footer` code never runs.
 * This action runs on every portal view before FC module scripts (app.js).
 *
 * Patches both XHR and fetch because $put() may not use fetch.
 */
function fcom_print_course_completion_redirect_script()
{
    static $printed = false;
    if ($printed) {
        return;
    }
    if (is_admin()) {
        return;
    }

    $redirect_url = apply_filters('my_fcom_course_completed_redirect_url', MY_FCOM_COURSE_DONE_URL);
    if (empty($redirect_url)) {
        return;
    }
    $printed = true;

    $url_json = wp_json_encode(esc_url_raw($redirect_url));
?>
    <script id="fcom-course-completion-redirect">
        (function() {
            var redirectUrl = <?php echo $url_json; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                ?>;

            function isCompletionRequestUrl(url) {
                if (!url || typeof url !== 'string') {
                    return false;
                }
                return url.indexOf('/courses/') !== -1 &&
                    url.indexOf('/lessons/') !== -1 &&
                    url.indexOf('/completion') !== -1;
            }

            function maybeRedirect(text) {
                try {
                    var data = JSON.parse(text);
                    if (data && data.is_completed === true) {
                        window.location.href = redirectUrl;
                    }
                } catch (e) {}
            }
            if (typeof XMLHttpRequest !== 'undefined') {
                var xopen = XMLHttpRequest.prototype.open;
                var xsend = XMLHttpRequest.prototype.send;
                XMLHttpRequest.prototype.open = function(method, url) {
                    this._fcomCompletionUrl = url;
                    return xopen.apply(this, arguments);
                };
                XMLHttpRequest.prototype.send = function() {
                    var xhr = this;
                    xhr.addEventListener('load', function() {
                        if (!isCompletionRequestUrl(xhr._fcomCompletionUrl)) {
                            return;
                        }
                        maybeRedirect(xhr.responseText || '');
                    });
                    return xsend.apply(this, arguments);
                };
            }
            if (typeof window.fetch === 'function') {
                var ofetch = window.fetch;
                window.fetch = function() {
                    var args = arguments;
                    return ofetch.apply(this, args).then(function(response) {
                        var req = args[0];
                        var reqUrl = typeof req === 'string' ? req : (req && req.url ? req.url : '');
                        if (!isCompletionRequestUrl(reqUrl)) {
                            return response;
                        }
                        var clone = response.clone();
                        return clone
                            .json()
                            .then(function(data) {
                                if (data && data.is_completed === true) {
                                    window.location.href = redirectUrl;
                                }
                                return response;
                            })
                            .catch(function() {
                                return response;
                            });
                    });
                };
            }
        })();
    </script>
<?php
}

add_action('fluent_community/before_js_loaded', 'fcom_print_course_completion_redirect_script', 1);
/** Fallback if portal is not headless and something prevents before_js_loaded (should be rare). */
add_action('wp_footer', 'fcom_print_course_completion_redirect_script', 1);
