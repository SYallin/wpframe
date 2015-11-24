<?php
class ACFOptions {
	
	public $options_array;
	
	function __construct( $base = 'options_' ){
		global $wpdb;
		$query = "SELECT `option_name`, `option_value` FROM `wp_options` WHERE `option_name` LIKE '" . $base . "%'";
		$wpdb->query($query);
		$this->options_array = $wpdb->get_results( $query,  OBJECT_K );
	}
	
	function find( $name ){
		return new ACFOptionsValues( $name, $this->options_array );
	}	
}