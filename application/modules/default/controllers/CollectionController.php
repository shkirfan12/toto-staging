<?php

class Default_CollectionController extends Zend_Controller_Action {

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
        $objCollection = new Default_Model_Collection();
        $collectiontList = $objCollection->fetchCollectionList($sessUser->user_Id);
        $this->view->collectionList = $collectiontList;
    }
    
    public function ajaxCollectionDelAction() {
        $this->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        $objCollection = new Default_Model_Collection();
        $request = $this->getRequest();
        $data = $request->getParams();
        $collectionId = $this->_request->getParam("collectionId"); 
        
        if(!empty($collectionId)){
           $delCollection = $objCollection->delCollectionById($collectionId);
           echo 'success'; die;
        }
    }
    public function saveAction() {
        $this->getHelper('layout')->disableLayout();

        $request = $this->getRequest();
        $data = $request->getParams(); 
        $objCollection = new Default_Model_Collection();
        
        $sessUser = new Zend_Session_Namespace('user');
        
        if(!empty($_POST)){  
            if(isset($_POST['hdnCollectionId'])){ //Edit
                $subAtt = $objCollection->editCollection($sessUser->user_Id,$_POST);
                    $this->view->succMesg = 'Collection updated succesfully';
            }else{// Insert
               $lastInsertId = $objCollection->enterCollectionInfo($sessUser->user_Id,$_POST['collection_name']);
               $this->view->succMesg = "Collection added succesfully";
            }
        }
        
        if(isset($data['id'])){
            $collectionInfo = $objCollection->fetchCollectionByID($data['id']);
            $this->view->collectionInfo = $collectionInfo;
        }
    }
     public function collectAction() {
        $this->getHelper('layout')->disableLayout();
        $request = $this->getRequest();
        $data = $request->getParams();
        $sessUser = new Zend_Session_Namespace('user');
        $objCollection = new Default_Model_Collection();
        $collectiontList = $objCollection->fetchCollectionList($sessUser->user_Id);
        $this->view->collectionList = $collectiontList;
    }
     public function collectaddAction() {
        $this->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        $sessUser = new Zend_Session_Namespace('user');
        $objCollection = new Default_Model_Collection();
        $request = $this->getRequest();
        $data = $request->getParams();
        //echo "Actionv<pre>"; 
//print_r($data); die;
if($data['cId'] == 'none')
{
  $result=  $objCollection->collectionTranscation1($data['productId'],$data['cname'],$sessUser->user_Id);
 // print_r($result); die;
        echo 'success'; die;
     }
     else
     {
       $objCollection->collectionTranscation($data['productId'],$data['cId'],$data['cname'],$sessUser->user_Id); 
       echo 'success'; die;
     }   
       //$objCollection->collectionTranscation1($data['productId'],$data['cname'],$sessUser->user_Id);

}

}

