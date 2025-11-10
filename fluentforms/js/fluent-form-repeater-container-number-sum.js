(function ($) {
    const repeaterRootName = 'repeater_container';
    const sumFieldName = 'numeric_field_1';

    const repeaterNumericFieldsSelector = `div[data-root_name="${repeaterRootName}"] input[type="number"]`;
    const repeaterButtonsSelector = `div[data-root_name="${repeaterRootName}"] .js-container-repeat-buttons`;
    const totalFieldSelector = `input[name="${sumFieldName}"]`;

    function updateSum() {
        let currentSum = 0;
        const numericFields = $form.find(repeaterNumericFieldsSelector);
        
        numericFields.each(function () {
            const value = parseFloat($(this).val());
            if (!isNaN(value)) {
                currentSum += value;
            }
        });
        
        const totalField = $form.find(totalFieldSelector);
        totalField.val(currentSum).trigger('change');
    }

    function initializeEventListeners() {
        $form.off('.ff-repeater-calc');

        $form.on('input.ff-repeater-calc', repeaterNumericFieldsSelector, function () {
            updateSum();
        });

        $form.on('click.ff-repeater-calc', repeaterButtonsSelector, function () {
            setTimeout(updateSum, 100);
        });
    }

    initializeEventListeners();
    updateSum();

})(jQuery);