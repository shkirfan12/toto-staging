<?php

class Admin_ImportManagementController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $this->_options = $this->getInvokeArg('bootstrap')->getOptions();
    }

    public function checklogin() {
        $loginSession = new Zend_Session_Namespace('login');
        if (empty($loginSession->userId)) {
            $this->_redirect('/admin');
        }
    }

    public function indexAction() {
        // action body
        $this->checklogin();
        Zend_Layout::startMvc(array(
            'layoutPath' => APPLICATION_PATH . '/layouts/scripts/',
            'layout' => 'adminLayout'
        ));
        $request = $this->getRequest();
        $data = $request->getParams();
        
        $mastergridModel = new Admin_Model_Mastergrid ();
        $record = $mastergridModel->fetchLastModifiedImportFile();
        $this->view->modifiedRecord = $record;
    }

}

