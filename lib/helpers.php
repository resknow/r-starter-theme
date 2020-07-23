<?php

/**
 * Excerpt Length
 */
add_filter( 'excerpt_length', function($length) {
    return 15;
}, 999 );

/**
 * Posts per page
 */
add_action( 'pre_get_posts', function($query) {

    if ( is_home() ) {
       $query->set( 'posts_per_page', 12 );
    }

    return;

} );

/**
 * Get Post with Fields
 *
 * Returns a post with it's ACF fields
 * attached to it
 *
 * @NOTE We don't use $post as a variable
 * inside here so we don't mess with the
 * WordPress global variable
 *
 * @param int $id Post ID
 */
function get_post_with_fields( $id ) {

    // Get Post
    if ( !$the_post = get_full_post($id) )
        return false;

    // Add the fields
    $the_post->fields = get_fields($id);

    // Done!
    return $the_post;

}

/**
 * Get Posts with Fields
 *
 * Returns an array of posts
 * with their ACF fields attached
 *
 * @NOTE We don't use $post as a variable
 * inside here so we don't mess with the
 * WordPress global variable
 *
 * @param array $args get_posts() args
 */
function get_posts_with_fields( array $args = array() ) {

    // Get Posts
    if ( !$the_posts = get_posts($args) )
        return false;

    // Add fields
    foreach ( $the_posts as $key => $p ) {
        $p = get_full_post($p->ID);
        $p->fields = get_fields($p->ID);

        $the_posts[$key] = $p;
    }

    // Done!
    return $the_posts;

}

/**
 * Get Full Post
 *
 * Gets post object and all fields, featured image etc.
 * @param int $id Post ID
 * @return object $the_post_object
 */
function get_full_post( $id = null ) {
    global $post;

    // If no post, get the current post
    if ( is_null($id) ) {
        $the_post_object = $post;

    // Get post by ID
    } else {
        $the_post_object = get_post($id);
    }

    // Check we have a post
    if ( ! $the_post_object ) {
        return false;
    }

    // Create Excerpt if we don't have one
    if ( empty($the_post_object->post_excerpt) ) {
        $the_post_object->post_excerpt = create_post_excerpt($the_post_object->post_content);
    }

    // Run filters on the content
    $the_post_object->post_content = apply_filters( 'the_content', $the_post_object->post_content );

    // Get Featured Image
    $the_post_object = get_featured_image($the_post_object);

    // Done!
    return $the_post_object;

}

/**
 * Get Featured Image
 *
 * Takes a WP_Post object and adds an
 * array with the featured image in all
 * available sizes
 *
 * @param WP_Post $the_post_object
 * @return WP_Post $the_post_object
 */
function get_featured_image( WP_Post $the_post_object ) {

    // Set default thumbnail
    $post_id = ( has_post_thumbnail($the_post_object->ID) ? $the_post_object->ID : HOMEPAGE );

    // Create Array
    $the_post_object->featured_image = array();

    // Get image attachment ID
    $thumbnail_id = get_post_thumbnail_id($post_id);

    // Get the image array
    $full_image = wp_get_attachment_image_src($thumbnail_id, 'full');

    // Add it to the post object in the same format as ACF
    $the_post_object->featured_image['url'] = $full_image[0];

    // Get image sizes
    $image_sizes = get_intermediate_image_sizes();

    // Get the image for each size
    foreach ( $image_sizes as $size ) {

        // Get the image array
        $image = wp_get_attachment_image_src($thumbnail_id, $size);

        // Add it to the post object in the same format as ACF
        $the_post_object->featured_image['sizes'][$size] = $image[0];
        $the_post_object->featured_image['sizes'][$size . '-width'] = $image[1];
        $the_post_object->featured_image['sizes'][$size . '-height'] = $image[2];

    }

    // Done!
    return $the_post_object;

}

/**
 * Create Post Excerpt
 *
 * Creates a post excerpt with an ellipsis if needed.
 */
function create_post_excerpt( $content, $length = 150 ) {
    $content = strip_tags($content);
    $excerpt = substr( $content, 0, $length );
    $ellipsis = ( strlen($excerpt) > ($length - 3) ? '...' : '' );
    return $excerpt . $ellipsis;
}

/**
 * Get Image Brightness
 */
function get_image_brightness( $gdHandle ) {
    $width = imagesx($gdHandle);
    $height = imagesy($gdHandle);

    $totalBrightness = 0;

    for ($x = 0; $x < $width; $x++) {
        for ($y = 0; $y < $height; $y++) {
            $rgb = imagecolorat($gdHandle, $x, $y);

            $red = ($rgb >> 16) & 0xFF;
            $green = ($rgb >> 8) & 0xFF;
            $blue = $rgb & 0xFF;

            $totalBrightness += (max($red, $green, $blue) + min($red, $green, $blue)) / 2;
        }
    }

    imagedestroy($gdHandle);

    return ($totalBrightness / ($width * $height)) / 2.55;
}

/**
 * Get Menu
 *
 * @param string $name Menu name
 * @return array Menu array
 */
function get_menu( $name ) {

    // Get Menu
    if ( !$menu = wp_get_nav_menu_items($name) ) {
        return false;
    }

    // Sort the menu children etc.
    foreach ( $menu as $key => $item ) {
        if ( $item->menu_item_parent < 1 ) {
            $the_menu[$item->ID] = $item;
            $the_menu[$item->ID]->children = [];
        } else {
            $the_menu[$item->menu_item_parent]->children[] = $item;
        }
    }

    return $the_menu;

}
