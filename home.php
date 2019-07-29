<?php

$context = Timber::context();
$context['posts'] = new Timber\PostQuery();

Timber::render( 'home.twig', $context );
