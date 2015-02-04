<?php

class Admin_IndexController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $this->_options = $this->getInvokeArg('bootstrap')->getOptions();
    }

    public function indexAction() {
        $this->_helper->layout->disableLayout();
       
        $request = $this->getRequest();
        $data = $request->getParams();
        $param = $_POST;
        $objAdmin = new Admin_Model_Login();
        
        if (isset($data['txtusername']) && isset($data['txtpassword'])) { 
            $isAdmin = $objAdmin->getAdminLogin($data['txtusername'], $data['txtpassword']);  
            
            $loginSession = new Zend_Session_Namespace("login");
            $loginSession->errorMsg = "";
        
            if (!empty($isAdmin)) {
                    $loginSession->userId = $isAdmin["user_id"];
                    $loginSession->fname = $isAdmin["fname"];
                    $loginSession->lname = $isAdmin["lname"];
                    $loginSession->email_id = $isAdmin["email_id"];
                    $loginSession->userType = $isAdmin["userType"];
                    
                if ($isAdmin["userType"] == 'superadmin') {  //Superadmin loggedIn
                    //$this->_redirect('/admin/attribute-management/');
                    header('Location: /admin/landing-page/index/id/'.$loginSession->userId);
                }else{ // Client loggedIn
                    $objLanding = new Admin_Model_Landing();
                    $info = $objLanding->fetchLandingInfoByUserId($loginSession->userId);
                    if(!empty($info)){
                        $clientId = $info['client_id'];
                        header('Location: /admin/landing-page/index/id/'.$clientId);
                    }else{
                        header('Location: /admin/landing-page/index/id/'.$loginSession->userId);
                    }
                     
                }
                
            } else { //Invalid user
                $this->view->errorMessage = "Invalid email or password. Please try again.";
            }
        }
    }

    public function loginAction() {
       
    }

    public function logoutAction() {  
        $loginSession = new Zend_Session_Namespace("login");
        Zend_Session::namespaceUnset('login'); 
       // Zend_Session::destroy("login", true);
        Zend_Session::destroy("login", true);
        $this->_redirect('/admin');
         
        /*$auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        Zend_Session::destroy($remove_cookie = true, $readonly = true);
        setcookie('email', '', time() - 30);
        setcookie('password', '', time() - 30);
        $this->_redirect('/admin');*/
    }

}

