<?php

/**
 * Display global inventory stats outside forms.
 *
 * Usage:
 *   [ff_inventory slug="dance-class"]
 *   [ff_inventory slug="dance-class" show="remaining"]
 *   show: remaining (default) | total | used | name | label (name + remaining text)
 */
add_shortcode('ff_inventory', function ($atts) {
    if (! class_exists('\FluentFormPro\classes\Inventory\InventoryValidation')) {
        return '';
    }

    $atts = shortcode_atts(
        [
            'slug' => '',
            'show' => 'remaining',
        ],
        $atts,
        'ff_inventory'
    );

    $slug = sanitize_title($atts['slug']);
    if ($slug === '') {
        return '';
    }

    $inventoryList = get_option('ff_inventory_list');
    if (! is_array($inventoryList) || ! isset($inventoryList[$slug])) {
        return '';
    }

    $row   = $inventoryList[$slug];
    $total = (int) \FluentForm\Framework\Helpers\ArrayHelper::get($row, 'quantity', 0);
    $label = (string) \FluentForm\Framework\Helpers\ArrayHelper::get($row, 'name', '');

    $usedRows = \FluentFormPro\classes\Inventory\InventoryValidation::getSubmittedGlobalInventories([$slug]);
    $byOption = \FluentFormPro\classes\Inventory\InventoryValidation::calculateGlobalInventory($usedRows, true);

    $used = 0;
    if (! empty($byOption[$slug]) && is_array($byOption[$slug])) {
        $used = array_sum($byOption[$slug]);
    }

    $remaining = max($total - $used, 0);

    switch ($atts['show']) {
        case 'total':
            return esc_html((string) $total);
        case 'used':
            return esc_html((string) $used);
        case 'name':
            return esc_html($label);
        case 'label':
            return esc_html(
                sprintf(
                    /* translators: 1: inventory name, 2: remaining count */
                    __('%1$s: %2$s spots left', 'textdomain'),
                    $label,
                    $remaining
                )
            );
        case 'remaining':
        default:
            return esc_html((string) $remaining);
    }
});
