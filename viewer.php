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
		if( ! empty( $_GET['viewer'] ) && 'debug-log' == $_GET['viewer'] )
		{
			echo file_get_contents( Breakfast_Delete_Debug_Log_Button::debug_log_path() );
			exit;
		}
	}
}
