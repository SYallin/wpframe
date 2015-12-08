<?php

define( 'WPFRAME_DIR', 'wpframe' );

spl_autoload_register( 'wpframe_class_loader' );

function wpframe_class_loader( $class_name ) {
	
	if (file_exists( get_template_directory() . '/' . WPFRAME_DIR . '/' . $class_name .'/' . $class_name . '.php' )) {
		require_once( get_template_directory() . '/' . WPFRAME_DIR . '/' . $class_name .'/' . $class_name . '.php' );
	}elseif( file_exists( get_template_directory() . '/' . WPFRAME_DIR . '/lib/' . $class_name . '.php' ) ){
		require_once( get_template_directory() . '/' . WPFRAME_DIR . '/lib/' . $class_name . '.php' );
	}
}

