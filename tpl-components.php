<?php

/* Template Name: Components */

$context = Timber::context();

$timber_post = new Timber\Post();
$context['post'] = $timber_post;
Timber::render( array( 'tpl-components.twig', 'page.twig' ), $context );
