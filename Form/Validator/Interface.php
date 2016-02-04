<?php

interface Form_Validator_Interface{
    
    public function validate( $value );
    
    public function message();
}