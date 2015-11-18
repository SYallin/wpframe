<?php
/*
 * предусмотреть название пост тайпа из нескольких слов
 *
 *
 */

class Core {
	
	function get_classes_url(){
		return get_template_directory() . '/core/';
	}
	
	function get_textdomain(){
        $theme = wp_get_theme();
        return ( $theme->get( 'TextDomain' ) );
	}
	
	function inflect(){
		require_once 'inflect.php';
		return new Inflect();
	}
	
    function register_post_type( $slug, $labels = false, $args = false ){
			
			$slug	= strtolower( $slug ); // book
			$slug_pluralize = $this->inflect()->pluralize( $slug ); // books
			$uctitle = ucwords( $slug ); // Book
			$uctitle_pluralize = $this->inflect()->pluralize( $uctitle ); // Books

			$default_labels = array(
				'name'               => _x( $uctitle_pluralize, 'post type general name', $this->textdomain ),
				'singular_name'      => _x( $uctitle, 'post type singular name', $this->textdomain ),
				'menu_name'          => _x( $uctitle_pluralize, 'admin menu', $this->textdomain ),
				'name_admin_bar'     => _x( $uctitle, 'add new on admin bar', $this->textdomain ),
				'add_new'            => _x( 'Add New', $slug, $this->textdomain ),
				'add_new_item'       => __( 'Add New ' . $uctitle, $this->textdomain ),
				'new_item'           => __( 'New ' . $uctitle, $this->textdomain ),
				'edit_item'          => __( 'Edit ' . $uctitle, $this->textdomain ),
				'view_item'          => __( 'View ' . $uctitle, $this->textdomain ),
				'all_items'          => __( 'All ' . $uctitle_pluralize, $this->textdomain ),
				'search_items'       => __( 'Search ' . $uctitle_pluralize, $this->textdomain ),
				'parent_item_colon'  => __( 'Parent ' . $uctitle_pluralize . ':', $this->textdomain ),
				'not_found'          => __( 'No ' . $slug_pluralize . ' found.', $this->textdomain ),
				'not_found_in_trash' => __( 'No ' . $slug_pluralize . ' found in Trash.', $this->textdomain )
			);
			if( !$labels ){
				$labels = $default_labels;
			}else{
				$labels = array_merge( $default_labels, $labels );
			}
		
			$default_args = array(
				'labels'             => $labels,
				'description'        => __( 'Description.', $this->textdomain ),
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => $slug ),
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => null,
				'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields' )
			);
			if( !$args ){
				$args = $default_args;
			}else{
				$args = array_merge( $default_args, $args );
			}
			register_post_type( $slug, $args );
    }
	
	
	function register_taxonomy( $slug, $post_type, $labels = false, $args = false ){
		
		$slug	= strtolower( $slug ); // book
		$slug_pluralize = $this->inflect()->pluralize( $slug ); // books
		$uctitle = ucwords( $slug ); // Book
		$uctitle_pluralize = $this->inflect()->pluralize( $uctitle ); // Books
		
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