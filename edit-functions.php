/*
 * 喺 active theme 之內嘅 functions.pgp
 * 更改 woo my-account nav links
 */
add_filter ( 'woocommerce_account_menu_items', 'apkee_more_links' );
function apkee_more_links( $menu_links ){

	//刪除 Address
	unset( $menu_links['edit-address'] ); // Addresses

	//新堦：we will hook "apkee-my-account-links" later
	//$new = array( 'apkee-my-account-link1' => __('Billing Address', 'woocommerce') );
 
	// or in case you need 2 links
	$new = array( 'apkee_my_account_link1' => __('Billing Address', 'woocommerce'), 'apkee_my_account_link2' => __('Shipping Address', 'woocommerce') );
 
	// array_slice() is good when you want to add an element between the other ones
	/*
	$menu_links = array_slice( $menu_links, 0, 1, true ) 
	+ $new 
	+ array_slice( $menu_links, 1, NULL, true );
  
	return $menu_links;
	*/

	// Add the new item after `downloads`.
	return my_custom_insert_after_helper( $menu_links, $new, 'downloads' );
}
 
add_filter( 'woocommerce_get_endpoint_url', 'apkee_hook_endpoint', 10, 4 );
function apkee_hook_endpoint( $url, $endpoint, $value, $permalink ){

	if( $endpoint === 'apkee_my_account_link1' ) {
 
		// ok, here is the place for your custom URL, it could be external
		//$url = site_url();
        $url = 'https://apkee.hk/my-account/edit-address/billing/';
 
	}
 
	if( $endpoint === 'apkee_my_account_link2' ) {
 
		// ok, here is the place for your custom URL, it could be external
		//$url = site_url();
        $url = 'https://apkee.hk/my-account/edit-address/shipping/';
 
	}
 
	return $url;
}

/**
 * Custom help to add new items into an array after a selected item.
 *
 * @param array $items
 * @param array $new_items
 * @param string $after
 * @return array
 */
function my_custom_insert_after_helper( $items, $new_items, $after ) {
	// Search for the item position and +1 since is after the selected item key.
	$position = array_search( $after, array_keys( $items ) ) + 1;

	// Insert the new item.
	$array = array_slice( $items, 0, $position, true );
	$array += $new_items;
	$array += array_slice( $items, $position, count( $items ) - $position, true );

    return $array;
}


// Flexible Checkout Fields 唔識處理 my-account edit-address，要自己加 filter
// refer: https://wpbeaches.com/remove-address-fields-in-woocommerce-checkout/
//        https://stackoverflow.com/questions/49641316/customizing-my-account-addresses-fields-in-woocommerce-3
add_filter( 'woocommerce_default_address_fields' , 'custom_override_checkout_fields_ek', 99 );
// Remove some fields from billing form
// Our hooked in function - $fields is passed via the filter!
// Get all the fields - https://docs.woothemes.com/document/tutorial-customising-checkout-fields-using-actions-and-filters/
function custom_override_checkout_fields_ek( $fields ) {
	// Only on account pages
	if( ! is_account_page() ) return $fields;

	unset($fields['address_1']);
	unset($fields['address_2']);
	unset($fields['postcode']);

	return $fields;
}
