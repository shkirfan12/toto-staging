<?php

class Product_AdvanceSearchController extends Zend_Controller_Action {

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
        $request    = $this->getRequest();
        $data       = $request->getParams();  
        
        $objProduct = new Admin_Model_Product();
        $objProduct1 = new Product_Model_Product();
       
        $tag1List  = $objProduct->fetchTag1List();   $this->view->tag1List = $tag1List;
        $tag2List  = $objProduct->fetchTag2List();   $this->view->tag2List = $tag2List;
        $tier1List = $objProduct->fetchTier1List(); $this->view->tier1List = $tier1List;
        $tier2List = $objProduct->fetchTier2List(); $this->view->tier2List = $tier2List;
        
        //Update Master Tiers/Tags Name << 
        $tierTagMasterName = $objProduct->fetchTierTagMasterName(); 
        $this->view->tierTagMasterName  = $tierTagMasterName;
        //Update Master Tiers/Tags Name >> 
        
        if(!empty($_POST)){ 
            $res = $objProduct1->advanceSearch($_POST);
            if($res == 'empty'){
                $this->view->errMsg = "Record not found.";
            }else{
                /*$search = json_encode($res); 
                $sessAdSearch = new Zend_Session_Namespace('adSearch');
                $sessAdSearch->search = $search;
                $this->_redirect('/product/search/result/advance/value');*/
                
                $sessAdSearch = new Zend_Session_Namespace('adSearch');
                $sessAdSearch->search = $res;
                $this->advanceSearch = $res;
                $this->_redirect('/product/search/result/advance/value');
            }
        }
    }
    
    public function fetchTier2Action() {
        $request    = $this->getRequest();
        $data       = $request->getParams();
        $objProduct = new Admin_Model_Product();
        
        if (!empty($data['fetch_tier2ByTier1Id'])) {
            $tier2List = $objProduct->fetchTier2ByTier1($data['fetch_tier2ByTier1Id']);
            
            $html = "";
            if(!empty($tier2List)){
                foreach ($tier2List as $key => $value) {
                    $tier2Id = $value['tier2_id'];
                    $tier2Name = $value['tier2_name'];
                    $html .= "<li><a style='padding-left: 15px; float: left; width: 90%;' class='tier2_name' id='$tier2Id'>$tier2Name</a><input type='checkbox' class='myClass' value='$tier2Id' id='tier2_$tier2Id' name='tier2[]'/></li>";
                }
                echo $html;die;
            }else{
                $html .= "<li>Record not found</option></li>";
                echo $html;die;
            }
             
            echo "<pre>"; print_r($tier2List); die;
        }
    }
}

