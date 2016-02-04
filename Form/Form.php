<?php


class Form extends Form_Element_Element{

    public $action = '/';
    public $method = 'post';
    public $ajax = false;

    protected $elements = array();
    protected $data = false;
    protected $isValid = false;
    protected $validator = false;
    protected $ajaxDataType = 'json';
    
    public function __construct(){

    }
    
    public function setAction( $action ){
        $this->action = $action;
        return $this;
    }
    
    public function setMethod( $method ){
        $this->method = $method;
        return $this;
    }

    public function addElement( Form_Element_Element $element ){
        $this->elements[] = $element;
        return $this;
    }
    
    public function setData( $data ){
        $this->data = $data;
        
        foreach( $this->elements as $element ){
            $element->setValue( $data[ $element->getAttribute('name') ] );
        }
        
        return $this;
    }
    
    protected function setValidator(){
        if( !$this->validator ){
            $this->validator = new Form_Validator;
        }
    }
    
    public function isValid(){
        $this->setValidator();
        $this->validator->setData( $this->data );
        $this->validator->setElements( $this->elements );
        $this->isValid = $this->validator->validate();
        return $this->isValid;
    }
    
    public function getValidator(){
        return $this->validator;
    }    
    
    public function getResult(){
        return $this->validator->getResult();
    }
    
    public function getMessages(){
        return $this->validator->getMessages();
    }

    public function setAjaxDataType( $ajaxDataType ){
        $this->ajaxDataType = $ajaxDataType;
        return $this;
    }    
    
    
    
    public function render(){
        $form = '';
        foreach( $this->elements as $element ){
            $form .= $element->render();
        }
        return '<form data-wp-form '. $this->renderFormAttributes() . ' >' . $form . '</form>';
    }
    
    public function renderFormAttributes(){
        $ajax = '';
        if( $this->ajax ){
            $ajax = 'data-ajax="enable" data-ajaxtype="' . $this->ajaxDataType . '" ';
        }
        
        return 'method="' . $this->method . '" action="' . $this->action . '"  '.$this->renderAttributes() . $ajax;
    }
    
    public function setAjax( $ajax ){
        $this->ajax = $ajax;
        return $this;
    }
    
    
    
}
