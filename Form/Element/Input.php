<?php

class Form_Element_Input extends Form_Element_Object{
    
    protected $type = 'text';
    
    public function setType( $type ){
        $this->type = $type;
    }
    
    public function render(){
        $label = '';
        if( $this->label ){
            $label = "<label for=\"" . $this->attributes['name'] . "\">" . $this->label ."</label>\n";
        }
        return $label . $this->renderElement();
    }
    
    public function renderElement(){
        if( $this->value ){
            $value = 'value="'. $this->value .'" ';
        }elseif( $this->options && $this->options['option'] ){
            $value = 'value="' . $this->options['option'] . '" ';
        }else{
            $value = 'value="" ';
        }
        return '<input '. $this->renderAttributes(). $value . $this->getValidatorsJSData() . '>';
    }
}