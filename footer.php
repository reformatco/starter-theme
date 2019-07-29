<?php

$timberContext = $GLOBALS['timberContext']; // @codingStandardsIgnoreFile
if ( ! isset( $timberContext ) ) {
	throw new \Exception( 'Timber context not set in footer.' );
}
$timberContext['content'] = ob_get_contents();
// $timberContext['footer_widgets'] = Timber::get_widgets('footer_widgets');

ob_end_clean();
$templates = array( 'page-plugin.twig' );
Timber::render( $templates, $timberContext );
