<?php

/**
 * Theme Setup
 *
 */
add_action('after_setup_theme', function () {

    // Let WordPress manage the document title.
    add_theme_support('title-tag');

    // Add Featured Image Support for posts
    add_theme_support('post-thumbnails');

    add_theme_support('align-wide');

    // Add HTML5 Support
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    // Set a better Thumbnail Size
    update_option('thumbnail_size_w', 240);
    update_option('thumbnail_size_h', 240);

    // Panel Image
    add_image_size('panel_image', 600, 300, true);

    // Hero Sizes
    add_image_size('hero', 1800, 840, true);
    add_image_size('hero_medium', 980, 457, true);
    add_image_size('hero_small', 480, 224, true);

    // Register Menu
    register_nav_menu('main-menu', __('Main Menu'));
});

/**
 * Admin CSS
 */
add_action('admin_enqueue_scripts', function () {

    // Global Styles
    wp_enqueue_style('admin-styles', get_template_directory_uri() . '/admin.css');
});

add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_style('editor-layout-style', get_template_directory_uri() . '/assets/css/editor.css');
});

/**
 * Disable the Gutenberg editor for Products
 */
add_filter('use_block_editor_for_post_type', function ($can_edit, $post_type) {
    if ($post_type == 'product') {
        $can_edit = false;
    }

    return $can_edit;
}, 10, 2);

/**
 * Remove Quick Edit + Preview links from the list view
 */
add_filter('post_row_actions', function ($actions) {

    unset($actions['view']);
    unset($actions['inline hide-if-no-js']);
    return $actions;
}, 10, 1);
