<?php

class Admin_AttributeManagementController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $this->_options = $this->getInvokeArg('bootstrap')->getOptions();
    }

    public function checklogin() {
        $loginSession = new Zend_Session_Namespace('login');
        if(empty($loginSession->userId)){
             $this->_redirect('/admin');
        }
        /*$usrtype = $loginSession->userType;
        if ($usrtype == "superadmin") {
            return 1;
        } else {
            $this->_redirect('/admin');
            //return 0;
        }*/
    }
    
    public function indexAction() {
        // action body
        $this->checklogin();
        Zend_Layout::startMvc(array(
            'layoutPath' => APPLICATION_PATH . '/layouts/scripts/',
            'layout' => 'adminLayout'
        ));
        $data    = $this->_request->getPost();
        $loginSession = new Zend_Session_Namespace('login'); 
     //  echo $loginSession->userId.'--'.$loginSession->fname.'--'.$loginSession->lname.'--'.$loginSession->email_id.'--'.$loginSession->userType;
        $objAtt  = new Admin_Model_Attribute();
        if($loginSession->userType == 'superadmin'){
            $attList    = $objAtt->fetchAttList();
            $subAttList = $objAtt->fetchSubAttList();
        }else{
            /*$attList    = $objAtt->fetchActiveAttList();
            $subAttList = $objAtt->fetchActiveSubAttList();*/
            $attList    = $objAtt->fetchAttList();
            $subAttList = $objAtt->fetchSubAttList();
        }
        $this->view->attList = $attList;
        $this->view->subAttList = $subAttList;
    }
    
    public function attDisplayAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        $data = $this->_request->getPost();
        $objAtt = new Admin_Model_Attribute();
        $objAtt->updateAttSubattChk($data);
    }
    
    public function subattAction(){
        $this->_helper->layout->disableLayout();
        
        $data    = $this->_request->getPost(); 
        $id = $this->_request->getParam("id"); 
        $objAtt  = new Admin_Model_Attribute();
        
        $attList    = $objAtt->fetchAttList();
        $this->view->attList = $attList;
       
        if (!empty($data)) {
            if (!empty($data['hdnSunattId'])) { //Update
                $subAtt = $objAtt->editSubatt($data['attribute'], $data['subattName'], $data['hdnSunattId']);
                $this->view->succMesg = 'Record updated succesfully';
            }else{ //Insert
                $subAtt = $objAtt->addSubatt($data['attribute'], $data['subattName']);
                $this->view->succMesg = 'Record created succesfully';
            }
        }
        if(!empty($id)) { 
            $subAttInfo = $objAtt->fetchSubattByID($id);
            $this->view->subAttInfo = $subAttInfo;
        } 
    }
    public function ajaxAttDelAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        $request = $this->getRequest();
        $data = $request->getParams(); 
        $objAtt  = new Admin_Model_Attribute();
        $result = $objAtt->delAtt($data['id'],$data['type']);
        echo $result;
        die;
    }
    
      public function attAction(){
          $this->getHelper('layout')->disableLayout();
      
            $request = $this->getRequest();
            $data    = $request->getParams();
            $id    = $this->_request->getParam("id");
            $objAtt  = new Admin_Model_Attribute();
            
            
            if ($this->getRequest()->isPost()) {
                if (isset($_POST['submit'])) {
                    $file = $_FILES['imgfile']['name']; 
                    if (!empty($file)) {
                        $checkExtension = explode(".", $file);

                        if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/attributeImages/" . $_FILES["imgfile"]["name"])) {
                            // echo $_FILES["imgfile"]["name"] . " already exists. ";
                            // header('Location: ' . $this->view->baseUrl() . '/typical/update/id/'.$data['typId'].'/msg/1');
                        } else {
                            move_uploaded_file($_FILES["imgfile"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/attributeImages/" . $_FILES["imgfile"]["name"]);
                            /* create thumb nail << */
                            $image_name = $_FILES["imgfile"]["name"];
                            $targetfilepath = $_SERVER['DOCUMENT_ROOT'] . "/attributeImages/" . $_FILES["imgfile"]["name"];
                            $thumbPath = $_SERVER['DOCUMENT_ROOT'] . "/attributeImages/";
                            $thumb_width = '36';
                            $thumb_hight = '36';
                            // $this->thumbnailfun($targetfilepath, $thumbPath, $image_name, $thumb_width, $thumb_hight, $checkExtension['1']);
                            $this->set_thumb($file, $_SERVER['DOCUMENT_ROOT'] . "/attributeImages", $_SERVER['DOCUMENT_ROOT'] . "/attributeImages/thumb", '36', '36', $checkExtension['1']);
                            $thumbImg = 'th_' . $image_name;
                        }
                    } else {
                        $image_name = "";
                        $thumbImg = '';
                    }
                    
                     
                if (!empty($data)) {
                    if (!empty($data['hdnattId'])) { //Update
                        $hdnInfoById = $objAtt->fetchAttByID($data['hdnattId']);
                        if(empty($image_name)){
                            if(!empty($hdnInfoById['attribute_image'])){
                                $thumbImg =  $hdnInfoById['attribute_image'];
                            }
                        }
                        $attName = $data['attribute_title'];
                        $subAtt = $objAtt->editAtt($image_name, $thumbImg, $attName,$data['hdnattId']);
                        $this->view->succMesg = 'Attribute updated succesfully';
                    } else { //Insert
                        $attName = $data['attribute_title'];
                        $objAtt->insertAttInfo($image_name, $thumbImg, $attName);
                        $this->view->succMesg = 'Attribute created succesfully';
                    }
                }
            }
            }
            if (!empty($id)) {
                $attInfo = $objAtt->fetchAttByID($id);
                $this->view->attInfo = $attInfo;
            }
    }
     public function ajaxFetchSubattAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $request = $this->getRequest();
        $data = $request->getParams();
        $objAtt = new Admin_Model_Attribute();
       $loginSession = new Zend_Session_Namespace('login'); 
     //  echo $loginSession->userId.'--'.$loginSession->fname.'--'.$loginSession->lname.'--'.$loginSession->email_id.'--'.$loginSession->userType;
  
        
        if(isset($data['attId'])){
            if($loginSession->userType == 'superadmin'){
                $subAttList=$objAtt->fetchSubattByAttId($data['attId']);
            }else{
                //$subAttList=$objAtt->fetchActiceSubattByAttId($data['attId']);
                 $subAttList=$objAtt->fetchSubattByAttId($data['attId']);
            }
           
           
           if($loginSession->userType == 'superadmin'){
                $html = '<tr class="t-head"><td class="tdw-130 tdleft">Sub Attributes<span class="sort"><a href="#" class="up"><img src="/images/sort-up.png" alt="sort" /></a><a href="#"><img src="/images/sort-down.png" alt="sort" /></a></span></td><td class="tdw-60">Activate</td><td class="tdw-40">Edit</td><td class="tdw-52">Delete</td><td class="tdw-52">Activate Calculator</td></tr>';
           }else{
               $html = '<tr class="t-head"><td class="tdw-130 tdleft">Sub Attributes<span class="sort"><a href="#" class="up"><img src="/images/sort-up.png" alt="sort" /></a><a href="#"><img src="/images/sort-down.png" alt="sort" /></a></span></td><td class="tdw-60">Activate</td></tr>';
           }
           
            if(!empty($subAttList)){
               foreach ($subAttList as $key1 => $value1) {
                    if($value1['display'] == 'y'){ 
                        $chk = "checked='checked'";
                    }else{
                        $chk = '';
                    }
                    if($value1['calci_subatt'] == 'y'){ 
                        $chk1 = "checked='checked'";
                    }else{
                        $chk1 = '';
                    }
                    if($loginSession->userType == 'superadmin'){
                        $html .= "<tr><td class='tdleft'>".$value1['attribute_name']." - ".$value1['subatt_name']."</td><td><input type='checkbox' $chk class='subattClass' value='".$value1['display']."' id='".$value1['subatt_id']."'  name='answer'/></td><td><a href='/admin/attribute-management/subatt/id/".$value1['subatt_id']."' data-fancybox-type='iframe' class='addSubatt'><img src='/images/edit-icon.png' alt='edit'/></a></td><td><a href='javascript:void(0);' onclick=javascript:fnDelSubAtt('".$value1['subatt_id']."','subAtt')><img src='/images/delete-icon.png' alt='delete'/></a></td><td><input type='checkbox' $chk1 class='calciSubAtt' value='".$value1['calci_subatt']."' id='".$value1['subatt_id']."'  name='answer'/></td></tr>";
                    }else{
                        $html .= "<tr><td class='tdleft'>".$value1['attribute_name']." - ".$value1['subatt_name']."</td><td><input type='checkbox' $chk class='subattClass' value='".$value1['display']."' id='".$value1['subatt_id']."'  name='answer'/></td></tr>";
                    }
                }
            }else{ 
                 $html .= "<tr><td colspan='4'>No Record found.</td></tr>";
            }
            echo $html; die;
        }        
    }

    function set_thumb($file, $photos_dir, $thumbs_dir, $square_size, $quality, $ext) {
        //check if thumb exists
        if (!file_exists($thumbs_dir . "/" . $file)) {
            //get image info
            list($width, $height, $type, $attr) = getimagesize($photos_dir . "/" . $file);

            //set dimensions
            if ($width > $height) {
                $width_t = $square_size;
                //respect the ratio
                $height_t = round($height / $width * $square_size);
                //set the offset
                $off_y = ceil(($width_t - $height_t) / 2);
                $off_x = 0;
            } elseif ($height > $width) {
                $height_t = $square_size;
                $width_t = round($width / $height * $square_size);
                $off_x = ceil(($height_t - $width_t) / 2);
                $off_y = 0;
            } else {
                $width_t = $height_t = $square_size;
                $off_x = $off_y = 0;
            }

            //$thumb=imagecreatefromjpeg($photos_dir."/".$file);
            if (!strcmp("png", $ext)) {
                $thumb = imagecreatefrompng($photos_dir . "/" . $file);
            } else {
                $thumb = imagecreatefromjpeg($photos_dir . "/" . $file);
            }

            $thumb_p = imagecreatetruecolor($square_size, $square_size);
            //default background is black
            $bg = imagecolorallocate($thumb_p, 255, 255, 255);
            imagefill($thumb_p, 0, 0, $bg);
            imagecopyresampled($thumb_p, $thumb, $off_x, $off_y, 0, 0, $width_t, $height_t, $width, $height);

            //imagejpeg($thumb_p,$thumbs_dir."/th_".$file,$quality);
            if (!strcmp("png", $ext)) {
                imagepng($thumb_p, $thumbs_dir . "/th_" . $file);
            } else {
                imagejpeg($thumb_p, $thumbs_dir . "/th_" . $file, $quality);
            }
        }
    }
    
    public function ajaxUpdateMasterNamesAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $request = $this->getRequest();
        $data = $request->getParams();
        $objAtt = new Admin_Model_Attribute();
        
        if(!empty($data['masterAttName'])){
           $objAtt->editMasterAttName($data['masterAttName']); 
        }
        if(!empty($data['masterSubAttName'])){
           $objAtt->editMasterSubAttName($data['masterSubAttName']); 
        }
    }
    public function saverankAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $this->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $request = $this->getRequest();
        $jsonString_result = $request->getParam('jsonString_result');
        $jsonString_result = str_replace("\\", "", $jsonString_result);
        $jsonString_result = Zend_Json::decode($jsonString_result);
        $objAtt = new Admin_Model_Attribute();

        foreach ($jsonString_result as $key => $value) {
            if ($value == '') {
                unset($jsonString_result[$key]);
            }
        } 
        $attData = $objAtt->updateAttRank($jsonString_result);
    }
    public function saveranksubattAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $this->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $request = $this->getRequest();
        $jsonString_result = $request->getParam('jsonString_result');
        $jsonString_result = str_replace("\\", "", $jsonString_result);
        $jsonString_result = Zend_Json::decode($jsonString_result);
        $objAtt = new Admin_Model_Attribute();

        foreach ($jsonString_result as $key => $value) {
            if ($value == '') {
                unset($jsonString_result[$key]);
            }
        } 
        $attData = $objAtt->updateSubAttRank($jsonString_result);
    }
    
}

