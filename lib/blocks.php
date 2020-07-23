<?php

add_action('acf/init', function () {

    acf_register_block_type(array(
        'name'              => 'cta',
        'title'             => __('Call to Action'),
        'description'       => __('(Example Block) Prompt the user to take a desired action.'),
        'render_callback'   => 'render_acf_block',
        'category'          => 'formatting',
        'icon'              => 'redo',
        'keywords'          => array('cta', 'call', 'action'),
    ));
});

/**
 *  This is the callback that displays the block.
 *
 * @param   array  $block      The block settings and attributes.
 * @param   string $content    The block content (empty string).
 * @param   bool   $is_preview True during AJAX preview.
 */
function render_acf_block($block, $content = '', $is_preview = false)
{
    $context = Timber::context();
    $context['block'] = $block;
    $context['fields'] = get_fields();
    $context['is_preview'] = $is_preview;

    $name = str_replace('acf/', '', $block['name']);
    $template = sprintf('blocks/%s.twig', $name);
    Timber::render($template, $context);
}
