<?php
/**
 * TIC Product Custom Meta
 *
 * Plugin Name: TIC Product Custom Meta
 * Plugin URI:  https://wordpress.org/plugins/tic-product-meta/
 * Description: Enables the WordPress TIC Product Custom Meta
 * Version:     1.0.0
 * Author:      autocircle
 * Author URI:  https://github.com/autocircled/
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: tic-product-meta
 * Domain Path: /languages
 * Requires at least: 4.9
 * Requires PHP: 5.2.4
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

if ( ! defined( 'ABSPATH' ) )
{
    die( 'Invalid request.' );
}
 
if ( ! class_exists( 'TIC_Product_Meta' ) ) :
class TIC_Product_Meta {

    /**
	 * TIC_Product_Meta version.
	 *
	 * @var string
	 * @since 1.2.0
	 */
	public $version = '1.0.0';

    /**
	 * This plugin's instance
	 *
	 * @var TIC_Product_Meta The one true TIC_Product_Meta
	 * @since 1.0
	 */
	private static $instance;

    /**
	 * Main TIC_Product_Meta Instance
	 *
	 * Insures that only one instance of TIC_Product_Meta exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @return TIC_Product_Meta The one true TIC_Product_Meta
	 * @since 1.0.0
	 * @static var array $instance
	 */
	public static function init() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof TIC_Product_Meta ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

    /**
	 * Return plugin version.
	 *
	 * @return string
	 * @since 1.2.0
	 * @access public
	 **/
	public function get_version() {
		return $this->version;
	}

    /**
	 * Plugin URL getter.
	 *
	 * @return string
	 * @since 1.2.0
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Plugin path getter.
	 *
	 * @return string
	 * @since 1.2.0
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Plugin base path name getter.
	 *
	 * @return string
	 * @since 1.2.0
	 */
	public function plugin_basename() {
		return plugin_basename( __FILE__ );
	}

    /**
	 * Initialize plugin for localization
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function localization_setup() {
		load_plugin_textdomain( 'tic-product-meta', false, plugin_basename( dirname( __FILE__ ) ) . '/i18n/languages' );
	}

    /**
	 * Determines if the wc active.
	 *
	 * @return bool
	 * @since 1.0.0
	 *
	 */
	public function is_wc_active() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		return is_plugin_active( 'woocommerce/woocommerce.php' ) == true;
	}

    /**
	 * WooCommerce plugin dependency notice
	 * @since 1.2.0
	 */
	public function wc_missing_notice() {
		if ( ! $this->is_wc_active() ) {
			$message = sprintf( __( '<strong>TIC Product Meta</strong> requires <strong>WooCommerce</strong> installed and activated. Please Install %s WooCommerce. %s', 'tic-product-meta' ),
				'<a href="https://wordpress.org/plugins/woocommerce/" target="_blank">', '</a>' );
			echo sprintf( '<div class="notice notice-error"><p>%s</p></div>', $message );
		}
	}

	/**
	 * Define constant if not already defined
	 *
	 * @param string $name
	 * @param string|bool $value
	 *
	 * @return void
	 * @since 1.2.0
	 *
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @access protected
	 * @return void
	 */

	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'tic-product-meta' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @access protected
	 * @return void
	 */

	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'tic-product-meta' ), '1.0.0' );
	}

    /**
	 * TIC_Product_Meta constructor.
	 */
	private function __construct() {
		$this->define_constants();
		register_activation_hook( __FILE__, array( $this, 'activate_plugin' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate_plugin' ) );

		add_action( 'woocommerce_loaded', array( $this, 'init_plugin' ) );
		add_action( 'admin_notices', array( $this, 'wc_missing_notice' ) );
	}

    /**
	 * Define all constants
	 * @return void
	 * @since 1.2.0
	 */
	public function define_constants() {
		$this->define( 'TIC_PRODUCT_META_PLUGIN_VERSION', $this->version );
		$this->define( 'TIC_PRODUCT_META_PLUGIN_FILE', __FILE__ );
		$this->define( 'TIC_PRODUCT_META_PLUGIN_DIR', dirname( __FILE__ ) );
		$this->define( 'TIC_PRODUCT_META_PLUGIN_INC_DIR', dirname( __FILE__ ) . '/includes' );
	}

    /**
	 * Activate plugin.
	 *
	 * @return void
	 * @since 1.2.0
	 */
	public function activate_plugin() {
		// require_once dirname( __FILE__ ) . '/includes/class-tic-product-meta-installer.php';
		// TIC_Product_Meta_Installer::install();
	}

    /**
	 * Deactivate plugin.
	 *
	 * @return void
	 * @since 1.2.0
	 */
	public function deactivate_plugin() {

	}

    /**
	 * Load the plugin when WooCommerce loaded.
	 *
	 * @return void
	 * @since 1.2.0
	 */
	public function init_plugin() {
		$this->includes();
		$this->init_hooks();
	}

    /**
	 * Include required core files used in admin and on the frontend.
	 * @since 1.2.0
	 */
	public function includes() {
		// require_once dirname( __FILE__ ) . '/includes/tic-product-meta-functions.php';

		if ( is_admin() ) {
			// require_once dirname( __FILE__ ) . '/includes/admin/class-tic-product-meta-admin.php';
		}
		do_action( 'tic_product_meta__loaded' );
	}

    /**
	 * Hook into actions and filters.
	 *
	 * @since 1.0.0
	 */
	private function init_hooks() {
		add_action( 'plugins_loaded', array( $this, 'localization_setup' ) );
	}

    /**
	 * When WP has loaded all plugins, trigger the `tic_product_meta__loaded` hook.
	 *
	 * This ensures `tic_product_meta__loaded` is called only after all other plugins
	 * are loaded, to avoid issues caused by plugin directory naming changing
	 *
	 * @since 1.0.0
	 */
	public function on_plugins_loaded() {
		do_action( 'tic_product_meta__loaded' );
	}

}
endif;

/**
 * The main function responsible for returning the one true WC Serial Numbers
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * @return TIC_Product_Meta
 * @since 1.2.0
 */
function tic_product_meta() {
	return TIC_Product_Meta::init();
}

//lets go.
tic_product_meta();