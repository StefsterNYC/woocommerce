<?php
/**
 * WooCommerce – Display-Only Whole Euro Rounding
 *
 * Description:
 *   Rounds *displayed* product prices (gross, including tax) up to the next whole euro.
 *   Does not alter WooCommerce’s underlying price calculations, so taxes, shipping,
 *   discounts, and gateway fees continue to use the exact values.
 *
 * Usage:
 *   - Paste into your child theme’s functions.php or a small custom plugin.
 *   - Remove any existing filters on `raw_woocommerce_price` to avoid conflicts.
 *
 * Affected Areas:
 *   - Catalog prices (shop/archive pages)
 *   - Single product price display
 *   - Cart item price and line item subtotals
 *
 * Not Affected:
 *   - Internal order math
 *   - Tax totals
 *   - Shipping totals
 *   - Payment gateway fee calculations
 *
 * Author: S. Serafin
 * Agency: The Bonsai Group
 */

// Catalog & single product price HTML
add_filter('woocommerce_get_price_html', function ($html, $product) {
    // Helper to round a specific raw price for a product to gross whole €
    $round_gross = function($raw) use ($product) {
        if ($raw === '' || $raw === null) return $html;
        $gross = wc_get_price_including_tax($product, ['price' => (float) $raw]);
        return wc_price( ceil($gross) ) . $product->get_price_suffix();
    };

    // Variable products: show rounded min–max gross range
    if ( $product->is_type('variable') ) {
        $prices = $product->get_variation_prices( true ); // true => incl. tax for display
        if ( empty($prices['price']) ) return $html;
        $min = ceil( min($prices['price']) );
        $max = ceil( max($prices['price']) );
        return $min === $max
            ? wc_price($min) . $product->get_price_suffix()
            : wc_price($min) . ' – ' . wc_price($max) . $product->get_price_suffix();
    }

    // Simple/other types, handle sale strikethrough correctly
    $regular = $product->get_regular_price();
    $sale    = $product->get_sale_price();
    $price   = $product->get_price();

    // On sale: format <del>/<ins> with rounded gross
    if ( $product->is_on_sale() && $regular !== '' && $sale !== '' ) {
        $reg_html  = $round_gross($regular);
        $sale_html = $round_gross($sale);
        return wc_format_sale_price($reg_html, $sale_html) . $product->get_price_suffix();
    }

    // Not on sale: single rounded gross
    return $round_gross($price);
}, 10, 2);

// Cart: unit price (display only)
add_filter('woocommerce_cart_item_price', function ($price_html, $cart_item) {
    $product = $cart_item['data'];
    if ( ! $product instanceof WC_Product ) return $price_html;
    $gross = wc_get_price_including_tax($product, ['price' => (float) $product->get_price()]);
    return wc_price( ceil($gross) );
}, 10, 2);

// Cart/Checkout: line subtotal (qty × rounded gross) – display only
add_filter('woocommerce_cart_item_subtotal', function ($subtotal_html, $cart_item) {
    $product = $cart_item['data'];
    $qty     = (int) $cart_item['quantity'];
    if ( ! $product instanceof WC_Product || $qty <= 0 ) return $subtotal_html;
    $gross_unit = wc_get_price_including_tax($product, ['price' => (float) $product->get_price()]);
    return wc_price( ceil($gross_unit) * $qty );
}, 10, 2);