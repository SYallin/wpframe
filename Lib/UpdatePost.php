<?php

class UpdatePost extends AddPost{

    public $id;    
    
    function __construct( $id, array $data = null ){
        $this->id = $id;
        $data['ID'] = $id;
        return $this->insert_item( $data );
    }
    
    public function insert_item( array $data ){
        wp_update_post( $data );
        
        if( $data[ 'meta' ] ){
            $this->update_meta_values( $data[ 'meta' ], $this->id );
        }
        
        if( $data[ 'taxonomies' ] ){
            $this->update_taxonomies( $data[ 'taxonomies' ], $this->id );
        }
        
        if( $data[ 'feautured' ] ){
            $this->set_feautured_image( $data[ 'feautured' ], $this->id  );
        }        
        
        return $this->id;
    }
    
    public function set_feautured_image( $data, $id ){
        set_post_thumbnail( $this->id, $data );
    }    
}
