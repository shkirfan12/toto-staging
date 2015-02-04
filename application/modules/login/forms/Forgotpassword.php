<?php

/*
 * Login Form
 */

class Login_Form_Forgotpassword extends Zend_Form {

    private $_timeout;
   
    public function init() {
        $msg_ini = new Zend_Config_Ini(APPLICATION_PATH . "/configs/messages.ini",$options=null);
        $this->setName("login");
        $this->setMethod('post');
     
        //We don't want the default decorators
        $this->setDisableLoadDefaultDecorators(true);

        $this->addDecorator('FormElements')
             ->addDecorator('HtmlTag', array('tag' => 'div' ,'class'=>'login-block')) //this adds a <ul> inside the <form>
             ->addDecorator('Form');


        $this->addElement('text', 'email', array(
            'class'=>'input',
            'label' => 'Enter registered email',
            'maxlength' => '100',
            'validators' => array('EmailAddress'),
            'required' => true,
         
        ));
      
        $this->email->addErrorMessage('%value% '.$msg_ini->loginError->invalid_email);
       //$this->email->addErrorMessage('Invalid Email Format.');

        $this->addElement('submit', 'submit', array(
            'required' => false,
            'ignore' => true,
            'label' => '',
            //'class' => 'button',
            'id' => 'submit',
             
          //  'Description'=>('<div align="right"><a href="#">Forgot Password ?</a></div>'),
        ));



        $this->setElementDecorators(array(
            'ViewHelper',
            'Label',
            'Errors',
            new Zend_Form_Decorator_HtmlTag(array('tag' => 'p'))), array('email') //wrap elements in <li>'s
        );

       

       
        $this->setElementDecorators(array(
            'ViewHelper',
            'Label',
            'Errors',
            new Zend_Form_Decorator_HtmlTag(array('tag' => 'p', 'class' => 'login_btn clearfix'))), array('submit') //wrap elements in <li>'s
          
        );

       
        $this->submit->removeDecorator('Label');
    }

}
