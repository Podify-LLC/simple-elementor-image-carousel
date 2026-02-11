<?php
/**
 * Plugin Name: Simple Elementor Image Carousel
 * Description: Adds a lightweight Elementor image carousel widget using Swiper (Elementor compatible).
 * Version: 1.0.0
 * Author: Podify Inc.
 * Requires at least: 6.0
 * Requires PHP: 8.2
 * Text Domain: seic
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

final class SEIC_Plugin {

	const VERSION = '1.0.0';
	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		// Hook into Elementor's asset registration to ensure handles exist early
		add_action( 'elementor/frontend/after_register_styles', array( $this, 'register_styles' ) );
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_scripts' ) );

		// Also hook into wp_enqueue_scripts as a fallback for non-Elementor contexts
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets_fallback' ), 5 );
		
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );

		new \SEIC\Github_Updater(
			__FILE__,
			'podify-inc',
			'simple-elementor-image-carousel',
			'PODIFY_GITHUB_TOKEN'
		);
	}

	public function register_styles() {
		$base_url = plugin_dir_url( __FILE__ );
		wp_register_style(
			'seic-carousel',
			$base_url . 'assets/css/carousel.css',
			array(),
			self::VERSION
		);
	}

	public function register_scripts() {
		$base_url = plugin_dir_url( __FILE__ );

		// Register bundled Swiper JS (Zero dependencies for faster loading)
		wp_register_script(
			'seic-swiper-lib',
			$base_url . 'assets/js/swiper-bundle.min.js',
			array(), // Swiper itself does not depend on jQuery
			self::VERSION,
			true
		);

		// JS depends on Elementor frontend, our bundled Swiper, and jQuery
		wp_register_script(
			'seic-carousel',
			$base_url . 'assets/js/carousel.js',
			array( 'elementor-frontend', 'seic-swiper-lib', 'jquery' ),
			self::VERSION,
			true
		);
	}

	public function register_assets_fallback() {
		$this->register_styles();
		$this->register_scripts();
	}

	public function register_widgets( $widgets_manager ) {
		require_once __DIR__ . '/widgets/class-image-carousel-widget.php';
		$widgets_manager->register( new \SEIC_Image_Carousel_Widget() );
	}
}

require_once __DIR__ . '/admin/class-admin-page.php';
new SEIC_Admin_Page();

SEIC_Plugin::instance();