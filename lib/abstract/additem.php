<?php
abstract class AddItem {
    
    public function insert_item( array $data ){
    }
    
    public function insert_items( array $data ){
        foreach( $data as $item ){
            $this->insert_item( $item );
        }
    }    
    
    public function update_meta_values( array $fields, $item_id  ){
        foreach( $fields as $field_name => $value ){
            $this->update_meta( $item_id, $field_name, $value );
        }
    }       
    
    public function update_meta( $item_id, $field_name, $value = '' ){
    }
}