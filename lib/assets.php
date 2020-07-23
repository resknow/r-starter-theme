<?php

/**
 * Assets Directory
 *
 * @return Full path to the assets directory
 */
function assets_dir($suffix = false)
{
    $dir = get_template_directory_uri() . '/assets';

    // Suffix, possible path to a file?
    if ($suffix) {
        $dir .= '/' . $suffix;
    }

    return $dir;
}

/**
 * Add Style / Script
 *
 * Simple wrappers for wp_enqueue
 */
function add_style($id, $src, $tag = false)
{
    $tag = (!$tag ? SITE_VERSION : $tag);
    wp_enqueue_style($id, $src, $tag);
}

function add_script($id, $src, $deps = array(), $tag = false)
{
    wp_enqueue_script($id, $src, $deps, $tag, true); # The last parameter is to place the JS in the footer
}

function register_style($id, $src, $tag = false)
{
    $tag = (!$tag ? SITE_VERSION : $tag);
    wp_register_style($id, $src, $tag);
}

function register_styles($styles = [], $type)
{
    foreach ($styles as $style) {
        $path = sprintf('css/%s/%s.css', $type, $style);
        register_style($style, assets_dir($path));
    }
}

function add_required_styles($template)
{
    if (!wp_style_is($template, 'registered')) return;
    wp_enqueue_style($template);
}

/***********************************/

/**
 * Declare all your assets inside here
 */
function theme_assets()
{

    // Stylesheets
    $version = filemtime(get_template_directory() . '/assets/css/global.css');
    add_style('global', assets_dir('css/global.css?v=' . $version)); # Theme Stylesheet

    // Scripts
    add_script('nav', assets_dir('js/nav.js'));

    // Add Web Component for Google Maps
    if (is_page(64)) {
        add_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyAeP_G3ffklPHmkCIdhkVGq0GtzhusHHls');
        add_script('wc-google-map', assets_dir('js/wc-google-map.js'));
    }

    // Register Available Styles
    $components = get_component_names(get_template_directory() . '/assets/css/components');
    register_styles($components, 'components');
}

/**
 * Get Component Names
 *
 * @param string $dir Component directory
 */
function get_component_names($dir)
{
    $components = glob($dir . '/*.css');
    $components = array_map(function ($component) use ($dir) {
        $name = str_replace($dir . '/', '', $component);
        return rtrim($name, '.css');
    }, $components);

    return $components;
}

/***********************************/

add_action('wp_enqueue_scripts', 'theme_assets');
