<?php
class ACFOptionsValues {
	
	public $basename = 'options_';
	protected $name;
	protected $options_array;
	protected $i = false;
	
	function __construct( $name, $options_array, $i = false, $basename = false ){
		$this->name = $name;
		$this->options_array = $options_array;
		if( $basename ){
			$this->basename = $basename;
		}
		
		if( $i ){
			$this->i = $i;
		}
	}
	
	function get_value(){
		return $this->options_array[ $this->basename . $this->name ]->option_value; 
	}

	function find_sub_field( $i, $name ){
		return new ACFOptionsValues( $name, $this->options_array, $i, $this->basename  . $this->name . '_' . $i . '_' );
	}
}