/*
Hide Payment Field When Coupon Is A Coupon is applied
- Take a radio field in your form and change the name attribute 'payment_identifier_radio'
- Hide the radio field with container class 'ff-hidden'
- Use `couponArray` to check the coupons applied
*/

jQuery(document).ready(function ($) {
  const couponField = $form.find("[name='__ff_all_applied_coupons']")
  const radioField = $form.find("[name='payment_identifier_radio']")

  // Function to update radio button based on coupon values
  function updateRadioField () {
    let couponValue = couponField.val().trim() // Ensure no extra spaces

    // Remove square brackets and split by comma
    let couponArray = couponValue.slice(1, -1).replace(/"/g, '').split(',')

    if (couponArray.includes('FF100')) {
      radioField.filter("[value='yes']").prop('checked', true).trigger('change')
    } else {
      radioField.filter("[value='no']").prop('checked', true).trigger('change')
    }
  }

  // Use a MutationObserver to detect changes
  const observer = new MutationObserver(updateRadioField)

  observer.observe(couponField[0], {
    attributes: true,
    attributeFilter: ['value']
  })

  // Also trigger on page load in case a value is pre-set
  updateRadioField()
})

$(document).ready(function () {
  const url = $form.find("[name='hidden']").val();
  // console.log(url);
  $form.on('fluentform_submission_success', function () {
    // window.open(url, '_blank')
    console.log('submission success')
    console.log(url)
  })
})
