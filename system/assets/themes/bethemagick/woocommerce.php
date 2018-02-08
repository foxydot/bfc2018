<?php
/**
 * WooCommerce Template
 *
 * Template used for all WooCommerce views for your site
 *
 */

//* Remove standard post content output
remove_action( 'genesis_loop', 'genesis_do_loop');

//* Add WooCommerce content output
add_action( 'genesis_loop', 'woocommerce_setup_genesis' );

genesis();