<?php

class RegisterTaxonomy{

    private $inflect;
    private $textdomain;    
    
	function __construct( $slug, $post_type, $labels = false, $args = false ){
		
        $this->inflect      = new Inflect();
        $this->textdomain   = wp_get_theme()->get( 'TextDomain' );
        
        $slug               = strtolower( $slug );                      // book
        $slug_pluralize     = $this->inflect->pluralize( $slug );       // books
        $uctitle            = ucwords( $slug );                         // Book
        $uctitle_pluralize  = $this->inflect->pluralize( $uctitle );    // Books
		
		$default_labels = array(
			'name'              => _x( $uctitle_pluralize, 'taxonomy general name', $this->textdomain ),
			'singular_name'     => _x( $uctitle, 'taxonomy singular name', $this->textdomain ),
			'search_items'      => __( 'Search ' . $uctitle_pluralize, $this->textdomain ),
			'all_items'         => __( 'All ' . $uctitle_pluralize, $this->textdomain ),
			'parent_item'       => __( 'Parent ' . $uctitle, $this->textdomain ),
			'parent_item_colon' => __( 'Parent ' . $uctitle . ':', $this->textdomain ),
			'edit_item'         => __( 'Edit ' . $uctitle, $this->textdomain ),
			'update_item'       => __( 'Update ' . $uctitle, $this->textdomain ),
			'add_new_item'      => __( 'Add New ' . $uctitle, $this->textdomain ),
			'new_item_name'     => __( 'New '  . $uctitle . ' Name', $this->textdomain ),
			'menu_name'         => __( $uctitle, $this->textdomain ),
		);
		if( !$labels ){
			$labels = $default_labels;
		}else{
			$labels = array_merge( $default_labels, $labels );
		}		
	
		$default_args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => $slug ),
		);
		if( !$args ){
			$args = $default_args;
		}else{
			$args = array_merge( $default_args, $args );
		}
        
		register_taxonomy( $slug, $post_type, $args  );
	}    
    
}