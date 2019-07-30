<?php

/*
ACF Setup
**************************************************************/

function acf_setup(){
  $option_panels = array('Modules','Social','Misc');
  acf_add_options_page();
  foreach( $option_panels as $panel ):
    acf_add_options_sub_page( $panel );
  endforeach;

}

if( function_exists('acf_init_theme') ) {
  add_action('after_setup_theme', 'acf_setup');
}

function acf_map_init() {
	acf_update_setting('google_api_key', KEY_GMAPS);
}

add_action('acf/init', 'acf_map_init');

function admin_theme_style() {
  wp_enqueue_style( 'admin-theme', esc_url( get_template_directory_uri() ).ASSETS_DIR."/css/admin.css" );
}
add_action('admin_enqueue_scripts', 'admin_theme_style');
add_action('login_enqueue_scripts', 'admin_theme_style');


add_filter( 'acf/fields/wysiwyg/toolbars' , 'reformat_toolbars'  );
function reformat_toolbars( $toolbars ){

  $toolbars['Very Simple' ] = array();
  $toolbars['Very Simple' ][1] = array('bold' , 'italic' , 'underline', 'link' );

  if( ($key = array_search('code' , $toolbars['Full' ][2])) !== false ){
      unset( $toolbars['Full' ][2][$key] );
  }

  return $toolbars;
}

?>