<?php
/*
 * проверить права пользователя
 * фичеред
 * таксономия
 * acf репитеры 
 *
 */

class AddPost extends AddItem{

    protected $default_data = array();
    protected $name;    
    public $taxonomy_append = true;
    
    function __construct( $name, array $data = null ){
        $this->name = $name;
        $this->set_default_data(
            array(
                'post_type'     => $this->name,
                'post_status'   => 'publish',
                'post_author'   => 1,
                )
        );
        if( $data ){
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
        $item_id = wp_insert_post( $data );
        
        if( $data[ 'meta' ] ){
            $this->update_meta_values( $data[ 'meta' ], $item_id );
        }
        
        if( $data[ 'taxonomies' ] ){
            $this->update_taxonomies( $data[ 'taxonomies' ], $item_id );
        }
        
        if( $data[ 'feautured' ] ){
            $this->set_feautured_image( $data[ 'feautured' ], $item_id  );
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
        set_post_thumbnail( $item_id, $data );
    }
    
    
}
