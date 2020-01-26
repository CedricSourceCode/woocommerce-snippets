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
