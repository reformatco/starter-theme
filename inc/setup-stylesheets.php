<?php

function reformat_theme_styles() {

  wp_deregister_style( 'wp-block-library' );

  if( KEY_GFONTS ):
    wp_enqueue_style( 'gfonts', "https://fonts.googleapis.com/css?family=".KEY_GFONTS);
  endif;

  if( KEY_TYPEKIT ):
    wp_enqueue_style( 'typekit', "https://use.typekit.net/".KEY_TYPEKIT.".css");
  endif;

  wp_enqueue_style( 'theme-styles', THEME_STYLESHEET, false, ASSETS_VER, false );

}

if( !is_admin() && 'wp-login.php' != $pagenow ){
  add_action( 'wp_enqueue_scripts', 'reformat_theme_styles' );
}
