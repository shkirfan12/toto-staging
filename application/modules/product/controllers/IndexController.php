<?php

class Product_IndexController extends Zend_Controller_Action {

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

    public function indexAction() {
        $objProduct = new Admin_Model_Product();
//        $productList = $objProduct->productList();
//        $this->view->productList = $productList;
        $tier1List = $objProduct->fetchTier1List();
        $this->view->tier1List = $tier1List;
    }

}

