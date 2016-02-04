<?php

class Form_Element_Button extends Form_Element_Input{
    
    public function render(){
        return $this->renderElement();
    }
    
    public function renderElement(){
        $value = '';
        if( $this->options && $this->options['option'] ){
            $value = 'value="'. $this->options['option'] .'" ';
        }else{
            $value = 'value="" ';
        }        
        return '<input type="' . $this->type . '" '. $this->renderAttributes(). $value . '>';
    }
}
