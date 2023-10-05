<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_VERSION', '2.8.1' );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

if ( ! function_exists( 'hello_elementor_setup' ) ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function hello_elementor_setup() {
		if ( is_admin() ) {
			hello_maybe_update_theme_version_in_db();
		}

		if ( apply_filters( 'hello_elementor_register_menus', true ) ) {
			register_nav_menus( [ 'menu-1' => esc_html__( 'Header', 'hello-elementor' ) ] );
			register_nav_menus( [ 'menu-2' => esc_html__( 'Footer', 'hello-elementor' ) ] );
		}

		if ( apply_filters( 'hello_elementor_post_type_support', true ) ) {
			add_post_type_support( 'page', 'excerpt' );
		}

		if ( apply_filters( 'hello_elementor_add_theme_support', true ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support(
				'html5',
				[
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
					'script',
					'style',
				]
			);
			add_theme_support(
				'custom-logo',
				[
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				]
			);

			/*
			 * Editor Style.
			 */
			add_editor_style( 'classic-editor.css' );

			/*
			 * Gutenberg wide images.
			 */
			add_theme_support( 'align-wide' );

			/*
			 * WooCommerce.
			 */
			if ( apply_filters( 'hello_elementor_add_woocommerce_support', true ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'hello_elementor_setup' );

function hello_maybe_update_theme_version_in_db() {
	$theme_version_option_name = 'hello_theme_version';
	// The theme version saved in the database.
	$hello_theme_db_version = get_option( $theme_version_option_name );

	// If the 'hello_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
	if ( ! $hello_theme_db_version || version_compare( $hello_theme_db_version, HELLO_ELEMENTOR_VERSION, '<' ) ) {
		update_option( $theme_version_option_name, HELLO_ELEMENTOR_VERSION );
	}
}

if ( ! function_exists( 'hello_elementor_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function hello_elementor_scripts_styles() {
		$min_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		if ( apply_filters( 'hello_elementor_enqueue_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor',
				get_template_directory_uri() . '/style' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( apply_filters( 'hello_elementor_enqueue_theme_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor-theme-style',
				get_template_directory_uri() . '/theme' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_scripts_styles' );

if ( ! function_exists( 'hello_elementor_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function hello_elementor_register_elementor_locations( $elementor_theme_manager ) {
		if ( apply_filters( 'hello_elementor_register_elementor_locations', true ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'hello_elementor_register_elementor_locations' );

if ( ! function_exists( 'hello_elementor_content_width' ) ) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function hello_elementor_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'hello_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'hello_elementor_content_width', 0 );

if ( is_admin() ) {
	require get_template_directory() . '/includes/admin-functions.php';
}

/**
 * If Elementor is installed and active, we can load the Elementor-specific Settings & Features
*/

// Allow active/inactive via the Experiments
require get_template_directory() . '/includes/elementor-functions.php';

/**
 * Include customizer registration functions
*/
function hello_register_customizer_functions() {
	if ( is_customize_preview() ) {
		require get_template_directory() . '/includes/customizer-functions.php';
	}
}
add_action( 'init', 'hello_register_customizer_functions' );

if ( ! function_exists( 'hello_elementor_check_hide_title' ) ) {
	/**
	 * Check hide title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function hello_elementor_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'hello_elementor_page_title', 'hello_elementor_check_hide_title' );

if ( ! function_exists( 'hello_elementor_add_description_meta_tag' ) ) {
	/**
	 * Add description meta tag with excerpt text.
	 *
	 * @return void
	 */
	function hello_elementor_add_description_meta_tag() {
		$post = get_queried_object();

		if ( is_singular() && ! empty( $post->post_excerpt ) ) {
			echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $post->post_excerpt ) ) . '">' . "\n";
		}
	}
}
add_action( 'wp_head', 'hello_elementor_add_description_meta_tag' );

/**
 * BC:
 * In v2.7.0 the theme removed the `hello_elementor_body_open()` from `header.php` replacing it with `wp_body_open()`.
 * The following code prevents fatal errors in child themes that still use this function.
 */
if ( ! function_exists( 'hello_elementor_body_open' ) ) {
	function hello_elementor_body_open() {
		wp_body_open();
	}
}

/**
 * Change Default Gravatar
 */
add_filter( 'avatar_defaults', 'wpb_new_gravatar' );
function wpb_new_gravatar ($avatar_defaults) {
    $myavatar = '<?php echo get_stylesheet_directory_uri();?>/wp-content/uploads/2023/07/default.png';
    $avatar_defaults[$myavatar] = "Default Gravatar";
    return $avatar_defaults;
}

/**
 * Humans.txt will display in the root of your site
 */
add_action( 'init', 'add_humans_txt_rewrite', 10 );
function add_humans_txt_rewrite() {
    add_rewrite_rule( '^humans\.txt$', 'index.php?humans_txt=true', 'top' );
}

/**
 * Filter the list of public query vars in order to allow the WP::parse_request
 * to register the query variable.
 *
 * @param array $public_query_vars The array of whitelisted query variables.
 *
 * @return array
 */
add_filter( 'query_vars', 'add_humans_txt_query_var', 10, 1 );
function add_humans_txt_query_var( $public_query_vars ) {
    $public_query_vars[] = 'humans_txt';
    return $public_query_vars;
}

/**
 * Hook the parse_request action and serve the humans.txt when custom query variable is set to 'true'.
 *
 * @param WP $wp Current WordPress environment instance
 */
add_action( 'parse_request', 'get_humans_txt_request', 10, 1 );
function get_humans_txt_request( $wp ) {
    if ( isset( $wp->query_vars['humans_txt'] ) && 'true' === $wp->query_vars['humans_txt'] ) {
        header( 'Content-Type: text/plain' );
        echo file_get_contents( get_stylesheet_directory() . '/humans.txt' );
        exit;
    }
}

//Add a LINK tag in the HEAD of your main page
add_action('wp_head', 'add_humans_txt_link');
function add_humans_txt_link() {
    if (is_front_page()) {
        echo '<!-- humans.txt we are people, not machines -->';
        echo "\n" . '<link type="text/plain" rel="author" href="humans.txt" />';
    }
};
/**
 * End of Humans.txt
 */

 /**
  * Add favicons to theme
  */
add_action('wp_head','dogma_add_favicon');
function dogma_add_favicon(){
	echo "\n" . "<!-- Custom Favicons -->" . "\n";
	?>
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_stylesheet_directory_uri();?>/assets/images/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_stylesheet_directory_uri();?>/assets/images/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_stylesheet_directory_uri();?>/assets/images/favicon/favicon-16x16.png">
	<link rel="manifest" href="<?php echo get_stylesheet_directory_uri();?>/assets/images/favicon/site.webmanifest">
	<link rel="mask-icon" href="<?php echo get_stylesheet_directory_uri();?>/assets/images/favicon/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">
    <?php }
