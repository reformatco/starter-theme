<?php

/* Template Name: Archive - Events */

global $paged;
if (!isset($paged) || !$paged){
    $paged = 1;
}

$templates = array( 'archive-events.twig', 'archive.twig', 'index.twig' );

$context = Timber::context();

$context['title'] = 'Archive';

$args = array(
  'post_type' => 'events',
  'ignore_sticky_posts' => 1,
  'posts_per_page' => get_option('posts_per_page'),
  'post_parent' => 0,
  'paged' => $paged,
);
$context['posts'] = new Timber\PostQuery($args);

Timber::render( $templates, $context );
