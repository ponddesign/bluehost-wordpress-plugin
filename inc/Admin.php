<?php
/**
 * Register Admin page and features.
 *
 * @package WPPluginBluehost
 */

namespace Bluehost;

/**
 * \Bluehost\Admin
 */
final class Admin {

	/**
	 * Register functionality using WordPress Actions.
	 */
	public function __construct() {
		/* Add Page to WordPress Admin Menu. */
		\add_action( 'admin_menu', array( __CLASS__, 'page' ) );
		/* Load Page Scripts & Styles. */
		\add_action( 'load-toplevel_page_bluehost', array( __CLASS__, 'assets' ) );
		/* Load i18 files */
		\add_action( 'init', array( __CLASS__, 'load_text_domain' ), 100 );
		/* Add Links to WordPress Plugins list item. */
		\add_filter( 'plugin_action_links_wp-plugin-bluehost/wp-plugin-bluehost.php', array( __CLASS__, 'actions' ) );
		/* Add inline style to hide subnav link */
		\add_action( 'admin_head', array( __CLASS__, 'admin_nav_style' ) );

		if ( isset( $_GET['page'] ) && strpos( filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING ), 'bluehost' ) >= 0 ) { // phpcs:ignore
			\add_action( 'admin_footer_text', array( __CLASS__, 'add_brand_to_admin_footer' ) );
		}
	}

	/**
	 * Subpages to register with add_submenu_page().
	 *
	 * Order or array items determines menu order.
	 *
	 * @return array
	 */
	public static function subpages() {
		return array(
			'bluehost#/home'        => __( 'Home', 'wp-plugin-bluehost' ),
			'bluehost#/store'       => __( 'Store', 'wp-plugin-bluehost' ),
			'bluehost#/marketplace' => __( 'Marketplace', 'wp-plugin-bluehost' ),
			'bluehost#/performance' => __( 'Performance', 'wp-plugin-bluehost' ),
			'bluehost#/settings'    => __( 'Settings', 'wp-plugin-bluehost' ),
			'bluehost#/staging'     => __( 'Staging', 'wp-plugin-bluehost' ),
			'bluehost#/help'        => __( 'Help', 'wp-plugin-bluehost' ),
		);
	}

	/**
	 * Add inline script to admin screens
	 *  - hide extra link in subnav
	 */
	public static function admin_nav_style() {
		echo '<style>';
		echo 'ul#adminmenu a.toplevel_page_bluehost.wp-has-current-submenu:after, ul#adminmenu>li#toplevel_page_bluehost.current>a.current:after { border-right-color: #fff !important; }';
		echo 'li#toplevel_page_bluehost > ul > li.wp-first-item { display: none !important; }';
		echo '#wp-toolbar #wp-admin-bar-bluehost-coming_soon .ab-item { padding: 0; }';
		echo '</style>';
	}

	/**
	 * Add WordPress Page to Appearance submenu.
	 *
	 * @return void
	 */
	public static function page() {
		$bluehost_icon = 'data:image/svg+xml;base64,PHN2ZyBpZD0iTGF5ZXJfMSIgZGF0YS1uYW1lPSJMYXllciAxIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA1OC4wMyA1OC4xMyI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiNmZmY7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5iaC13aGl0ZTwvdGl0bGU+PGcgaWQ9Il9Hcm91cF8iIGRhdGEtbmFtZT0iJmx0O0dyb3VwJmd0OyI+PGcgaWQ9Il9Hcm91cF8yIiBkYXRhLW5hbWU9IiZsdDtHcm91cCZndDsiPjxnIGlkPSJfR3JvdXBfMyIgZGF0YS1uYW1lPSImbHQ7R3JvdXAmZ3Q7Ij48cmVjdCBpZD0iX1BhdGhfIiBkYXRhLW5hbWU9IiZsdDtQYXRoJmd0OyIgY2xhc3M9ImNscy0xIiB3aWR0aD0iMTYuMiIgaGVpZ2h0PSIxNi4yMSIvPjxyZWN0IGlkPSJfUGF0aF8yIiBkYXRhLW5hbWU9IiZsdDtQYXRoJmd0OyIgY2xhc3M9ImNscy0xIiB4PSIyMC45MSIgd2lkdGg9IjE2LjIxIiBoZWlnaHQ9IjE2LjIxIi8+PHJlY3QgaWQ9Il9QYXRoXzMiIGRhdGEtbmFtZT0iJmx0O1BhdGgmZ3Q7IiBjbGFzcz0iY2xzLTEiIHg9IjQxLjgyIiB3aWR0aD0iMTYuMjEiIGhlaWdodD0iMTYuMjEiLz48cmVjdCBpZD0iX1BhdGhfNCIgZGF0YS1uYW1lPSImbHQ7UGF0aCZndDsiIGNsYXNzPSJjbHMtMSIgeT0iMjAuOTYiIHdpZHRoPSIxNi4yIiBoZWlnaHQ9IjE2LjIxIi8+PHJlY3QgaWQ9Il9QYXRoXzUiIGRhdGEtbmFtZT0iJmx0O1BhdGgmZ3Q7IiBjbGFzcz0iY2xzLTEiIHg9IjIwLjkxIiB5PSIyMC45NiIgd2lkdGg9IjE2LjIxIiBoZWlnaHQ9IjE2LjIxIi8+PHJlY3QgaWQ9Il9QYXRoXzYiIGRhdGEtbmFtZT0iJmx0O1BhdGgmZ3Q7IiBjbGFzcz0iY2xzLTEiIHg9IjQxLjgyIiB5PSIyMC45NiIgd2lkdGg9IjE2LjIxIiBoZWlnaHQ9IjE2LjIxIi8+PHJlY3QgaWQ9Il9QYXRoXzciIGRhdGEtbmFtZT0iJmx0O1BhdGgmZ3Q7IiBjbGFzcz0iY2xzLTEiIHk9IjQxLjkyIiB3aWR0aD0iMTYuMiIgaGVpZ2h0PSIxNi4yMSIvPjxyZWN0IGlkPSJfUGF0aF84IiBkYXRhLW5hbWU9IiZsdDtQYXRoJmd0OyIgY2xhc3M9ImNscy0xIiB4PSIyMC45MSIgeT0iNDEuOTIiIHdpZHRoPSIxNi4yMSIgaGVpZ2h0PSIxNi4yMSIvPjxyZWN0IGlkPSJfUGF0aF85IiBkYXRhLW5hbWU9IiZsdDtQYXRoJmd0OyIgY2xhc3M9ImNscy0xIiB4PSI0MS44MiIgeT0iNDEuOTIiIHdpZHRoPSIxNi4yMSIgaGVpZ2h0PSIxNi4yMSIvPjwvZz48L2c+PC9nPjwvc3ZnPg==';

		\add_menu_page(
			__( 'Bluehost', 'wp-plugin-bluehost' ),
			__( 'Bluehost', 'wp-plugin-bluehost' ),
			'manage_options',
			'bluehost',
			array( __CLASS__, 'render' ),
			$bluehost_icon,
			0
		);

		foreach ( self::subpages() as $route => $title ) {
			\add_submenu_page(
				'bluehost',
				$title,
				$title,
				'manage_options',
				$route,
				array( __CLASS__, 'render' )
			);
		}
	}

	/**
	 * Render DOM element for React to load onto.
	 *
	 * @return void
	 */
	public static function render() {
		global $wp_version;

		echo '<!-- Bluehost -->' . PHP_EOL;

		if ( version_compare( $wp_version, '5.4', '>=' ) ) {
			echo '<div id="wppbh-app" class="wppbh wppbh_app"></div>' . PHP_EOL;
		} else {
			// fallback messaging for WordPress older than 5.4
			echo '<div id="wppbh-app" class="wppbh wppbh_app">' . PHP_EOL;
			echo '<header class="wppbh-header" style="min-height: 90px; padding: 1rem; margin-bottom: 1.5rem;"><div class="wppbh-header-inner"><div class="wppbh-logo-wrap">' . PHP_EOL;
			echo '<img src="' . esc_url( BLUEHOST_PLUGIN_URL . 'assets/svg/bluehost-logo.svg' ) . '" alt="Bluehost logo" />' . PHP_EOL;
			echo '</div></div></header>' . PHP_EOL;
			echo '<div class="wrap">' . PHP_EOL;
			echo '<div class="card" style="margin-left: 20px;"><h2 class="title">' . esc_html__( 'Please update to a newer WordPress version.', 'wp-plugin-bluehost' ) . '</h2>' . PHP_EOL;
			echo '<p>' . esc_html__( 'There are new WordPress components which this plugin requires in order to render the interface.', 'wp-plugin-bluehost' ) . '</p>' . PHP_EOL;
			echo '<p><a href="' . esc_url( admin_url( 'update-core.php' ) ) . '" class="button component-button is-primary button-primary" variant="primary">' . esc_html__( 'Please update now', 'wp-plugin-bluehost' ) . '</a></p>' . PHP_EOL;
			echo '</div></div></div>' . PHP_EOL;
		}

		echo '<!-- /Bluehost -->' . PHP_EOL;
	}

	/**
	 * Load Page Scripts & Styles.
	 *
	 * @return void
	 */
	public static function assets() {
		$asset_file = BLUEHOST_BUILD_DIR . '/index.asset.php';

		if ( is_readable( $asset_file ) ) {
			$asset = include_once $asset_file;
		} else {
			return;
		}

		\wp_register_script(
			'bluehost-script',
			BLUEHOST_BUILD_URL . '/index.js',
			array_merge( $asset['dependencies'] ),
			$asset['version'],
			true
		);

		\wp_set_script_translations(
			'bluehost-script',
			'wp-plugin-bluehost',
			BLUEHOST_PLUGIN_DIR . '/languages'
		);

		include BLUEHOST_PLUGIN_DIR . '/inc/Data.php';
		\wp_add_inline_script(
			'bluehost-script',
			'var WPPBH =' . \wp_json_encode( Data::runtime() ) . ';',
			'before'
		);

		\wp_register_style(
			'bluehost-style',
			BLUEHOST_BUILD_URL . '/index.css',
			array( 'wp-components' ),
			$asset['version']
		);

		$screen = get_current_screen();
		if ( false !== strpos( $screen->id, 'bluehost' ) ) {
			\wp_enqueue_script( 'bluehost-script' );
			\wp_enqueue_style( 'bluehost-style' );
		}
	}

	/**
	 * Load text domain for plugin
	 *
	 * @return void
	 */
	public static function load_text_domain() {

		\load_plugin_textdomain(
			'wp-plugin-bluehost',
			false,
			BLUEHOST_PLUGIN_DIR . '/languages'
		);

		\load_script_textdomain(
			'bluehost-script',
			'wp-plugin-bluehost',
			BLUEHOST_PLUGIN_DIR . '/languages'
		);
	}

	/**
	 * Add Links to WordPress Plugins list item for Bluehost.
	 *
	 * @param  array $actions - array of action links for Plugin row item.
	 * @return array
	 */
	public static function actions( $actions ) {
		return array_merge(
			array(
				'overview' => '<a href="' . \admin_url( 'admin.php?page=bluehost#/home' ) . '">' . __( 'Home', 'wp-plugin-bluehost' ) . '</a>',
				'settings' => '<a href="' . \admin_url( 'admin.php?page=bluehost#/settings' ) . '">' . __( 'Settings', 'wp-plugin-bluehost' ) . '</a>',
			),
			$actions
		);
	}

	/**
	 * Filter WordPress Admin Footer Text "Thank you for creating with..."
	 *
	 * @param string $footer_text footer text
	 * @return string
	 */
	public static function add_brand_to_admin_footer( $footer_text ) {
		$footer_text = \sprintf( \__( 'Thank you for creating with <a href="https://wordpress.org/">WordPress</a> and <a href="https://bluehost.com/about-us">Bluehost</a>.', 'wp-plugin-bluehost' ) );
		return $footer_text;
	}
} // END \Bluehost\Admin