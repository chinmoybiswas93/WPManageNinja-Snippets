jQuery(document).ready(function ($) {
  const dropdownItem = $form.find('[data-name="dropdown"]')
  const nextButton = $form.find('.ff-btn.ff-btn-next')

  dropdownItem.on('change', function () {
    if (dropdownItem.val() === 'Yes') {
      nextButton.hide() // Hide the button if the value is "yes"
    } else if (dropdownItem.val() === 'No') {
      nextButton.show() // Show the button if the value is "no"
    }
  })
})
