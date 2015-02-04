<?php

/*
 * Login Form
 */

class Login_Form_Login extends Zend_Form {

    private $_timeout;
   
    public function init() {
        $msg_ini = new Zend_Config_Ini(APPLICATION_PATH . "/configs/messages.ini",$options=null);
        $this->setName("login");
        $this->setMethod('post');
     
        $objField     = new Login_Model_Users();
        $userType = $objField->fetchUserType(); 
   
        $type = array(''=>'-- select --');
        foreach ($userType as $key) {
           $type[$key['user_type_id']] = $key['user_type'];
        }
     
        
        //We don't want the default decorators
        $this->setDisableLoadDefaultDecorators(true);

        $this->addDecorator('FormElements')
             ->addDecorator('HtmlTag', array('tag' => 'ul' ,'class'=>'login_form clearfix')) //this adds a <ul> inside the <form>
             ->addDecorator('Form');


        $this->addElement('text', 'email', array(
            'class'=>'input',
            'label' => 'Email',
            'maxlength' => '100',
            'validators' => array('EmailAddress'),
            'required' => true,
         
        ));
      
        $this->email->addErrorMessage($msg_ini->loginError->invalid_email);
        //$this->email->addErrorMessage('%value% '.$msg_ini->loginError->invalid_email);
       //$this->email->addErrorMessage('Invalid Email Format.');


        $this->addElement('password', 'password', array(
             'class'=>'input',
            'label' => 'Password',
            'required' => true,
            
        ));
        $this->password->addErrorMessage($msg_ini->loginError->blank_password);

        
        $this->addElement('checkbox', 'chkremember', array(
                      'label'=>'Remember Me',
                      'checked' => true,
                      'checked' => false,
		   // 'Description'=>('<div align="right"><a href="#">Forgot Password ?</a></div>'),
		)); 

        $this->addElement('select', 'userType', array(
            'multiOptions' => $type,
            'filters' => array('StringTrim'),
            'validators' => array(
                array('StringLength', false, array(0, 100)),
            ),
            'required' => true,
            'label' => 'Programm*',
            'class' => 'select_drop',
        ));
         $this->addElement('submit', 'submit', array(
            'required' => false,
            'ignore' => true,
            'label' => 'submit',
            'onclick' => 'return validateLoginUser();',
            'class' => 'loginbtn1',
          //  'Description'=>('<div align="right"><a href="#">Forgot Password ?</a></div>'),
        ));



        $this->setElementDecorators(array(
            'ViewHelper',
            'Label',
            'Errors',
            new Zend_Form_Decorator_HtmlTag(array('tag' => 'li'))), array('email') //wrap elements in <li>'s
        );

        $this->setElementDecorators(array(
            'ViewHelper',
            'Label',
            'Errors',
            new Zend_Form_Decorator_HtmlTag(array('tag' => 'li', 'class' => 'basic-modal'))), array('password') //wrap elements in <li>'s
        );
        $this->setElementDecorators(array(
            'ViewHelper',
            'Label',
            'Errors',
            new Zend_Form_Decorator_HtmlTag(array('tag' => 'li'))), array('userType') //wrap elements in <li>'s
        );
      

        $this->setElementDecorators(array(
            'ViewHelper',
            'Label',
            'Errors',
            new Zend_Form_Decorator_HtmlTag(array('tag' => 'li')),
            array('Description', array('escape' => false, 'tag' => false, 'placement' => 'append'))), array('chkremember') //wrap elements in <li>'s
        );
        $this->getElement('chkremember')->addDecorator('Label', array('placement' => 'APPEND',"style" =>'float:none;'));

        $this->setElementDecorators(array(
            'ViewHelper',
            'Label',
            'Errors',
            new Zend_Form_Decorator_HtmlTag(array('tag' => 'li', 'class' => 'login_btn clearfix'))), array('submit') //wrap elements in <li>'s
           /*new Zend_Form_Decorator_HtmlTag(array('tag' => 'li', 'class' => 'login_btn clearfix')),
            array('Description', array('escape' => false, 'tag' => false, 'placement' => 'append'))), array('submit')*/ //wrap elements in <li>'s
     
        );

       
        $this->submit->removeDecorator('Label');
    }

}
