<?php

class Product_ComparisonController extends Zend_Controller_Action {

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
        $request = $this->getRequest();
        $data = $request->getParams();
        
        $productSession = new Zend_Session_Namespace('productSession1');
        $productSession->count = '';
        $productSession->selectedPid='';
        
        if(!empty($data['ids'])){ 
             $objProduct = new Product_Model_Product();
             $productList = $objProduct->fetchCompareIds($data['ids']);
             $this->view->productList = $productList;
             $this->view->ids = $data['ids'];
             
             $objAtt  = new Admin_Model_Attribute();
             $attList    = $objAtt->fetchAttList();
             $subAttList = $objAtt->fetchSubAttList();
             $this->view->attList = $attList;
             $this->view->subAttList = $subAttList;
             
             
        }
    }
    public function compareAction() {
        $this->getHelper('layout')->disableLayout();
        $request = $this->getRequest();
        $data = $request->getParams();
    }
    public function calculateAction() {
        $this->getHelper('layout')->disableLayout();
        $request = $this->getRequest();
        $data = $request->getParams();
        $sessUser = new Zend_Session_Namespace('user');
        
        $objProject = new Default_Model_Project();
        $projectList = $objProject->fetchProjectList($sessUser->user_Id);
        $this->view->projectList = $projectList;
    }
    public function calculateSaveAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();

        $request = $this->getRequest();
        $data = $request->getParams();
        
        $projectId = $data['projectId'];
        $finalCalculate = $data['finalCalculate']; 
        
        $objProject = new Default_Model_Project();
        $projInfoById = $objProject->fetchProjectInfoById($projectId);
        if(!empty($projInfoById['product_id'])) {
            $productList = $projInfoById['product_id'].$finalCalculate;
        }else{
            $productList = $finalCalculate;
        }
        
        $tags = explode(',', $productList);
        $tags = array_values( array_unique($tags));
        $finalvalue = implode(',',$tags); 
        $product_value =  $finalvalue.',';
        $objProject->updateProjectByProductIds($projectId,$product_value);
       
    }

}

