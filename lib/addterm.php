<?php

class AddTerm {
    
    protected $taxonomy;
    
    function __construct( $taxonomy, $term = false, array $args = null ){
        $this->taxonomy = $taxonomy;

        if( $term ){
            return $this->insert_item( $term );
        }else{
            return false;
        }
    }    
    
    public function insert_item( $term, $data = null ){
        $item_id = wp_insert_term( $term, $this->taxonomy, $data );
        
        
        
        return $item_id;
    }    
}