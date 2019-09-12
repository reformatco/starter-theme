<?php

$templates = array( 'search.twig', 'archive.twig', 'index.twig' );

$context          = Timber::context();
$context['title'] = 'Search Results';
$context['posts'] = new Timber\PostQuery();
$content['sq'] = get_search_query();

Timber::render( $templates, $context );
