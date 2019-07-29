<?php

function reformat_theme_js() {

  wp_dequeue_script( array('wp-embed') );

  if( KEY_SHARETHIS && get_post_type() == 'post' ):
    wp_enqueue_script('sharethis', "//platform-api.sharethis.com/js/sharethis.js#property=".KEY_SHARETHIS."&product=custom-share-buttons", false, null, false);
  endif;

  wp_enqueue_script('theme-scripts', THEME_SCRIPTS, array('jquery'), ASSETS_VER, true);

}

if( !is_admin() && 'wp-login.php' != $pagenow ){
  add_action( 'wp_enqueue_scripts', 'reformat_theme_js' );
}
