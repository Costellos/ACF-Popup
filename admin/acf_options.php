<?php 
// Check function exists.
if( function_exists('acf_add_options_page') ) {

    // Register options page.
    $option_page = acf_add_options_page(array(
        'page_title'    => __('ACF Popup'),
        'menu_title'    => __('ACF Popup'),
        'menu_slug'     => 'acf-popup',
    ));
}