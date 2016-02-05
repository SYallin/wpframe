<?php

define( 'WPFRAME_DIR', dirname( __FILE__ ) );

spl_autoload_register( function($class_name){
	if (file_exists( WPFRAME_DIR . '/' . $class_name .'/' . $class_name . '.php' )) {
		require_once( WPFRAME_DIR . '/' . $class_name .'/' . $class_name . '.php' );
	}elseif( file_exists( WPFRAME_DIR . '/Lib/' . $class_name . '.php' ) ){
		require_once( WPFRAME_DIR . '/Lib/' . $class_name . '.php' );
	}

	$class_arr = explode("_", $class_name);
	$class_path = WPFRAME_DIR;
	foreach($class_arr as $class ){
		$class_path.= '/' . $class;
		
	}
	$class_path.= '.php';

	if (file_exists( $class_path )) {
		require_once( $class_path );
	}
} );
