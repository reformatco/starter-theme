<?php

define('NAMESPACE','reformat-starter');
define('ASSETS_DIR','/dist');
define('ASSETS_VER','1.0');

// Third Party Scripts

define('KEY_GFONTS','');
define('KEY_TYPEKIT','');
define('KEY_SHARETHIS','');
define('KEY_GMAPS','');

// Scripts/Stylesheets

define('THEME_STYLESHEET',esc_url( get_template_directory_uri() ).ASSETS_DIR."/css/style.css" );
define('THEME_SCRIPTS',esc_url( get_template_directory_uri() ).ASSETS_DIR."/js/bundle.js" );

require_once "inc/setup-media.php";
require_once "inc/setup-stylesheets.php";
require_once "inc/setup-scripts.php";
require_once "inc/setup-header-cleanup.php";

if( is_admin() == $pagenow ){
  require_once "inc/setup-editorstyles.php";
  require_once "inc/setup-acf.php";
}

require_once "inc/custom-walkers.php";


$composer_autoload = __DIR__ . '/vendor/autoload.php';
if ( file_exists($composer_autoload) ) {
	require_once( $composer_autoload );
	$timber = new Timber\Timber();
}


if ( ! class_exists( 'Timber' ) ) {

	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
	});

	add_filter('template_include', function( $template ) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});
	return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( 'templates', 'views' );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site {
	/** Add timber support. */
	public function __construct() {
    add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
    add_action( 'after_setup_theme', array( $this, 'register_menus' ) );
    add_action( 'widgets_init', array( $this, 'register_widgets' ) );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		parent::__construct();
  }

	/** This is where you can register custom post types. */
	public function register_post_types() {

	}
	/** This is where you can register custom taxonomies. */
	public function register_taxonomies() {

	}

	/** This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context( $context ) {
    $context['menu'] = new Timber\Menu('primary_navigation');
    $context['footer_menu'] = new Timber\Menu('footer_navigation');
    $context['footer_widgets'] = Timber::get_widgets('footer_widgets');
    $context['site'] = $this;

    $context['placeholder'] = array(
      'profile' => 'iVBORw0KGgoAAAANSUhEUgAAAUAAAADICAQAAAAzDfAgAAABfUlEQVR42u3SQREAAAjDMObfKxbABXwSCb2mp+BNDIgBMSAYEAOCATEgGBADggExIBgQA4IBMSAYEAOCATEgGBADggExIBgQA4IBMSAYEAOCATEgGBADYkAwIAYEA2JAMCAGBANiQDAgBgQDYkAwIAYEA2JAMCAGBANiQDAgBgQDYkAwIAYEA2JAMCAGBANiQAwIBsSAYEAMCAbEgGBADAgGxIBgQAwIBsSAYEAMCAbEgGBADAgGxIBgQAwIBsSAYEAMCAbEgBjQgBgQA4IBMSAYEAOCATEgGBADggExIBgQA4IBMSAYEAOCATEgGBADggExIBgQA4IBMSAYEAOCATEgBgQDYkAwIAYEA2JAMCAGBANiQDAgBgQDYkAwIAYEA2JAMCAGBANiQDAgBgQDYkAwIAYEA2JAMCAGxIBgQAwIBsSAYEAMCAbEgGBADAgGxIBgQAwIBsSAYEAMCAbEgGBADAgGxIBgQAwIBsSAYEAMiAFFwIAYEAyIAeHOAjbsggAY86c8AAAAAElFTkSuQmCC',
      'large' => 'iVBORw0KGgoAAAANSUhEUgAAAmwAAAFeCAQAAACSxouVAAADfElEQVR42u3UQREAAAjDMObfKxZAB1wioY+mpwBeibEBxgZgbADGBmBsAMYGGBuAsQEYG4CxARgbYGwAxgZgbADGBmBsgLEBGBuAsQEYG4CxARgbYGwAxgZgbADGBmBsgLEBGBuAsQEYG4CxAcYGYGwAxgZgbADGBhgbgLEBGBuAsQEYG4CxAcYGYGwAxgZgbADGBhgbgLEBGBuAsQEYG2BsAMYGYGwAxgZgbICxARgbgLEBGBuAsQEYG2BsAMYGYGwAxgZgbICxARgbgLEBGBuAsQHGBmBsAMYGYGwAxgYYm7EBxgZgbADGBmBsAMYGGBuAsQEYG4CxARgbYGwAxgZgbADGBmBsgLEBGBuAsQEYG4CxARgbYGwAxgZgbADGBmBsgLEBGBuAsQEYG4CxAcYGYGwAxgZgbADGBhgbgLEBGBuAsQEYG4CxAcYGYGwAxgZgbADGBhgbgLEBGBuAsQEYG2BsAMYGYGwAxgZgbICxARgbgLEBGBuAsQEYG2BsAMYGYGwAxgZgbICxARgbgLEBGBuAsQHGBmBsAMYGYGwAxgYYmwiAsQEYG4CxARgbgLEBxgZgbADGBmBsAMYGGBuAsQEYG4CxARgbYGwAxgZgbADGBmBsAMYGGBuAsQEYG4CxARgbYGwAxgZgbADGBmBsgLEBGBuAsQEYG4CxAcYGYGwAxgZgbADGBmBsgLEBGBuAsQEYG4CxAcYGYGwAxgZgbADGBhgbgLEBGBuAsQEYG2BsAMYGYGwAxgZgbADGBhgbgLEBGBuAsQEYG2BsAMYGYGwAxgZgbICxARgbgLEBGBuAsQHGBmBsAMYGYGwAxgZgbICxARgbgLEBGBuAsQHGBmBsAMYGYGwAxgYYG4CxARgbgLEBGBtgbMYGGBuAsQEYG4CxARgbYGwAxgZgbADGBmBsgLEBGBuAsQEYG4CxAcYGYGwAxgZgbADGBmBsgLEBGBuAsQEYG4CxAcYGYGwAxgZgbADGBhgbgLEBGBuAsQEYG2BsAMYGYGwAxgZgbADGBhgbgLEBGBuAsQEYG2BsAMYGYGwAxgZgbICxARgbgLEBGBuAsQHGBmBsAMYGYGwAxgZgbICxARgbgLEBGBuAsQHGBmBsAMYGYGwAxgYYG4CxARgbgLEBGBtgbMYGGBuAsQEYG4CxARgbYGwAxgZgbADGBmBsgLEBXLX8iaOD8uydngAAAABJRU5ErkJggg=='
    );

		return $context;
	}

	public function theme_supports() {

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support(
			'html5', array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		// add_theme_support(
		// 	'post-formats', array(
		// 		'aside',
		// 		'image',
		// 		'video',
		// 		'quote',
		// 		'link',
		// 		'gallery',
		// 		'audio',
		// 	)
		// );

		add_theme_support( 'menus' );
  }

  public function register_menus() {
    register_nav_menus(array(
      'primary_navigation' => 'Primary Navigation',
      'footer_navigation' => 'Footer Navigation'
    ));
  }

  public function register_widgets() {
    register_sidebar(array(
      'name' => 'Footer Widgets',
      'id' => 'footer_widgets',
      'before_widget' => '<section class="widget">',
      'after_widget' => '</section>',
      'before_title' => '<header class="widget-header"><h3>',
      'after_title' => '</h3></header>',
    ) );
  }

	/** This Would return 'foo bar!'.
	 *
	 * @param string $text being 'foo', then returned 'foo bar!'.
	 */
	public function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	/** This is where you can add your own functions to twig.
	 *
	 * @param string $twig get extension.
	 */
	public function add_to_twig( $twig ) {
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter( new Twig_SimpleFilter( 'myfoo', array( $this, 'myfoo' ) ) );
		return $twig;
	}

}

new StarterSite();
