<?php

use Timber\Twig_Function as TwigFunction;

/**
 * Get Context with Fields
 *
 * Returns Timber Context and all custom fields
 *
 * @param array $merge Optional array to merge into the context
 */
function get_context_with_fields($merge = [])
{

    // Get main context from Timber
    $context['timber'] = Timber::get_context();

    // Current Post/Post
    $context['this'] = new Timber\Post();

    // Add our stuff
    $context['fields'] = get_fields();
    $context['current'] = get_full_post();

    // Global
    $context['globals'] = get_fields('options');

    // Products
    $context['products'] = get_posts(['post_type' => 'product', 'per_page' => -1]);

    // Menu
    $context['menu'] = get_menu('Main Menu');

    // Add some helpers
    $context['util']['assetsDir'] = assets_dir();
    $context['util']['hasPostThumbnail'] = has_post_thumbnail();
    $context['util']['year'] = date('Y');

    return apply_filters('get_context_with_fields', array_merge($context, $merge));
}

/**
 * Add Functions to Twig
 */
add_filter('timber/twig', function ($twig) {
    $twig->addFunction(new TwigFunction('assets_dir', 'assets_dir'));
    $twig->addFunction(new TwigFunction('dump', 'dump'));
    return $twig;
});
