<?php
class Form_Validator {
    
    protected $data;
    protected $elements;
    protected $results = array();
    protected $status = true;
    
    public function setData( $data ){
        $this->data = $data;
        return $this;
    }
    
    public function setElements( $elements ){
        $this->elements = $elements;
        return $this;
    }
    
    public function validate(){
        foreach( $this->elements as $element ){
            if( $element->getValidators() && $element->isRequired() ){
                foreach( $element->getValidators() as $priority => $rules ){
                    
                    foreach( $rules as $validator ){
                        
                        $className = 'Form_Validator_' . key( $validator );
                        $validator_obj = new $className;
                        
                        if( !$validator_obj->validate( $this->data[ $element->getAttributes()['name'] ] ) ){
                            $this->status = false;
                        }
                        $this->results[ $element->getAttributes()['name'] ][] = $validator_obj;
                    }                    
                }
            }
        }
        return $this->status;
    }
    
    public function getResult(){
        return $this->results;
    }
    
    public function getMessage( $validatorObj ){
        return $validatorObj->message();
    }
    
    public function getMessages(){
        $messages = '';
        foreach ( $this->results as $name => $result ){
            
            foreach ( $result as $validator ){
                $messages .= $name . ' ' . $validator->message();
            }
        }
        return $messages;
    }    
    
}
