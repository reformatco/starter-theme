<?php



function root_relative_url($input) {
	if (is_feed()) {
		return $input;
	}

	$url = parse_url($input);
	if (!isset($url['host']) || !isset($url['path'])) {
		return $input;
	}
	$site_url = parse_url(network_home_url());  // falls back to home_url

	if (!isset($url['scheme'])) {
		$url['scheme'] = $site_url['scheme'];
	}
	$hosts_match = $site_url['host'] === $url['host'];
	$schemes_match = $site_url['scheme'] === $url['scheme'];
	$ports_exist = isset($site_url['port']) && isset($url['port']);
	$ports_match = ($ports_exist) ? $site_url['port'] === $url['port'] : true;

	if ($hosts_match && $schemes_match && $ports_match) {
		return wp_make_link_relative($input);
	}
	return $input;

}

/**
 * Compare URL against relative URL
 */
function url_compare($url, $rel) {
	$url = trailingslashit($url);
	$rel = trailingslashit($rel);
	return ((strcasecmp($url, $rel) === 0) || root_relative_url($url) == $rel);
}

class AriaWalker extends Walker_Nav_Menu {
  private $cpt; // Boolean, is current post a custom post type
  private $archive; // Stores the archive page for current URL

  public function __construct() {
    add_filter('nav_menu_css_class', array($this, 'cssClasses'), 10, 2);
    add_filter('nav_menu_item_id', '__return_null');
    $cpt           = get_post_type();
    $this->cpt     = in_array($cpt, get_post_types(array('_builtin' => false)));
    $this->archive = get_post_type_archive_link($cpt);
  }

  public function checkCurrent($classes) {
    return preg_match('/(current[-_])|active/', $classes);
  }

  public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : "\n";

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'depth-' . $depth;

		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';


		$id = apply_filters( 'nav_menu_item_id', $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= sprintf( '%s<li%s%s%s>',
			$indent,
			$id,
			$class_names,
			in_array( 'menu-item-has-children', $item->classes ) ? '' : ''
		);

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		// print_r($item->classes);

		if ($this->archive) {
			if ( url_compare($this->archive, $item->url) ) {
				$atts['aria-current'] = 'page';
			}
		}

		if( in_array( 'menu-item-has-children', $item->classes ) ){
			$atts['aria-haspopup'] = "true";
		}

		if( $item->current ){
			$atts['aria-current'] = 'page';

		}

		// print_r($item);

		if( ( isset($atts['aria-haspopup']) && isset($args->sidebar) && $args->sidebar ) && ( $item->current || $item->current_item_ancestor ) ){

			if( $item->current || $item->current_item_ancestor ){
				$atts['aria-expanded'] = "true";
			}else{
				$atts['aria-expanded'] = "false";
			}

    }

    if( isset($atts['aria-haspopup']) && !isset($args->sidebar) ){
      $atts['aria-expanded'] = "false";
    }



		// $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );



		$item_output = $args->before;
		$item_output .= '<a'. $attributes .' class="menu-item-link">';
		$item_output .= $args->link_before . $title . $args->link_after;
		if( in_array( 'menu-item-has-children', $item->classes ) ){
			if( isset($args->no_button_focus) ){
				$button_tabindex="-1";
			}else{
				$button_tabindex="0";
			}
			$item_output .= "<button tabindex=\"".$button_tabindex."\"><span class=\"screen-reader-text\">Show sub menu for \"".$title."\"</span></button>";
		}
		$item_output .= '</a>';

		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

  // @codingStandardsIgnoreStart
  public function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {

    $element->is_subitem = ((!empty($children_elements[$element->ID]) && (($depth + 1) < $max_depth || ($max_depth === 0))));

    if ($element->is_subitem) {
      foreach ($children_elements[$element->ID] as $child) {
        if ($child->current_item_parent || url_compare($this->archive, $child->url)) {
          $element->classes[] = 'active';
        }
      }
    }

    $element->is_active = (!empty($element->url) && strpos($this->archive, $element->url));

    if ($element->is_active) {
      $element->classes[] = 'active';
	}

    parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
  }
  // @codingStandardsIgnoreEnd

  public function cssClasses($classes, $item) {
    $slug = sanitize_title($item->title);

	// Fix core `active` behavior for custom post types


    if ($this->cpt) {
      $classes = str_replace('current_page_parent', 'is-parent', $classes);

      if ($this->archive) {
        if (url_compare($this->archive, $item->url)) {
          $classes[] = 'active';
        }
      }
    }

    $classes = preg_replace('/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', 'active', $classes);
	$classes = preg_replace('/^((menu|page)[-_\w+]+)+/', '', $classes);


    // Re-add core `menu-item` class
    $classes[] = 'menu-item';

    // Re-add core `menu-item-has-children` class on parent elements
    if ($item->is_subitem) {
      $classes[] = 'menu-item-has-children';
    }

    // Add `menu-<slug>` class
    $classes[] = 'menu-' . $slug;

    $classes = array_unique($classes);
    $classes = array_map('trim', $classes);

    return array_filter($classes);
  }

  function start_lvl(&$output, $depth = 0, $args = array() ){
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent";
		// if( $args->sub_wrap ):
		// 	$output .= "<div class=\"sub-menu-group\">";
		// endif;
		$output .= $args->submenu_before."<ul class=\"sub-menu\" aria-label=\"submenu\">\n";
	}

	function end_lvl(&$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent";
		// if( $args->sub_wrap ):
		// 	$output .= "</div>";
		// endif;
		$output .= "</ul>\n".$args->submenu_after;
	}


}

/**
 * Clean up wp_nav_menu_args
 *
 * Remove the container
 * Remove the id="" on nav menu items
 */
function nav_menu_args($args = '') {
  $nav_menu_args = [];
  $nav_menu_args['container'] = false;

  if (!$args['items_wrap']) {
    $nav_menu_args['items_wrap'] = '<ul class="%2$s" role="menubar">%3$s</ul>';
  }

  return array_merge($args, $nav_menu_args);
}
add_filter('wp_nav_menu_args', 'nav_menu_args');
add_filter('nav_menu_item_id', '__return_null');


class Aria_Walker_Page extends Walker_Page {

	public function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {
		if ( isset( $args['item_spacing'] ) && 'preserve' === $args['item_spacing'] ) {
			$t = "\t";
			$n = "\n";
		} else {
			$t = '';
			$n = '';
		}
		if ( $depth ) {
			$indent = str_repeat( $t, $depth );
		} else {
			$indent = '';
		}

		$atts = array(); // create here so we can add top level

		$css_class = array( 'page-item', 'page-item-' . sanitize_title($page->post_title) );

		$args['link_before'] = '<span>';

		if ( isset( $args['pages_with_children'][ $page->ID ] ) ) {
			$css_class[] = 'has-children';
			$atts['aria-haspopup'] = 'true';
			$atts['aria-expanded'] = 'false';
			$args['link_after'] ="</span><button><span class=\"screen-reader-text\">Show sub menu for \"".$page->post_title."\"</span></button>";
		}else{
			$args['link_after'] ="</span>";
		}

		if ( ! empty( $current_page ) ) {
			$_current_page = get_post( $current_page );
			if ( $_current_page && in_array( $page->ID, $_current_page->ancestors ) ) {
				$css_class[] = 'is-ancestor';
			}
			if ( $page->ID == $current_page ) {
				$css_class[] = 'current-page-item';
			} elseif ( $_current_page && $page->ID == $_current_page->post_parent ) {
				$css_class[] = 'is-parent';
			}
		} elseif ( $page->ID == get_option( 'page_for_posts' ) ) {
			$css_class[] = 'is-parent';
		}

		if( in_array('is-ancestor',$css_class) || ( in_array('has-children',$css_class) && in_array('current-page-item',$css_class) ) ){
			$atts['aria-expanded'] = 'true';
		}

		$css_classes = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );
		$css_classes = $css_classes ? ' class="' . esc_attr( $css_classes ) . '"' : '';

		if ( '' === $page->post_title ) {
			/* translators: %d: ID of a post */
			$page->post_title = sprintf( __( '#%d (no title)' ), $page->ID );
		}

		$args['link_before'] = empty( $args['link_before'] ) ? '' : $args['link_before'];
		$args['link_after']  = empty( $args['link_after'] ) ? '' : $args['link_after'];


		$atts['href'] = get_permalink( $page->ID );
		$atts['role'] = 'menuitem';
		$atts['aria-current'] = ( $page->ID == $current_page ) ? 'page' : '';
		$atts['class'] = 'page-item-link';

		$atts = apply_filters( 'page_menu_link_attributes', $atts, $page, $depth, $args, $current_page );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value       = esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$output .= $indent . sprintf(
			'<li%s><a%s>%s%s%s</a>',
			$css_classes,
			$attributes,
			$args['link_before'],
			/** This filter is documented in wp-includes/post-template.php */
			apply_filters( 'the_title', $page->post_title, $page->ID ),
			$args['link_after']
		);

		if ( ! empty( $args['show_date'] ) ) {
			if ( 'modified' == $args['show_date'] ) {
				$time = $page->post_modified;
			} else {
				$time = $page->post_date;
			}

			$date_format = empty( $args['date_format'] ) ? '' : $args['date_format'];
			$output     .= ' ' . mysql2date( $date_format, $time );
		}
	}

	function start_lvl(&$output, $depth = 0, $args = array() ){
       $indent = str_repeat("\t", $depth);
       $output .= "\n$indent<ul class=\"sub-menu\" aria-label=\"submenu\">\n";
   }

   function end_lvl(&$output, $depth = 0, $args = array() ) {
       $indent = str_repeat("\t", $depth);
       $output .= "$indent</ul>\n";
   }

}
