<?php

class Default_RegisterController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $this->_options = $this->getInvokeArg('bootstrap')->getOptions();
    }
    
    public function ajaxChkPasscodeExistanceAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();

        $request = $this->getRequest();
        $data = $request->getParams();
        $objAccount = new Login_Model_Account();
        $sessPasscode = new Zend_Session_Namespace('passcode');
        $sessPasscode->validPasscode = false;
        if ($request->isGet()) {
            if (!empty($data['passcode'])) {
               $existEmail = $objAccount->checkPasscodeExists($data['passcode']);
               if($existEmail == "1"){
                   $sessPasscode->validPasscode = true;
               }
                echo $existEmail;
                die();
            }
        }
    }

    public function ajaxChkEmailExistanceAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();

        $request = $this->getRequest();
        $data = $request->getParams();
        $objAccount = new Login_Model_Account();
        $sessUser = new Zend_Session_Namespace('user');

        if ($request->isGet()) {
            if (!empty($data['emailId'])) {
                if (empty($sessUser->user_Id)) {
                    $existEmail = $objAccount->checkUserEmailExists($data['emailId']);
                } else {
                    $existEmail = $objAccount->checkSessionUserEmailExists($data['emailId'], $sessUser->user_Id);
                }
                if ($existEmail == "1") { // Email Already Exists
                    echo $errMsg = "<label class='error' for='email' generated='true'>This Email already exists.</label>";
                    exit;
                }
            }
        }
    }
    
    public function indexAction() {
        $request = $this->getRequest();
        $data = $request->getParams();
        $objAccount = new Login_Model_Account();
        $sessUser = new Zend_Session_Namespace('user');
       // echo $sessUser->user_Id . '--' . $sessUser->email_Id . '--' . $sessUser->fname;

        if ($this->_request->isPost()) {
            if (empty($sessUser->user_Id)) { //echo "A"; die;
                if (isset($_POST['submit'])) { 
                    $userRecord = $objAccount->enterUserDetails($_POST['fname'], $_POST['lname'], $_POST['emailId'], $_POST['password']);
                    $sessUser->user_Id = $userRecord['user_id'];
                    $sessUser->email_Id = $userRecord['email_id'];
                    $sessUser->fname = $userRecord['fname'];
                    $sessUser->lname = $userRecord['lname'];
                    $this->_redirect('/product/search');
                }
            }else{ // Dummy user update
                 if (isset($_POST['submit'])) { 
                    $userRecord = $objAccount->updateDummyUserDetails($_POST['fname'], $_POST['lname'], $_POST['emailId'], $_POST['password'],$sessUser->user_Id);
                    $sessUser->user_Id = $userRecord['user_id'];
                    $sessUser->email_Id = $userRecord['email_id'];
                    $sessUser->fname = $userRecord['fname'];
                    $sessUser->lname = $userRecord['lname'];
                    $this->_redirect('/project');
                 }
            }
        }
    }


}

