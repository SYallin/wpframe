<?php
// http://framework.zend.com/manual/1.12/ru/zend.form.quickstart.html

    $form = new Zend_Form;
    
    $form->setAction('/resource/process')
         ->setMethod('post');
         
    $form->setAttrib('id', 'login');