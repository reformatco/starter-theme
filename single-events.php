<?php

$context = Timber::context();
$timber_post = Timber::query_post();
$context['post'] = $timber_post;

// $ancestors = get_post_ancestors($timber_post->ID);
// if ($ancestors) {
//   $top = $ancestors[ count($ancestors) - 1 ];
// } else {
//   $top = $timber_post->ID;
// }

// // any subpages
// $args = array(
//   'post_types' => 'events',
//   'numberposts' => -1,
//   'post_parent' => $top,
//   'orderby' => 'menuorder',
//   'order' => 'ASC'
// );
// $context['subpages'] = new Timber\PostQuery($args);

if ( post_password_required( $timber_post->ID ) ) {
	Timber::render( 'single-password.twig', $context );
} else {
	Timber::render( array( 'single-' . $timber_post->ID . '.twig', 'single-' . $timber_post->post_type . '.twig', 'single.twig' ), $context );
}
