<?php

class Default_ProjectController extends Zend_Controller_Action {

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
        error_reporting(0);
        $this->checklogin();
        $request = $this->getRequest();
        $data = $request->getParams();
        $sessUser = new Zend_Session_Namespace('user');
        $objProject = new Default_Model_Project();
        
        $projectList = $objProject->fetchProjectList($sessUser->user_Id);
        $this->view->projectList = $projectList;
   
       
        $projDtl= $objProject->getPaybackDetails($sessUser->user_Id);     
        $this->view->projDtl = $projDtl;
    }
    
    public function analysisAction() {
        $this->checklogin();
        $request = $this->getRequest();
        $data = $request->getParams();
        $sessUser = new Zend_Session_Namespace('user');
        $objProject = new Default_Model_Project();
        
        $projCalculatePid = new Zend_Session_Namespace('calculatePid');
        if(isset($data['calculatePid'])){
           $projCalculatePid->pid = $data['calculatePid'];
        }else{
            $projCalculatePid->pid = '';
        }
    }
    
    
    public function configurationAction() {
        $this->checklogin();
        
        $request = $this->getRequest();
        $data = $request->getParams();
        $sessUser = new Zend_Session_Namespace('user');
        $objProject = new Default_Model_Project();

        $stateList = $objProject->fetchState();
        $countryList = $objProject->fetchCountry();
        $this->view->stateList = $stateList;
        $this->view->countryList = $countryList;
      
        if (isset($data['type'])) {
            if(isset($data['pid'])){
                $projInfoById = $objProject->fetchProjectInfoById($data['pid']);
                $this->view->projInfoById = $projInfoById;
            }
            $this->view->configurationType = $data['type'];
        }
       
        if(!empty($_POST)){

//            $leed_list = "";
//            if(!empty($_POST['leed_list'])){
//                $leed_list = implode(',', $_POST['leed_list']);
//              
//               }
//                 print_r($leed_list);
//                die;
//            die;
            if(isset($_POST['hdnProjectId'])){ // Edit
                $updateProjInfo = $objProject->updateProjectDetails($_POST);   
                //$this->_redirect("/project/configuration/type/".$_POST['hdnProjectType']."/pid/".$_POST['hdnProjectId']);
                //$this->_redirect("/payback-analysis/index/pid/".$_POST['hdnProjectId']);
                $this->_redirect("product-config/index/pid/".$_POST['hdnProjectId']);
            }else{ // Insert
                $lastInsertId = $objProject->enterProjectDetails($sessUser->user_Id,$_POST);
                //$this->_redirect($_SERVER['REQUEST_URI']."/pid/".$lastInsertId);
                //$this->_redirect("/payback-analysis/index/pid/".$lastInsertId);
                 $this->_redirect("product-config/index/pid/".$lastInsertId);
            }   
            
        }
    }
    public function ajaxProjectDelAction() {
        $this->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        $objProject = new Default_Model_Project();
        $request = $this->getRequest();
        $data = $request->getParams();
        $projectId = $this->_request->getParam("projectId");
        
        if(!empty($projectId)){
           $delProj = $objProject->delProjectById($projectId);
           echo 'success'; die;
        }
    }
    
    public function ajaxSessionProjectNameAction() {
        $this->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $data = $request->getParams();
        
         if(!empty($data['name'])){
            $productAnalysis = new Zend_Session_Namespace('productAnalysis');
            
            if($data['name'] == 'empty'){ 
                $productAnalysis->name = '';
            }else{
                $productAnalysis->name = $data['name'];
            }
            echo "/project/configuration/type/".$data['type']; die;
        }
    }

}

