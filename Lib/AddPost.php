<?php

class AddPost{

    protected $default_data = array();
    protected $name;    
    public $taxonomy_append = false;
    public $attachment_id = 0;
    public $post_id = 0;
    
    function __construct( $name = 'post', array $data = null ){
        $this->name = $name;
        $this->set_default_data(
            array(
                'post_type'     => $this->name,
                'post_status'   => 'publish',
                'post_author'   => 1,
                )
        );
        if( $data  ){
            return $this->insert_item( $data );
        }else{
            return false;
        }
    }
    
    public function set_default_data( array $data ){
        $this->default_data = array_merge( $data, $this->default_data );
    }
    
    public function get_default_data(){
        return $this->default_data;
    }    
    
    public function cleare_default_data(){
        $this->default_data = array();
    }        
    
    public function insert_item( array $data ){
        $data = array_merge( $data, $this->default_data );
        $this->post_id = wp_insert_post( $data );
        
        if( $data[ 'meta' ] ){
            $this->update_meta_values( $data[ 'meta' ], $this->post_id );
        }
        
        if( $data[ 'taxonomies' ] ){
            $this->update_taxonomies( $data[ 'taxonomies' ], $this->post_id );
        }
        
        if( $data[ 'feautured' ] ){
            $this->set_feautured_image( $data[ 'feautured' ], $this->post_id  );
        }        
        
        return $item_id;
    }
    
    public function update_meta( $item_id, $field_name, $value = '' ){
        if ( empty( $value ) OR ! $value ){
            delete_post_meta( $item_id, $field_name );
        }elseif ( ! get_post_meta( $item_id, $field_name ) ){
            add_post_meta( $item_id, $field_name, $value );
        }else{
            update_post_meta( $item_id, $field_name, $value );
        }
    }
    
    public function update_taxonomies( array $data, $item_id ){
        foreach( $data as $taxonomy => $terms ){
            wp_set_object_terms( $item_id, $terms, $taxonomy, $this->taxonomy_append );
        }
    }
    
    public function set_taxonomy_append( $value ){
        $this->taxonomy_append = $value;
    }
    
    public function set_feautured_image( $data, $item_id ){
        if ( $item_id ) {
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );        
        
            $this->attachment_id = media_handle_upload( $data, $item_id );
            set_post_thumbnail( $item_id,  $this->attachment_id );
        }else{
            return false;
        }
    }
    
    public function update_meta_values( array $fields, $item_id  ){
        foreach( $fields as $field_name => $value ){
            $this->update_meta( $item_id, $field_name, $value );
        }
    }      
}
