$(document).ready(function () {
    flatpickr("[name='pickup_date']", {
      dateFormat: "d/m/Y",
      onChange: function (selectedDates, dateStr, instance) {
        if (selectedDates.length > 0) {
          const nextDate = new Date(selectedDates[0]).fp_incr(1); // Add 1 day to the selected date
          // Initialize or update the dropoff_date Flatpickr with the new minDate
          $("[name='dropoff_date']").flatpickr({
            dateFormat: "d/m/Y",
            minDate: nextDate, // Set the minDate to 1 days after the pickup_date
          });
        }
      },
    });
  });