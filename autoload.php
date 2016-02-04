<?php

define( 'WPFRAME_DIR', 'wpframe' );

spl_autoload_register( function($class_name){
	
		if (file_exists( get_template_directory() . '/' . WPFRAME_DIR . '/' . $class_name .'/' . $class_name . '.php' )) {
			require_once( get_template_directory() . '/' . WPFRAME_DIR . '/' . $class_name .'/' . $class_name . '.php' );
		}elseif( file_exists( get_template_directory() . '/' . WPFRAME_DIR . '/Lib/' . $class_name . '.php' ) ){
			require_once( get_template_directory() . '/' . WPFRAME_DIR . '/Lib/' . $class_name . '.php' );
		}
	
		$class_arr = explode("_", $class_name);
		$class_path = get_template_directory() . '/' . WPFRAME_DIR;
		foreach($class_arr as $class ){
			$class_path.= '/' . $class;
			
		}
		$class_path.= '.php';

		if (file_exists( $class_path )) {
			require_once( $class_path );
		}
} );