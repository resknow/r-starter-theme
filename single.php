<?php

while ( have_posts() ) {
    the_post();

    $full_post = get_full_post();

    $the_post = [
        'title'     => get_the_title(),
        'content'   => get_the_content(),
        'excerpt'   => create_post_excerpt( get_the_content() ),
        'link'      => get_permalink(),
        'image'     => $full_post->featured_image
    ];
}

Timber::render( 'single.twig', get_context_with_fields($the_post) );
