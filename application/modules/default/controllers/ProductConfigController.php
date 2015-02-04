<?php

class Default_ProductConfigController extends Zend_Controller_Action {

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
        if (empty($sessUser->user_Id)) {
            $this->_redirect('/login/account');
        }
    }

    public function indexAction() {
        $this->checklogin();
        $request = $this->getRequest();
        $data = $request->getParams();
        $sessUser = new Zend_Session_Namespace('user');
        $objProductConfig = new Default_Model_ProductConfig();

        
        if(!empty($data['baseline-popup'])){
            $this->view->msgBaselinePopup = 'close';
        }
        
        if (!empty($data['pid'])) {
            $productInfo = $objProductConfig->fetchProjectInfoById($data['pid']); 
            $info = $objProductConfig->projInfoFetch($data['pid']); 
            
            if(!empty($productInfo)){
                $product_id = substr($info['product_id'], 0, -1); 
                $qty_usesWK_Info = $objProductConfig->fetchQtyInfo($data['pid'],$product_id); 
                $this->view->qty_usesWK_Info = $qty_usesWK_Info;
            }
            
            $this->view->productInfo = $productInfo;
            $this->view->projectId = $data['pid'];
            $this->view->projectType = $info['project_type'];
        }
    }

    public function ajaxProductDelAction() {
        $this->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $request = $this->getRequest();
        $data = $request->getParams();

        $productId = $this->_request->getParam("productId");
        $projectId = $this->_request->getParam("projectId");
        $objProductConfig = new Default_Model_ProductConfig();

        if (!empty($productId) && !empty($projectId)) {
            $res = $objProductConfig->deleteProductId4rmProject($productId, $projectId);
            if ($res == 'success') {
                echo $res;
                die;
            }
        }
    }

    public function baselineAction() {
        error_reporting(0);
        $this->_helper->layout()->disableLayout();
        
        $request = $this->getRequest();
        $data = $request->getParams();
        $objProduct = new Product_Model_Product();
        
       
        if(!empty($data['baselineId'])){
            $productBaselineInfo = $objProduct->fetchProductBaselineInfo($data['projectId'],$data['productId'],$data['baselineId']); 
        
            if($productBaselineInfo =='empty'){
                $productDetail = $objProduct->fetchProductInfoByIdWithDetail($data['baselineId']); 
                $price = $productDetail[0]['purchase_price'];
            }else{
                $productDetail = $objProduct->fetchProductBaselineInfoByIdWithDetail($data['projectId'],$data['productId'],$data['baselineId']); 
                $price = $productBaselineInfo['price'];
            }
         
            $this->view->price = $price;
            $this->view->productDetail = $productDetail;
            $this->view->projectId = $data['projectId'];
            $this->view->productId = $data['productId'];
            $this->view->baselineId = $data['baselineId'];
        }
    }
    
    public function ajaxProductQtyAction() {
        $this->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        $objProduct = new Product_Model_Product();
        $request = $this->getRequest();
        $data = $request->getParams();
        
        if(!empty($data['projectId'])){ // Qty Update product 
            if(isset($data['qty'])){
                 $qty = $objProduct->qtyTranscation($data['projectId'],$data['productId'],$data['qty']);
            }else{
                  $qty = $objProduct->qtyTranscation($data['projectId'],$data['productId'],'');
            }
        }
     }
      public function ajaxProductUseswkAction() {
        $this->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        $objProduct = new Product_Model_Product();
        $request = $this->getRequest();
        $data = $request->getParams();
       
        if(!empty($data['projectId'])){ // usesWK Update product 
            if(isset($data['usesWK'])){
                $usesWK = $objProduct->usesWKTranscation($data['projectId'],$data['productId'],$data['usesWK']);
            }else{
                $usesWK = $objProduct->usesWKTranscation($data['projectId'],$data['productId'],'');
            }
        }
     }
      public function ajaxProductPriceAction() {
        $this->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        $objProduct = new Product_Model_Product();
        $request = $this->getRequest();
        $data = $request->getParams();
       
        if(!empty($data['projectId'])){ // price Update product 
            if(isset($data['price'])){
                $usesWK = $objProduct->priceTranscation($data['projectId'],$data['productId'],$data['price']);
            }else{
                $usesWK = $objProduct->priceTranscation($data['projectId'],$data['productId'],'');
            }
        }
     }
     
       public function ajaxBaselinePriceAction() { 
        $this->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        $request = $this->getRequest();
        $data = $request->getParams();
        $objProductConfig = new Default_Model_ProductConfig();
        $objProductConfig->baselinePopupPrice($data['productId'],$data['price']);
    }
    
     public function saveAction() { 
        $this->getHelper('layout')->disableLayout();
       // $this->_helper->viewRenderer->setNoRender();
         
        $request = $this->getRequest();
        $data = $request->getParams();
      
        $objProductConfig = new Default_Model_ProductConfig();
        $objProduct = new Product_Model_Product();
        
        $productDetail = $objProduct->fetchProductInfoByIdWithDetail($data['hdnBaselineId']);  
        $subatt_id = $productDetail[0]['subatt_id'];
        
        $pieces = explode(",", $subatt_id);
        if (in_array('27', $pieces)) {
            unset($pieces[array_search('27',$pieces)]);
        }
        $pieces1 = implode(",", $pieces);
        
      
       // $unit      = $productDetail[0]['unit'];
        
        $productBaselineInfo = $objProduct->fetchProductBaselineInfo($data['hdnProjectId'],$data['hdnProductId'],$data['hdnBaselineId']); 
        
        if($productBaselineInfo =='empty'){//Insert
             $objProduct->enterProductBaselineInfo($data['hdnProjectId'],$data['hdnProductId'],$data['hdnBaselineId'],$pieces1,$data['value'],$data['price']);
        }else{ //Update
             $objProduct->updateSubAttValue($data['hdnProjectId'],$data['hdnProductId'],$data['hdnBaselineId'],$data['value'],$data['price']);
        }
        //$this->_redirect('/product-config/baseline/projectId/'.$data['hdnProjectId'].'/productId/'.$data['hdnProductId'].'/baselineId/'.$data['hdnBaselineId']);
       // $this->_redirect("/product-config/index/baseline-popup/close");
     }
    

}

