<?php
defined( 'ABSPATH' ) or exit;

/**
 * Plugin Name: Delete Debug.log Button
 * Plugin URI: https://breakfast.company
 * Description: Adds a button to the Admin Bar that deletes wp-content/debug.log
 * Version: 0.2.0
 * Author: Corey Salzano
 * Author URI: https://coreysalzano.com
 * Text Domain: delete-debug-log-button
 * Domain Path: /languages
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

class Breakfast_Delete_Debug_Log_Button
{
	const SLUG = 'delete_debug_log';

	function ajax_callback()
	{
		if( empty( $_POST ) || ! isset( $_POST['_ajax_nonce'] )
			|| ! wp_verify_nonce( $_POST['_ajax_nonce'], self::SLUG ) )
		{
			wp_send_json_error( new WP_Error( '400', 'Unable to satisfy request' ) );
		}

		@unlink( ABSPATH . '/wp-content/debug.log' );

		wp_send_json_success();
		exit;
	}

	function maybe_include_javascript( $hook )
	{
		if( ! $this->show_button() )
		{
			//Not adding the button
			return;
		}

		wp_enqueue_script( self::SLUG, plugin_dir_url( __FILE__ ) . 'button.js' );
		wp_localize_script( self::SLUG, 'ddlb', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( self::SLUG )
		) );
	}

	function maybe_add_button( $wp_admin_bar )
	{
		if( ! $this->show_button() )
		{
			//Not adding the button
			return;
		}

		//Add the button
		$spinner_html = '<span class="spinner spinner-delete-debug-log"></span>';

		$args = array(
			'id'    => self::SLUG,
			'title' => __( 'Delete debug.log', 'delete-debug-log-button' ) . $spinner_html,
			'href'  => '#',
			'meta'  => array(
				'class' => 'custom-button-class'
			)
		);
		$wp_admin_bar->add_node( $args );

		$view_node = array(
			'parent' => self::SLUG,
			'id'     => 'view_debug_log',
			'title'  => __( 'View debug.log', 'delete-debug-log-button' ),
			'href'   => add_query_arg( 'viewer', 'debug-log', $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ),
		);
		$wp_admin_bar->add_node( $view_node );
	}

	public static function debug_log_path()
	{
		return ABSPATH . '/wp-content/debug.log';
	}

	/**
	 * @return boolean
	 */
	private function show_button()
	{
		return current_user_can( 'manage_options' ) && file_exists( self::debug_log_path() );
	}

	function hooks()
	{
		//Adds the button to the Admin Bar
		add_action( 'admin_bar_menu', array( $this, 'maybe_add_button' ), 555 );

		add_action( 'admin_enqueue_scripts', array( $this, 'maybe_include_javascript' ) );

		//Handles the AJAX request
		add_action( 'wp_ajax_' . self::SLUG, array( $this, 'ajax_callback' ) );

		include_once( __DIR__ . '/viewer.php' );
		$viewer = new Breakfast_Delete_Debug_Log_Viewer();
		$viewer->add_hooks();
	}
}
$delete_button_293087420963742634623945 = new Breakfast_Delete_Debug_Log_Button();
$delete_button_293087420963742634623945->hooks();
