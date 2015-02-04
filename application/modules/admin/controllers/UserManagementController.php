<?php

class Admin_UserManagementController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $this->_options = $this->getInvokeArg('bootstrap')->getOptions();
    }

    /* public function checklogin() {
      $loginSession = new Zend_Session_Namespace('login');
      if (empty($loginSession->userId)) {
      $this->_redirect('/admin');
      }
      } */

    private function _filterPaginatorUrl() {
        $params = $this->_request->getParams();
        extract($params, EXTR_PREFIX_ALL, "param");
        $urlParam['controller'] = $param_controller;
        $urlParam['action'] = $param_action;
        $urlParam['module'] = $param_module;
        return $urlParam;
    }
    
    public function indexAction() {
        // action body
        //   $this->checklogin();
        Zend_Layout::startMvc(array(
            'layoutPath' => APPLICATION_PATH . '/layouts/scripts/',
            'layout' => 'adminLayout'
        ));
        error_reporting(0);
      
        $objAdmin = new Admin_Model_Users();
        $result = $objAdmin->getuserinfo($this->_getParam("page"));
        $count = count($result);
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($result);
        $paginator->setItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);
        $this->view->urlParam = $this->_filterPaginatorUrl();
        $this->view->userinfo = $paginator;
        $this->view->usercount = $count;
        $state= $objAdmin->getstate();
        $this->view->state = $state;
//        $result = $objAdmin->getuserinfo();
//        $this->view->userinfo = $result;
    }

     public function usermanagementAction() {
        // action body
        //   $this->checklogin();
        Zend_Layout::startMvc(array(
            'layoutPath' => APPLICATION_PATH . '/layouts/scripts/',
            'layout' => 'adminLayout'
        ));
        error_reporting(0);
      
        $objAdmin = new Admin_Model_Users();
        $result = $objAdmin->getuserinfo($this->_getParam("page"));
        $count = count($result);
        $page = $this->_getParam('page', 1);
        $paginator = Zend_Paginator::factory($result);
        $paginator->setItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);
        $this->view->urlParam = $this->_filterPaginatorUrl();
        $this->view->userinfo = $paginator;
        $this->view->usercount = $count;
        $state= $objAdmin->getstate();
        $this->view->state = $state;
//        $result = $objAdmin->getuserinfo();
//        $this->view->userinfo = $result;
    }
    public function saveuserAction() {
//        $this->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $data = $_POST;

        $objAdmin = new Admin_Model_Users();
        $objAdmin->insertuserinfo($data);
//         print_r($result); die;
        $this->_redirect('admin/user-management/index');
//        print_r($data);
    }

    public function saveclientAction() {
//        $this->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $data = $_POST;

        $objAdmin = new Admin_Model_Users();
        $objAdmin->insertclientinfo($data);
//         print_r($result); die;
        $this->_redirect('admin/user-management/clientmanagement');
//        print_r($data);
    }

    public function edituserAction() {
        $this->getHelper('layout')->disableLayout();
        $request = $this->getRequest();
        $data = $request->getParams();
        $objAdmin = new Admin_Model_Users();
        $editid = $request->getParam('id');
        $result = $objAdmin->edituser($editid);
        $this->view->userinfo = $result;
        $state= $objAdmin->getstate();
        $this->view->state = $state;
    }

    public function editclientAction() {
        $this->getHelper('layout')->disableLayout();
        $request = $this->getRequest();
        $data = $request->getParams();
        $objAdmin = new Admin_Model_Users();
        $editid = $request->getParam('id');
        $result = $objAdmin->editclient($editid);
        $this->view->clientinfo = $result;
    }

    public function updateuserAction() {
        $this->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $request = $this->getRequest();
        $data = $request->getParams();
        $editid = $request->getParam('editId');
        $firstname = $request->getParam('edit_fname');
        $lastname = $request->getParam('edit_lname');

        $email = $request->getParam('edit_email');
        $usertype = $request->getParam('edit_utype');
        $state= $request->getParam('edit_state');
        $objAdmin = new Admin_Model_Users();
        $objAdmin->updateuser($firstname, $lastname, $email, $usertype,$state, $editid);
        $this->_redirect('/admin/user-management/index/');
    }

    public function updateclientAction() {
        $this->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $request = $this->getRequest();
        $data = $request->getParams();
         $editid = $request->getParam('editId'); 
        $cname = $request->getParam('edit_cname'); 
       $payment = $request->getParam('edit_payment');

        $status = $request->getParam('edit_status');
        $address = $request->getParam('address');
        $objAdmin = new Admin_Model_Users();
        $objAdmin->updateclient($cname, $payment, $status, $address, $editid);
        $this->_redirect('/admin/user-management/clientmanagement/');
    }

    public function sendmailAction() {
        $this->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
       $ans = $_POST['selected'];
        $objAdmin = new Admin_Model_Users();
        $result = $objAdmin->sendmail($ans);

        foreach ($result as $val) {
            $firstname = $val['fname'];
            $lastname = $val['lname'];
            $to = $val['email_id'];
            $password = $this->random_string(6);
            $subject = "Your My Green PayBack password has been reset";
            $mailBody = '<p>Hi,' . $firstname . " " . $lastname . '</p>
                    <p>Your My Green PayBack password has been reset.Your new password is' . $password .
                    '</p>
<p>&nbsp;</p>
<p>Thanks, My Green PayBack Team</p>';
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
            $headers .= "From: 'My green pay back Team' <vidya.patankar@azularc.com>\r\n";

            if (mail($to, $subject, $mailBody, $headers)) {
                $objAdmin->newpassword($to, md5($password));
            } 
            }
       
    }

    function random_string($length) {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }

    public function checkmailAction() {
        $this->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $email = $_POST['mail'];
        $objAdmin = new Admin_Model_Users();
        $result = $objAdmin->checkmail($email);
        return $result;
    }

    public function clientmanagementAction() {
        Zend_Layout::startMvc(array(
            'layoutPath' => APPLICATION_PATH . '/layouts/scripts/',
            'layout' => 'adminLayout'
        ));

        $objAdmin = new Admin_Model_Users();
        $result = $objAdmin->getclientinfo();
        $this->view->clientinfo = $result;
    }

}

