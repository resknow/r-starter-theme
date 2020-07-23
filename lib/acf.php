<?php

// Add Globals Page
add_action( 'acf/init', function() {
    acf_add_options_page([
        'page_title'    => 'Globals',
        'icon_url'      => 'dashicons-admin-site-alt',
        'position'      => '60.1'
    ]);
} );