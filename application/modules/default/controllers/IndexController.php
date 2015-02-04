<?php

class Default_IndexController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $this->_options = $this->getInvokeArg('bootstrap')->getOptions();
    }

    public function indexAction() {

//        if (isset($_COOKIE['email']) && isset($_COOKIE['password'])) {
//
//            $objAccount = new Login_Model_Account();
//            $sessUser = new Zend_Session_Namespace('user');
//            $email = base64_decode($_COOKIE['email']);
//            $pass = base64_decode($_COOKIE['password']);
//
//            $isExists = $objAccount->fetchUserInfo($email, $pass);
//            if (!empty($isExists)) {
//                $sessPasscode = new Zend_Session_Namespace('passcode');
//                $sessPasscode->validPasscode = true;
//                if (empty($sessUser->user_Id)) { //Login user
//                    $sessUser->user_Id = $isExists['user_id'];
//                    $sessUser->email_Id = $isExists['email_id'];
//                    $sessUser->fname = $isExists['fname'];
//                    $sessUser->lname = $isExists['lname'];
//                } else { // Dummy user
//                    $objAccount->updateDummyUserIdByOriginalUser($sessUser->user_Id, $isExists['user_id']);
//                    $sessUser->user_Id = $isExists['user_id'];
//                    $sessUser->email_Id = $isExists['email_id'];
//                    $sessUser->fname = $isExists['fname'];
//                    $sessUser->lname = $isExists['lname'];
//                }
//                 $this->_redirect('/product/search');
//                 die;
//            }
//        }
    }

}
