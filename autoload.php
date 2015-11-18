<?php

function __autoload( $class_name ) {
	
	$class_name = strtolower( $class_name );

	if (file_exists( get_template_directory() . '/modules/' . $class_name .'/' . $class_name . '.php' )) {
		require_once( get_template_directory() . '/modules/' . $class_name .'/' . $class_name . '.php' );
	}elseif( file_exists( get_template_directory() . '/modules/lib/' . $class_name . '.php' ) ){
		require_once( get_template_directory() . '/modules/lib/' . $class_name . '.php' );
	}elseif( file_exists( get_template_directory() . '/modules/lib/abstract/' . $class_name . '.php' ) ){
		require_once( get_template_directory() . '/modules/lib/abstract/' . $class_name . '.php' );
	}
}