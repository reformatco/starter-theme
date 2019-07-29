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

?>