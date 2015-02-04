<?php

class Default_MyProfileController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $this->_options = $this->getInvokeArg('bootstrap')->getOptions();
        $sessPasscode = new Zend_Session_Namespace('passcode');
         $sessUser = new Zend_Session_Namespace('user');
            if (!empty($sessUser->user_Id)) {
               $sessPasscode->validPasscode = true;
            }
            
          if(!isset($sessPasscode->validPasscode) ||  $sessPasscode->validPasscode == false){
              // $this->_redirect('/index/index');
               $this->_redirect('/');
            }
    }
    
    public function checklogin() {
        $sessUser = new Zend_Session_Namespace('user');
        if(empty($sessUser->user_Id)){
             $this->_redirect('/login/account');
        }
        
    }

    public function indexAction() {
        $this->checklogin();
        $request = $this->getRequest();
        $data = $request->getParams();
        $sessUser = new Zend_Session_Namespace('user');  
        $objProfile = new Default_Model_Profile();
        $profileDetail = $objProfile->fetchUserProfileDetails($sessUser->user_Id); 
        $userStats = $objProfile->getuserinfo($sessUser->user_Id); 
        if (!empty($data['profile_email'])) {
            $profile_email = $data['profile_email'];
            $userType = $data['userType'];
            $result = $objProfile->updateProfile($profile_email, $userType, $sessUser->user_Id);
            $profileDetail = $objProfile->fetchUserProfileDetails($sessUser->user_Id); 
        }
         $this->view->profileDetail = $profileDetail;
         $this->view->userStats = $userStats;
    }
    /* Reset Password << */
    public function changePasswordAction(){
        $this->_helper->layout()->disableLayout();
    }

     public function ajaxPasswordAction(){
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();

        $request       = $this->getRequest();
        $data          = $request->getParams();
        $objCommon     = new Default_Model_CommonClass();
        $objAccount    = new Login_Model_Account();
        $sessUser = new Zend_Session_Namespace('user');  

        if ($request->isPost()) {
            $hidAction = strtolower($objCommon->cleanVars($data['hidAction']));
            if ($hidAction == 'changepwd') {
                $oldPwd     = $data['pwdOldPwd'];
                $newPwd     = $objCommon->cleanVars($data['pwdNewPwd']);
                $confirmPwd = $objCommon->cleanVars($data['pwdConfirmPwd']);
               /* $auth = Zend_Auth::getInstance();
                $username = $auth->getIdentity()->name; 
                if ($auth->hasIdentity()) {
                    $sessUserId = $sessUser->user_Id;
                } else {
                    header('Location: ' . $this->view->baseUrl() . '/user');
                }*/
                $errors = $this->_validatePassword($oldPwd, $newPwd, $confirmPwd, $sessUser->user_Id);
                if (empty($errors)) {
                    if ($objAccount->changePassword($newPwd, $sessUser->user_Id)) {
                        $message = 'Success';
                        echo $message;
                    }
                }else{
                    echo json_encode($errors);
                }
            }
        }
    }
    private function _validatePassword($oldPwd, $newPwd, $confirmPwd, $userId){
        $objAccount    = new Login_Model_Account();
        $oldPwd        = md5($oldPwd);
        $errors        = array();
        
        if (empty($userId)) {
            $errors[] = "Invalid User ID";
            return $errors;
        }
        if (empty($oldPwd)) {
            $errors[] = "Invalid Old password";
        } else {
            if (!$objAccount->checkOldPassword($oldPwd, $userId))
                $errors[] = "Invalid Old Password";
        }
        if (empty($newPwd))
            $errors[] = " Invalid New password";
        else if ($oldPwd == $newPwd)
            $errors[] = "Passwords donot match";
        if (empty($confirmPwd))
            $errors[] = "Invalid Confirm password";
        else if ($newPwd != $confirmPwd)
            $errors[] = "Passwords donot match";
        return $errors;
    }
    public function ajaxAckResetPwdAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        
        $msg_ini    = new Zend_Config_Ini(APPLICATION_PATH . '/configs/messages.ini', $options = null);
        $config     = Zend_Registry::get('config');
        $adminEmail = $config->email->config->adminEmail;
        $adminName  = $config->email->config->adminName;
        
        $request    = $this->getRequest();
        $data       = $request->getParams();
        
        $mailFromEmailId = $adminEmail;   // vidya.patankar@azularc.com
        $mailFromName    = $adminName;  // United Way Admin
       
        $mailToEmailId   = $data['email']; 
        $userName        = ucfirst($data['fname']); 
        $mailSubject     = 'Acknowledgement of Reset Password';
        $mailBody        = '<p>Dear ' . $userName .',</p>
                            <p>Your United Way password has been reset!</p>
                            <p>&nbsp;</p>
                            <p>Thanks &amp; Regards,</p>
                            <p>MGP Team.</p>';
        $objCommonFunction = new Default_Model_GlobalFunctions_CommonFunctions();
        $sendmail          = $objCommonFunction->sendEmail($mailFromEmailId, $mailFromName, $mailToEmailId, $mailSubject, $mailBody);
        
        if (!empty($sendmail)) {
            $successMsg = $msg_ini->resetPassword->registerEmail;
            echo $successMsg;
            exit;
        } else {
            $errorMsg = $msg_ini->resetPassword->mail_send_error;
            echo $errorMsg;
            exit;
        }
    }

    /* Reset Password >> */
    

}

