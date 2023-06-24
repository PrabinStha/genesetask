<?php
/**
 * Create a code snippet to add Rs 100 Delivery Charge if total price of the checkout item is below Rs 1000.
 * Task I
 */

//  Function to add delivery charge if total price is below Rs 1000.

function add_delivery_charge() {
    // Get the cart subtotal
    $cart_total = WC()->cart->subtotal;

    // Convert cart total to numeric value
    $cart_total = floatval($cart_total);

    // Check if cart total is below Rs 1000
    if ($cart_total < 1000) {
        // Add delivery charge
        $delivery_charge = 100;

        // Add a fee to the cart
        WC()->cart->add_fee(
            __('Delivery Charge', 'puma'),  // Fee label or name
            $delivery_charge,               // Fee amount
            false,                          // Whether to apply tax to the fee (false in this case)
            ''                              // Optional fee class (empty string in this case)
        );

        // Update cart totals after adding the fee
        WC()->cart->calculate_totals();
    }
}

// Hook into the cart calculations and call the above function
add_action('woocommerce_cart_calculate_fees', 'add_delivery_charge');
