<?php

class Form_Validator_Email implements Form_Validator_Interface{
     
     protected $status = true;
     
     public function validate( $value ){
          if( $value ){
               return $this->status = true;
          }else{
               return $this->status = false;
          }
     }
     
     public function message(){
          return __( 'Email error', 'wpframe' );
     }
}