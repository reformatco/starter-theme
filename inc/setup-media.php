<?php

add_image_size('banner', 1920, 1080, true);
add_image_size('banner-mobile', 375, 240, true);
add_image_size('slider', 1440, 768, true);
add_image_size('grid-landscape', 960, 720, true);
add_image_size('grid-portrait', 960, 1464, true);
add_image_size('square', 600, 600, true);
add_image_size('map', 240, 180, true);



// Tweak YouTube sizes

function custom_youtube_settings($code){
  if(strpos($code, 'youtu.be') !== false || strpos($code, 'youtube.com') !== false){
    $return = preg_replace("@src=(['\"])?([^'\">\s]*)@", "src=$1$2&showinfo=0&rel=0&autohide=1", $code);
    return $return;
  }
  return $code;
}

add_filter('embed_handler_html', 'custom_youtube_settings');
add_filter('embed_oembed_html', 'custom_youtube_settings');
add_filter('oembed_result', 'custom_youtube_settings');

// Remove default media sizes

add_filter('intermediate_image_sizes_advanced', 'prefix_remove_default_images');

function prefix_remove_default_images( $sizes) {
  // unset( $sizes['thumbnail'] );
  unset( $sizes['medium_large'] );
  return $sizes;
}

// Remove any width attributes through editor

add_filter( 'post_thumbnail_html', 'remove_width_attribute', 10 );
add_filter( 'image_send_to_editor', 'remove_width_attribute', 10 );
add_filter( 'get_custom_logo', 'remove_width_attribute', 10 );

function remove_width_attribute( $html ) {
   $html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
   return $html;
}