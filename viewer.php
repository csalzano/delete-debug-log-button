<?php
defined( 'ABSPATH' ) or exit;

class Breakfast_Delete_Debug_Log_Viewer
{
	public function add_hooks()
	{
		add_action( 'init', array( $this, 'load_log' ) );
	}

	public function load_log()
	{
		if( ! empty( $_GET['viewer'] ) && 'debug-log' == $_GET['viewer']
			&& current_user_can( 'manage_options' ) )
		{
			$path = Breakfast_Delete_Debug_Log_Button::debug_log_path();
			if( file_exists( $path ) )
			{
				echo file_get_contents( $path );
			}
			exit;
		}
	}
}
