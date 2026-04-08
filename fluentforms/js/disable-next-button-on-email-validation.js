// Disable next button on email validation
(function ($) {
    var $email = $form.find('[name="email"]');
    var $confirmEmail = $form.find('[name="email_1"]');
    var $firstStepNextBtn = $form.find('.ff-btn-next').first();

    function toggleFirstStepNextButton() {
        var emailValue = $.trim($email.val() || '').toLowerCase();
        var confirmEmailValue = $.trim($confirmEmail.val() || '').toLowerCase();
        var isMatched = emailValue && confirmEmailValue && emailValue === confirmEmailValue;

        $firstStepNextBtn.prop('disabled', !isMatched);
    }

    // Disable by default
    $firstStepNextBtn.prop('disabled', true);

    // Re-check while typing/changing
    $email.add($confirmEmail).on('input change keyup', toggleFirstStepNextButton);

    // Initial check (in case fields are prefilled)
    toggleFirstStepNextButton();
})(jQuery);