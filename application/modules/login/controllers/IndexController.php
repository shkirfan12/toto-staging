<?php

class Login_IndexController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
        $this->_options = $this->getInvokeArg('bootstrap')->getOptions();
    }

   /* public function indexAction()
    {
         // action body
     
    }*/
    /*
     * Login
     */
   // public function loginAction() {
    public function indexAction() {
        //$this->_helper->layout()->disableLayout();
        $config   = Zend_Registry::get('config');
        $msg_ini  = new Zend_Config_Ini(APPLICATION_PATH . '/configs/messages.ini', $options = null);
        $sessUser = new Zend_Session_Namespace('user');
        $objField = new Login_Model_Users();
        
        $sessUser->user_role_Id = 
                
        $userTypeList = $objField->fetchUserType(); 
        $this->view->userTypeList = $userTypeList;

        if (isset($sessUser->email_Id)) {
            //header('Location: ' . $this->view->baseUrl() . '/user/admin3');
            if (($sessUser->user_role_Id == '1') || ($sessUser->user_role_Id == '3') || ($sessUser->user_role_Id == '5')) {
                $this->_redirect('/programs');
            } elseif ($sessUser->user_role_Id == '2') {
                $this->_redirect('/providers');
            }
            die;
        }

        $form    = new Login_Form_Login();
        $request = $this->getRequest();
  
        if (isset($_COOKIE['email']) && isset($_COOKIE['password'])) {
           
           // echo $_COOKIE['email'].'--'.$_COOKIE['password']; die;
            $userData   = $objField->fetchUserDetail($_COOKIE['email']);
            $encryptPwd = $config->salt->key . '#' . $userData['password'];

            if ($userData['email_id'] == $_COOKIE['email'] && $_COOKIE['password'] == $encryptPwd) {

                if ($this->_process($_COOKIE)) { 
                    // We're authenticated! Redirect to the home page
                    $this->_redirect('/user/admin1');
                } else {
                    /*setcookie('email', '', time() - 30, '/');
                    setcookie('password', '', time() - 30, '/');*/
                    setcookie('email', '', time() - 30);
                    setcookie('password', '', time() - 30);
                   $this->_redirect('/login'); // back to login page
                }
            }
        } else {

            if ($request->isPost()) {

                if ($form->isValid($request->getPost())) { //echo "third"; echo "<br/>";
                    $get = $form->getValues(); //echo '<pre>';print_r($get); 

                    if ($this->_process($form->getValues())) { //echo "one"; die;
                       $encryptPwd = $config->salt->key . '#' . md5($get['password']);

                         if (isset($get['chkremember']) && $get['chkremember'] != 0) {
                            $seconds = time() + (60 * 60 * 24 * 15); // 15 days
                            setcookie('email', $get['email'], $seconds);
                            setcookie('password', $encryptPwd, $seconds);
                        }

                        // We're authenticated! Redirect to the home page
                        //$this->_redirect('/user/admin2');
                        if( ($sessUser->user_role_Id == '1') || ($sessUser->user_role_Id == '3') || ($sessUser->user_role_Id == '5')  ){
                            $this->_redirect('/programs');
                        }elseif ($sessUser->user_role_Id == '2'){
                            $this->_redirect('/providers');
                        }
                    } else {  //echo "second"; die;
                        $message = $msg_ini->loginError->invalidLogin;
                        $this->view->loginMessage = $message;
                        //$sessUser->msgLoginError = "Incorrect Email, Password OR Inactive User";
                    }
                }
            }
        }
        $this->view->form = $form;
    }

    /*
     * Login call
     */
    protected function _process($values) {
        // Get our authentication adapter and check credentials
        $adapter = $this->_getAuthAdapter();
        $adapter->setIdentity($values['email']);
        $adapter->setCredential(md5($values['password']));
     

        $auth   = Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter);

        //create object of user model
        $objField = new Login_Model_Users(); 
        $msg_ini  = new Zend_Config_Ini(APPLICATION_PATH . '/configs/messages.ini', $options = null);
        $userData = $objField->fetchUserDetail($values['email'],$values['userType']); 
      
        if ($result->isValid()) { 
            if(!empty($userData)){
            $data = array(
                'email_id'         => $values['email'],
                'user_id'          => $userData['user_id'],
                'first_name'       => $userData['first_name'],
                'last_name'        => $userData['last_name'],
                'user_type'        => $userData['user_type'],
                'user_type_id'     => $userData['user_type_id'],
            );

            $auth->getStorage()->write($data);
            $sessUser = new Zend_Session_Namespace('user');
            $sessUser->user_Id      = $userData['user_id'];
            $sessUser->email_Id     = $values['email'];
            $sessUser->first_name   = $userData['first_name'];
            $sessUser->last_name    = $userData['last_name'];
            $sessUser->role_name    = $userData['user_type'];
            $sessUser->user_role_Id = $userData['user_type_id'];

            return true;
            }
        }
        return false;
    }

    /*
     * Login Authentication
     */

    protected function _getAuthAdapter() {
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);

        $authAdapter->setTableName('users')
                ->setIdentityColumn('email_id')
                ->setCredentialColumn('password');

        return $authAdapter;
    }

    /*
     * Logout
     */

    /*public function logoutAction() {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();

        Zend_Session::destroy();
        setcookie('email', '', time() - 30);
        setcookie('password', '', time() - 30);

        $this->_redirect('/'); // back to login page
    }*/

    
     /*public function ajaxLoginValidationAction() {
        $this->_helper->viewRenderer->setNoRender();  // Disable The View
        $this->_helper->layout()->disableLayout(); // Disable The layout
        $objField = new Login_Model_Users(); 

        $request = $this->getRequest();
        if ($request->isGet()) {
            $data = $request->getParams();
            echo "<pre>"; print_r($data); die;
        }
        
    }*/
    
}

