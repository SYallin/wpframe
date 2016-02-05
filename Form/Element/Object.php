<?php

abstract class Form_Element_Object extends Form_Element_Element{

    /**
     * Default priority at which validators are added
     */
    const DEFAULT_PRIORITY = 1;
    
    protected $validators;
    protected $required = false;
    protected $options = array();
    protected $placeholder;
   
    abstract function render();
    abstract function renderElement();

    public function addValidator( $validator, $value = false, $priority = self::DEFAULT_PRIORITY ){
        $this->validators[$priority][] = array( $validator => $value );
        return $this;
    }
    
    public function setRequired(){
        $this->required = true;
        return $this;
    }
    
    public function setMultiOptions( array $options ){
        $this->options = $options;
        return $this;
    }
    
    public function setOption( $option ){
        $this->options = array( 'option' => $option );
        return $this;
    }
    
    public function isRequired(){
        return $this->required;
    }
    
    public function getValidators(){
        return $this->validators;
    }
    
    public function setType( $type ){
        $this->setAttribute( 'type', $type );
        return $this;
    }
    
    public function getValidatorsJSData(){
        if( $this->validators ){
        foreach( $this->validators as $validators ){
            foreach( $validators as $validator ){
                $data[] = '"' . key( $validator ) . '": ' . current($validator) . '';
            }
        }
            return ' data-required=\'{' . implode(",", $data) . '}\'';
        }else{
            return false;
        }
    }
    
    public function setPlaceholder( $placeholder ){
        $this->placeholder = $placeholder;
        return $this;
    }
    
    public function getPlaceholder(){
        return $this->placeholder;
    }      
    
}