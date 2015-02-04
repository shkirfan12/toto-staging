<?php

class Product_SearchController extends Zend_Controller_Action {

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
//        $objProduct = new Admin_Model_Product();
//        $tier1List = $objProduct->fetchTier1List();
//        $this->view->tier1List = $tier1List;
        $this->_redirect('/product');
    }

    public function autoSearchAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();

        $request = $this->getRequest();
        $data = $request->getParams();

        $objProduct = new Admin_Model_Product();
        $productList = $objProduct->getAutoSearchList($data["term"]); 
        $result = array();

        if (!empty($productList)) {
            foreach ($productList as $key => $value) {
                //$result[] = ucfirst($this->seo_friendly_url($value['product_name']));
                $result[] =  htmlentities($value['product_name'], ENT_QUOTES);
            }
        }else{
            $result = array("Record Not Found");
        }  
         Zend_Json::$useBuiltinEncoderDecoder = true;
         echo Zend_Json::encode($result);
        exit;
    }
    
     public function productNumberAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();

        $request = $this->getRequest();
        $data = $request->getParams();
        $objProduct = new Product_Model_Product(); 
        $pname = html_entity_decode($data['pname']);
        $productDetail = $objProduct->fetchProductInfoByName($pname);
        echo $productDetail['product_num'];die;
    }
    
//    public function fetchSearchProductnameAction(){
//        $this->_helper->viewRenderer->setNoRender();
//        $this->_helper->layout()->disableLayout();
//
//        $request = $this->getRequest();
//        $data = $request->getParams();
//        
//        $objProduct = new Admin_Model_Product();
//        if(!empty($data['productName'])){
//            $productDetail = $objProduct->fetchProductInfoByName($data['productName']);
//            echo '<pre>'; print_r($productDetail); die;
//        }
//        
//    }
    public function resultAction() {
        error_reporting(0);
        $request    = $this->getRequest();
        $data       = $request->getParams(); 
        $objProduct = new Product_Model_Product();
   
        if(!empty($data['name'])){// Product Name : dropdown
            $productDetail1 = $objProduct->fetchProductInfoByNumber($data['name']); 
            $productDetail = $objProduct->fetchProductInfoByIdWithDetail($productDetail1['product_id']);
            $this->view->productDetail = $productDetail;
            $this->view->producViewMesg = $productDetail1['product_id']; 
        }
         if(!empty($data['tier1Id'])){ // Home Page : Click on category
            $tier1List = $objProduct->fetchProductInfoByTier1Id($data['tier1Id']); 
            $this->view->tier1List = $tier1List;
        }
        if(!empty($data['productId'])){ // Product Detail : Click on product
            //$productDetail = $objProduct->fetchProductInfoById($data['productId']);
            $productDetail = $objProduct->fetchProductInfoByIdWithDetail($data['productId']); 
            $this->view->productDetail = $productDetail;
            $this->view->producViewMesg = $data['productId']; 
        }
        if(!empty($data['collectionId'])){ // Collection  : Click on Collection Name
            $tier1List = $objProduct->fetchProductByCollectionId($data['collectionId']);
            $this->view->tier1List = $tier1List;
            $this->view->collectionId = $data['collectionId'];
           $this->view->collectionDeleteIcon = 'collectionDeleteIcon';
            
            $objCollection = new Default_Model_Collection();
            $collectionInfo = $objCollection->fetchCollectionByID($data['collectionId']);
            if(!empty($collectionInfo['product_id'])){
                $product_id = substr($collectionInfo['product_id'], 0, -1); 
                $comma_separator = explode(',', $product_id);
                $countProduct = count($comma_separator);
                $this->view->collectionProductCount = $countProduct;
            }else{
                $this->view->collectionProductCount = '0';
            }
            $this->view->collectionName = $collectionInfo['collection_name'];
        }
        
        if (!empty($data['advance'])) { //Advance search
            /* $advance = json_decode($data['advance']);
              $this->view->advanceSearch = $advance;  //echo $advance[0]->product_name; */
            
            $sessAdSearch = new Zend_Session_Namespace('adSearch');
            if (!empty($sessAdSearch->search)) {  //echo "<pre>search123"; print_R($sessAdSearch->search);die; 
               // $advance = json_decode($sessAdSearch->search); 
                 $advance = $sessAdSearch->search;  //echo "<pre>advance123";print_R($advance); die;
                $this->view->advanceSearch = $advance;  
                //unset($sessAdSearch->search);
            }
        }
    }
    
    public function ajaxProductSessionAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();

        $request = $this->getRequest();
        $data = $request->getParams();
        
        if(!empty($data['count'])){
            
            $productSession = new Zend_Session_Namespace('productSession1');
            if($data['count'] == 'empty'){ 
                $productSession->count = '';
                $productSession->selectedPid='';
                //Zend_Session::namespaceUnset('productSession1');
            }else{
               // Zend_Session::namespaceUnset('productSession1');
                $productSession->count = '';
                $productSession->selectedPid='';
                $productSession->count = $data['count'];
                $productSession->selectedPid = $data['selected'];
                //unset($productSession->count);
                //unset($productSession->selectedPid);
            }
            //echo "<pre>"; print_R($data); echo $productSession->count.'---'.$productSession->selectedPid; die;
        }
    }
    public function ajaxDummySessionAction(){
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();

        $request = $this->getRequest();
        $data = $request->getParams();
        $objAccount = new Login_Model_Account();
       
        $sessUser = new Zend_Session_Namespace('user');
          
        if(empty($sessUser->user_Id)){
            $randemail = $this->random_string('5'); 
            $dummyEmail = 'dummy_'.$randemail.'@gmail.com'; 
            $userRecord = $objAccount->enterUserDetails('Dummy','User',$dummyEmail,'changeme');
            $sessUser->user_Id  = $userRecord['user_id'];
            $sessUser->email_Id = $userRecord['email_id'];
            $sessUser->fname    = $userRecord['fname'];
            $sessUser->lname    = $userRecord['lname'];
            //echo $sessUser->user_Id.'--'.$sessUser->email_Id.'--'.$sessUser->fname.'--'.$sessUser->lname; die;
        }
    }
    
    function random_string($length) { //http://stackoverflow.com/questions/1846202/php-how-to-generate-a-random-unique-alphanumeric-string
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }
     public function ajaxProductDelAction() {
        $this->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $request = $this->getRequest();
        $data = $request->getParams();

        $productId = $this->_request->getParam("productId");
        $collectionId = $this->_request->getParam("collectionId");
        $objCollection = new Default_Model_Collection();

        if (!empty($productId) && !empty($collectionId)) {
            $res = $objCollection->deleteProductId4rmCollection($productId, $collectionId);
            if ($res == 'success') {
                echo $res;
                die;
            }
        }
    }
    function seo_friendly_url($string) {
        $string = str_replace(array('[\', \']'), '', $string);
        $string = preg_replace('/\[.*\]/U', '', $string);
        $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
        $string = htmlentities($string, ENT_COMPAT, 'utf-8');
        $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string);
        $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/'), '-', $string);
        return strtolower(trim($string, '-'));
    }
}

