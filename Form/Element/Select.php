<?php

class Form_Element_Select extends Form_Element_Object{
    
    protected $attributes = array(
        'type' => 'radio',
    );    
    
    public function render(){
        $label_open = '';
        $label_close = '';
        if( $this->label ){
            $label_open = "<label for=\"" . $this->attributes['name'] . "\">" . $this->label;
            $label_close = "</label>\n";
        }
        return $label_open . $this->renderElement() . $label_close;
    }
    
    public function renderElement(){
        $options = '';
        if( $this->options  ){
            foreach( $this->options as $option => $value ){
                $selected = '';
                if( $value == $this->value ){
                    $selected = ' selected="selected"';
                }
                $options .= '<option'.$selected.' value="' . $value . '">' . $option . '</option>';
            }
        }
        return '<select '. $this->renderAttributes(). $this->getValidatorsJSData() . '>' . $options . '</select>';        
    }
}
