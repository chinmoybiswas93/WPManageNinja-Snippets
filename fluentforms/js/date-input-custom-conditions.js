// date input custom conditions
// set hidden days based on the date input from two date fields
// check-in: no past dates
// check-out: no past dates
// check-out: must be after check-in
// check-out: must be after today
// check-out: must be after check-in
// check-out: must be after check-in

jQuery(function ($) {
    var checkInSelector = "[name='datetime']";
    var checkOutSelector = "[name='datetime_1']";
    var hiddenDaysSelector = "[name='hidden_days'], [name='hidden']";

    var $hiddenDays = $form.find(hiddenDaysSelector);

    function setHiddenDays(checkInDate, checkOutDate) {
        var days = 0;
        if (checkInDate && checkOutDate && checkOutDate > checkInDate) {
            days = Math.ceil((checkOutDate - checkInDate) / 86400000);
        }
        $hiddenDays.val(days).trigger('change');
    }

    // Check-in: no past dates
    var checkInPicker = flatpickr($form.find(checkInSelector)[0], {
        dateFormat: "m/d/Y",
        minDate: "today",
        allowInput: false,
        closeOnSelect: true,
        onChange: function (selectedDates) {
            var checkInDate = selectedDates[0] || null;
            var checkOutDate = checkOutPicker.selectedDates[0] || null;

            if (checkInDate) {
                var minCheckoutDate = new Date(checkInDate).fp_incr(1); // must be after check-in
                checkOutPicker.set("minDate", minCheckoutDate);

                // Clear invalid checkout
                if (checkOutDate && checkOutDate <= checkInDate) {
                    checkOutPicker.clear();
                    checkOutDate = null;
                }
            } else {
                checkOutPicker.set("minDate", "today");
            }

            setHiddenDays(checkInDate, checkOutDate);
        }
    });

    // Check-out: no past dates initially
    var checkOutPicker = flatpickr($form.find(checkOutSelector)[0], {
        dateFormat: "m/d/Y",
        minDate: "today",
        allowInput: false,
        closeOnSelect: true,
        onChange: function (selectedDates) {
            var checkInDate = checkInPicker.selectedDates[0] || null;
            var checkOutDate = selectedDates[0] || null;

            if (checkInDate && checkOutDate && checkOutDate <= checkInDate) {
                checkOutPicker.clear();
                checkOutDate = null;
            }

            setHiddenDays(checkInDate, checkOutDate);
        }
    });

    // Initial state
    setHiddenDays(checkInPicker.selectedDates[0] || null, checkOutPicker.selectedDates[0] || null);
});