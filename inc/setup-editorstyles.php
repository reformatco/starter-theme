<?php

function reformat_add_editor_styles() {
  global $package;

  remove_editor_styles();
  add_editor_style( 'editor-style.css' );

  if( KEY_GFONTS ):
    add_editor_style( "https://fonts.googleapis.com/css?family=".KEY_GFONTS);
	endif;

	if( KEY_TYPEKIT ):
    add_editor_style( "https://use.typekit.net/".KEY_TYPEKIT.".css" );
  endif;

}

add_action( 'admin_init', 'reformat_add_editor_styles' );
