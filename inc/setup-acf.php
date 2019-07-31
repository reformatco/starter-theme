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

function admin_theme_acf() {

  wp_register_style( 'acf-admin-css', esc_url( get_template_directory_uri() ).ASSETS_DIR."/css/admin.css", array() );
  wp_enqueue_style('acf-admin-css');

  // wp_register_script( 'acf-admin-js', esc_url( get_template_directory_uri() )."/inc/admin.js", array() );
  // wp_enqueue_script('acf-admin-js');

}

add_action('admin_enqueue_scripts', 'admin_theme_acf');
// add_action('login_enqueue_scripts', 'admin_theme_style');


add_filter( 'acf/fields/wysiwyg/toolbars' , 'reformat_toolbars'  );
function reformat_toolbars( $toolbars ){

  $toolbars['Very Simple' ] = array();
  $toolbars['Very Simple' ][1] = array('bold' , 'italic' , 'underline', 'link' );

  if( ($key = array_search('code' , $toolbars['Full' ][2])) !== false ){
      unset( $toolbars['Full' ][2][$key] );
  }

  return $toolbars;
}


function my_acf_input_admin_footer() {

  ?>
  <script type="text/javascript">
  (function($) {

    function updateLabels(elem){
      $(elem).find('.acf-button-group label input').each(function(){
        // console.log( $(this).attr('value') );
        var bg = $(this).attr('value');
        $(this).parent().addClass(bg);
      });
    }

    acf.addAction('prepare_field/type=button_group', function(elem){
      if ($(elem.$el).hasClass('swatches')) {
        updateLabels(elem.$el)
      }
    });

    acf.addAction('new_field/type=button_group', function(elem){
      if ($(elem.$el).hasClass('swatches')) {
        updateLabels(elem.$el)
      }
    });

  })(jQuery);
  </script>
  <?php

  }

  add_action('acf/input/admin_footer', 'my_acf_input_admin_footer');

?>