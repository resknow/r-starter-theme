<?php

// Login page branding
add_action( 'login_head', function() {
    printf( '<link rel="stylesheet" href="%s">', get_template_directory_uri() . '/login.css' );
} );

// Change the logo URL
add_filter( 'login_headerurl', function($url) {
    return home_url();
} );
