<?php

trait CheckRequiredClassesTrait{
    static function check_required_classes( array $classes ){
        foreach( $classes as $class){
            if( !class_exists( $class ) ){
                echo "Class $class not found in " . __CLASS__ . "\n";
            }else{
                if( method_exists( $class, 'check_required_classes' )  ){
                    $class::check_required_classes( $class::$required_classes );
                }
            }
        }
    }
}