<?php

/**
 * Get affiliate ID by WooCommerce order ID
 * 
 * @param int $order_id WooCommerce order ID
 * @return int|null Affiliate ID or null if not found
 */
function get_affiliate_id_by_order($order_id)
{
    // Check if Fluent Affiliate is active
    if (!class_exists('\FluentAffiliate\App\Models\Referral')) {
        return null;
    }

    $referral = \FluentAffiliate\App\Models\Referral::where('provider', 'woo')
        ->where('provider_id', $order_id)
        ->first();

    if (!$referral) {
        return null;
    }

    return $referral->affiliate_id;
}

// Test for order 1234
$affiliate_id = get_affiliate_id_by_order(1234);
error_log('Affiliate ID for order 1234: ' . ($affiliate_id ? $affiliate_id : 'Not found'));
