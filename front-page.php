<?php

Timber::render( 'page.twig', get_context_with_fields([
    'is_front_page' => true
]) );
