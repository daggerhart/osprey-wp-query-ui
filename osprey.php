<?php
/*
 * Plugin Name: Osprey 2
 * Description: Query UI
 * Author: Jonathan Daggerhart
 * Version: 0.2.0
 */

Osprey_Plugin::register();

class Osprey_Plugin {

    public static function register() {
        $plugin = new self();
        $plugin->load();
        add_action('plugins_loaded', [$plugin, 'pluginsLoaded'] );
    }

	/**
	 * Action 'plugins_loaded'
	 */
    public function pluginsLoaded() {
	    add_action( 'init', [ '\Osprey\PostType\Query','register' ] );
	    //add_action( 'cmb2_admin_init', [ '\Osprey\Post\QueryType','metaBoxes' ] );
    }

	/**
	 * Require the autoloader.
	 */
	private function load() {
		$file = dirname( __FILE__ ) . '/vendor/autoload.php';

		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}
}

