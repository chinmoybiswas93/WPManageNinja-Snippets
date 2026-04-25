//  Exact Two Checkbox Selection Validation for Multi-Step Form

(function ($) {
    "use strict";

    var form = document.querySelector("#fluentform_469");
    if (!form) return;

    var fieldsToValidate = [
        "round1_selection",
        "round2_selection",
        "round3_selection",
        "budget_preference"
    ];

    var errorText = "Please select exactly 2 options.";
    var errorClass = "ff-exact-two-error";

    function getInputs(step, fieldName) {
        return step.querySelectorAll(
            'input[name="' + fieldName + '[]"], input[name="' + fieldName + '"]'
        );
    }

    function getCheckedCount(inputs) {
        var count = 0;

        inputs.forEach(function (input) {
            if (input.checked) {
                count++;
            }
        });

        return count;
    }

    function getFieldWrapper(inputs) {
        if (!inputs.length) return null;
        return inputs[0].closest(".ff-el-group");
    }

    function clearError(inputs) {
        var wrapper = getFieldWrapper(inputs);
        if (!wrapper) return;

        wrapper.classList.remove("ff-el-is-error");

        var oldError = wrapper.querySelector("." + errorClass);
        if (oldError) {
            oldError.remove();
        }
    }

    function showError(inputs) {
        var wrapper = getFieldWrapper(inputs);
        if (!wrapper) return;

        clearError(inputs);

        wrapper.classList.add("ff-el-is-error");

        var error = document.createElement("div");
        error.className = "error text-danger " + errorClass;
        error.textContent = errorText;

        wrapper.appendChild(error);
    }

    function setButtonDisabled(button, disabled) {
        if (!button) return;

        button.disabled = disabled;
        button.classList.toggle("ff-disabled", disabled);
    }

    function getSubmitButton() {
        return form.querySelector('button[type="submit"].ff-btn-submit');
    }

    function validateStep(step) {
        var isValid = true;

        fieldsToValidate.forEach(function (fieldName) {
            var inputs = getInputs(step, fieldName);

            // Validate only the field that exists inside the current step.
            if (!inputs.length) {
                return;
            }

            var checkedCount = getCheckedCount(inputs);

            if (checkedCount !== 2) {
                isValid = false;
                showError(inputs);
            } else {
                clearError(inputs);
            }
        });

        return isValid;
    }

    // Do NOT validate here. Only hide old error and allow the user to try Next again.
    form.addEventListener("change", function (event) {
        var target = event.target;

        if (!target.matches('input[type="checkbox"]')) {
            return;
        }

        var fieldName = (target.getAttribute("name") || "").replace(/\[\]$/, "");

        if (fieldsToValidate.indexOf(fieldName) === -1) {
            return;
        }

        var step = target.closest(".fluentform-step");
        if (!step) return;

        var inputs = getInputs(step, fieldName);
        clearError(inputs);

        var nextButton = step.querySelector('button[data-action="next"].ff-btn-next');
        setButtonDisabled(nextButton, false);
        setButtonDisabled(getSubmitButton(), false);
    });

    // Validate only when Next is clicked.
    // Capture phase is used so this runs before Fluent Forms moves to the next step.
    form.addEventListener(
        "click",
        function (event) {
            var nextButton = event.target.closest('button[data-action="next"].ff-btn-next');

            if (!nextButton || !form.contains(nextButton)) {
                return;
            }

            var step = nextButton.closest(".fluentform-step");
            if (!step) return;

            var isValid = validateStep(step);

            if (!isValid) {
                event.preventDefault();
                event.stopPropagation();
                event.stopImmediatePropagation();

                setButtonDisabled(nextButton, true);
                setButtonDisabled(getSubmitButton(), true);

                return false;
            }

            setButtonDisabled(nextButton, false);
            setButtonDisabled(getSubmitButton(), false);
        },
        true
    );

})(jQuery);