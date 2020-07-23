<?php

// Get News
while( have_posts() ) {
    the_post();

    $full_post = get_full_post();

    $news[] = [
        'title'     => get_the_title(),
        'content'   => get_the_content(),
        'excerpt'   => create_post_excerpt( get_the_content() ),
        'link'      => get_permalink(),
        'image'     => $full_post->featured_image
    ];
}

Timber::render( 'news.twig', get_context_with_fields([
    'posts' => $news,
    'title' => 'News'
]) );
