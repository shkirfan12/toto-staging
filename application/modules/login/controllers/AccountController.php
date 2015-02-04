<?php

class Login_AccountController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $this->_options = $this->getInvokeArg('bootstrap')->getOptions();
    }

    public function indexAction() {
        
    }

    public function ajaxChkValidUserAction() {
        $this->_helper->viewRenderer->setNoRender();  // Disable The View
        $this->_helper->layout()->disableLayout(); // Disable The layout
        $request = $this->getRequest();
        $data = $request->getParams();
        $objAccount = new Login_Model_Account();
        $sessUser = new Zend_Session_Namespace('user');

        if (!empty($data)) {
            $isExists = $objAccount->fetchUserInfo($data['email'], $data['pwd']);
            if (empty($isExists)) {
                echo "error";
                die;
            } else {
                $email = base64_encode($data['email']);
                $pass = base64_encode($data['pwd']);
                if (isset($data['remember']) && $data['remember'] != 0) {
                    $hour = time() + 864000; /* set for 10 days */
                    setcookie('email', $email, $hour, '/');
                    setcookie('password', $pass, $hour, '/');
                }
                $sessPasscode = new Zend_Session_Namespace('passcode');
                // if (!empty($sessUser->user_Id)) {
                $sessPasscode->validPasscode = true;
                //}
                if (empty($sessUser->user_Id)) { //Login user
                    $sessUser->user_Id = $isExists['user_id'];
                    $sessUser->email_Id = $isExists['email_id'];
                    $sessUser->fname = $isExists['fname'];
                    $sessUser->lname = $isExists['lname'];
                    //echo $sessUser->user_Id;
                    if ($isExists['password'] == md5('changeme$p@y$9reen')) {
                        echo 'changeme';
                        die;
                    }
                } else { // Dummy user
                    $objAccount->updateDummyUserIdByOriginalUser($sessUser->user_Id, $isExists['user_id']);
                    $sessUser->user_Id = $isExists['user_id'];
                    $sessUser->email_Id = $isExists['email_id'];
                    $sessUser->fname = $isExists['fname'];
                    $sessUser->lname = $isExists['lname'];
                    echo "project";
                    die;
                }
            }
        }
    }

    public function logoutAction() {
        /* $loginSession = new Zend_Session_Namespace("login");
          Zend_Session::namespaceUnset('login');
          Zend_Session::destroy("login", true);
          $this->_redirect('/admin'); */

        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();

        $sessUser = new Zend_Session_Namespace('user');
        Zend_Session::namespaceUnset('user');
        Zend_Session::destroy("user", true);    //  Zend_Session::destroy();
        // setcookie('email', '', time() - 30);
        //setcookie('password', '', time() - 30);
        $this->_redirect('/');
    }

    /*
     * Forgot Password 
     */

    public function forgotPasswordAction() {
        error_reporting(0);
        $this->_helper->layout()->disableLayout(); // Disable The layout
        $config = Zend_Registry::get('config');
        $adminEmail = $config->email->config->adminEmail;
        $adminName = $config->email->config->adminName;

        $msg_ini = new Zend_Config_Ini(APPLICATION_PATH . '/configs/messages.ini', $options = null);
        $form = new Login_Form_Forgotpassword();
        $this->view->form = $form;

        $request = $this->getRequest();
        $data = $request->getParams();
        $objField = new Login_Model_Account();

        if ($this->_request->isPost()) {
            if ($form->isValid($request->getPost())) {
                if (isset($_POST['submit'])) {
                    $userInfo = $objField->getUserInfoByEmailnType($data['email']);
                    $userName = ucfirst($userInfo['fname']) . " " . ucfirst($userInfo['lname']);

                    if (!empty($userInfo)) {
                        // $randomKey = $objField->str_rand(16, 'alphanum');
                        // $randomKey = 'changeme$p@y$9reen'; 
                        $randomKey = mt_rand();
                        $newPwd = $objField->changePassword($randomKey, $userInfo['user_id']);

                        $mailFromEmailId = $adminEmail;   // vidya.patankar@azularc.com
                        $mailFromName = $adminName;  // Azularc Admin
                        $mailToEmailId = $userInfo['email_id']; // vidyaayazularc@gmail.com
                        $mailSubject = 'Reset Password';
                        $mailBody = '<p>Dear ' . $userName . ' ,</p>
                                            <p> Your MGP password has been reset!</p>
                                            <p>Please use this temporary password for login</p>
                                            <p>Email : <b>' . $mailToEmailId . '</b></p>
                                            </p>password : <b>' . $randomKey . '</b></p>
                                            <p>&nbsp;</p>
                                            <p>Thanks &amp; Regards,</p>
                                            <p>MGP Team</p>';
                        $objCommonFunction = new Default_Model_GlobalFunctions_CommonFunctions();
                        $sendmail = $objCommonFunction->sendEmail($mailFromEmailId, $mailFromName, $mailToEmailId, $mailSubject, $mailBody);

                        if (!empty($sendmail)) {
                            /* echo $message = $msg_ini->offer->mail_send_success;
                              exit; */
                            $successMsg = $msg_ini->forgotPassword->registerEmail_forgot;
                            $this->view->successMsg = $successMsg;
                        } else {
                            $errorMsg = $msg_ini->forgotPassword->mail_send_error;
                            $this->view->errorMsg = $errorMsg;
                            exit;
                        }
                    } else {
                        $errorMsg = $msg_ini->forgotPassword->unregisterEmail;
                        $this->view->errorMsg = $errorMsg;
                    }
                }
            }
        }
    }

}
