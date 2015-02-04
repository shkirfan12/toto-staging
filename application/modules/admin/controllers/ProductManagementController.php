<?php

class Admin_ProductManagementController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $this->_options = $this->getInvokeArg('bootstrap')->getOptions();
    }

     public function checklogin() {
        $loginSession = new Zend_Session_Namespace('login');
        if(empty($loginSession->userId)){
             $this->_redirect('/admin');
        }
        
    }
    
    public function indexAction() { 
       $this->checklogin();
        Zend_Layout::startMvc(array(
            'layoutPath' => APPLICATION_PATH . '/layouts/scripts/',
            'layout' => 'adminLayout'
        ));
        header('Location: /admin/product-management/category-setup');
    }
    
    public function productDetailAction() { 
        error_reporting(0);
       $this->checklogin();
        Zend_Layout::startMvc(array(
            'layoutPath' => APPLICATION_PATH . '/layouts/scripts/',
            'layout' => 'adminLayout'
        ));
        $loginSession = new Zend_Session_Namespace("login");

        $data = $this->_request->getPost(); 
        $request = $this->getRequest(); //$param   = $request->getParams();  echo '<pre>'; print_r($_POST);print_r($data); die;
        $pid = $request->getParam('pid');
        $objProduct = new Admin_Model_Product();   
        $arr1 =  array(); $arr2 =  array();
        
        $tag1List  = $objProduct->fetchTag1List();   $this->view->tag1List  = $tag1List;
        $tag2List  = $objProduct->fetchTag2List();   $this->view->tag2List  = $tag2List;
        $tier1List = $objProduct->fetchTier1List();  $this->view->tier1List = $tier1List;
        $tier2List = $objProduct->fetchTier2List();  $this->view->tier2List = $tier2List;
       // $tier3List = $objProduct->fetchTier3List();  $this->view->tier3List = $tier3List;
         if(!empty($pid)){
             $productList= $objProduct->productList();  
             foreach ($productList as $key => $value) {
                 if($value['product_id'] != $pid){
                     $arr1[] = $value;
                 }
                 if($value['product_id'] == $pid){
                       $arr2[] = $value;
                 }
             }
             $res = array_merge($arr2,$arr1);
             $this->view->productList = $res;
         }else{ 
             $productList= $objProduct->productList();  $this->view->productList = $productList;
         }
        
        
       
        if(isset($_FILES['Filedata']['name'])){
            $upload_file = $_FILES['Filedata']['name'];
            if (!empty($upload_file)) {
                $checkExtension = explode(".", $upload_file);
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/batchUpload/" . $upload_file)) {
                    // echo $_FILES["imgfile"]["name"] . " already exists. ";
                    // header('Location: ' . $this->view->baseUrl() . '/typical/update/id/'.$data['typId'].'/msg/1');
                } else {
                    move_uploaded_file($_FILES["Filedata"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/batchUpload/" . $_FILES["Filedata"]["name"]);
                    /* create thumb nail << */
                    $targetfilepath = $_SERVER['DOCUMENT_ROOT'] . "/batchUpload/" . $upload_file;
                    $mainThumbPath = $_SERVER['DOCUMENT_ROOT'] . "/batchUpload/main/";
                    $thumbPath = $_SERVER['DOCUMENT_ROOT'] . "/batchUpload/thumb/";
                    $photos_dir = $_SERVER['DOCUMENT_ROOT'] . "/batchUpload";
                    $thumbs_dir = $_SERVER['DOCUMENT_ROOT'] . "/batchUpload/thumb";
                    $main_thumb_w = '520';
                    $main_thumb_h = '400';
                    $this->thumbnailImg($targetfilepath, $mainThumbPath, $upload_file, $main_thumb_w, $main_thumb_h, $checkExtension['1']); //Main Image 
                    $this->set_thumb($upload_file, $photos_dir, $thumbs_dir, '90', '100', $checkExtension['1']); //Thumb Image
                }
            }
        }
            
        if ($this->getRequest()->isPost()) { 
            if (isset($_POST['submit'])) { 
                if ($_POST['hdnSearch'] == 'search') {
                    $productList = $objProduct->searchProduct($_POST);
                    if(!empty($productList)){
                        $productInfo = $objProduct->fetchProductDetailByPid($productList[0]['product_id']);
                    }else{
                        $productInfo = '';
                    }
                   
                    $this->view->productInfo = $productInfo;
                    $this->view->productList = $productList;
                   // $this->view->hdnSearch   = 'search';
                }else{
                    if (!empty($_POST['hdnProductId'])) { //edit
                        $productId = $objProduct->editProductDetail($_POST);
                        header('Location: /admin/product-management/product-detail/pid/' . $productId);
                    } else { // Insert
                        $productId = $objProduct->insertProductDetail($data, $loginSession->userId);
                        $productInfo = $objProduct->fetchProductDetailByPid($productId);
                     //   $productImg = $objProduct->fetchProductImgDetailByPid($productId);
                        $this->view->productInfo = $productInfo;
                      //  $this->view->productImg = $productImg;
                        header('Location: /admin/product-management/product-detail/pid/' . $productInfo['product_id']);
                    }
                }
            }
        }
         if(!empty($pid)){
            $productInfo = $objProduct->fetchProductDetailByPid($pid); 
             //$productImg = $objProduct->fetchProductImgDetailByPid($pid);
            $this->view->productInfo = $productInfo;
            //$this->view->productImg = $productImg;
        }
        
    }
    
  
    /*
     * Batch Upload
     */
    public function batchUploadAction() {
         $this->getHelper('layout')->disableLayout();
    }
    
    public function editImgAction() {
        $this->getHelper('layout')->disableLayout();

        $request = $this->getRequest();
        $data = $request->getParams(); 
        $id = $this->_request->getParam("pImgId"); 
        $objProduct = new Admin_Model_Product();

        if ($this->getRequest()->isPost()) { 
            if (isset($_POST['submit'])) { 
                $file = $_FILES['imgfile']['name']; 
                if (!empty($file)) {
                    $checkExtension = explode(".", $file);
                    $image_name = $_FILES["imgfile"]["name"];
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/batchUpload/" . $_FILES["imgfile"]["name"])) {
                        // echo $_FILES["imgfile"]["name"] . " already exists. ";
                        // header('Location: ' . $this->view->baseUrl() . '/typical/update/id/'.$data['typId'].'/msg/1');
                    } else {
                        move_uploaded_file($_FILES["imgfile"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/batchUpload/" . $_FILES["imgfile"]["name"]);
                        /* create thumb nail << */
                     
                        $targetfilepath = $_SERVER['DOCUMENT_ROOT'] . "/batchUpload/" . $_FILES["imgfile"]["name"];
                        $mainfilepath = $_SERVER['DOCUMENT_ROOT'] . "/batchUpload/main/" . $_FILES["imgfile"]["name"];
                      
                        $mainThumbPath = $_SERVER['DOCUMENT_ROOT'] . "/batchUpload/main/";
                        $thumbPath = $_SERVER['DOCUMENT_ROOT'] . "/batchUpload/thumb/";
                        $photos_dir = $_SERVER['DOCUMENT_ROOT'] . "/batchUpload";
                        $thumbs_dir = $_SERVER['DOCUMENT_ROOT'] . "/batchUpload/thumb";
                        //$main_thumb_w = '520';
                       // $main_thumb_h = '400';
                       // $this->thumbnailImg($targetfilepath, $mainThumbPath, $image_name, $main_thumb_w, $main_thumb_h, $checkExtension['1']); //Main Image 
                        $this->set_thumb($image_name, $photos_dir, $thumbs_dir, '90', '100', $checkExtension['1']); //Thumb Image
                    }
                    // Update DB 
                     $result = $objProduct->editProductImg($image_name, $id, $data['hdnImgName']);
                }
            }
        }
        if (!empty($id)) { 
            $productImgInfo = $objProduct->fetchProductImgById($id);
            $this->view->attInfo = $productImgInfo;
        }
    }
    public function ajaxProductDelAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        $request = $this->getRequest();
        $data = $request->getParams(); 
        $objProduct = new Admin_Model_Product();
        $result = $objProduct->delProductInfo($data['productId'],$data['img']);
        echo $result;
        die;
    }
      public function ajaxThumbimagClickAction(){
           $this->_helper->layout->disableLayout();
           
           $request = $this->getRequest();
           $data = $request->getParams();
           $loginSession = new Zend_Session_Namespace('login');
           $userType = $loginSession->userType;
           
           $objProduct = new Admin_Model_Product();
           $productId = $this->_request->getParam("pid");
           $productInfo = $objProduct->fetchProductDetailByPid($productId);
         
           foreach ($productInfo as $key => $value ) {
               $productInfo[$key] = htmlentities($value, ENT_QUOTES);
           }
           echo json_encode($productInfo); die;
      }
      
       public function importAction() {
        $this->getHelper('layout')->disableLayout();
        $this->checkLogin();   //check if user login

        $request    = $this->getRequest();
        $data       = $this->getRequest()->getParams();
        $objProduct = new Admin_Model_Product();
        $loginSession = new Zend_Session_Namespace('login');
       
        if ($this->_request->isPost()) {
            if (isset($_POST['submit'])) {
                $file = $_FILES['csvfile']['name'];
                $checkExtension = strtolower(substr(strrchr($file, "."), 1)); 
                if (!empty($file)) {
                    $import = $objProduct->import($checkExtension,$loginSession->userId);
                    $this->view->sussMsg =  'Record updated successfully.';
                }
            }
        }
    }
    
    public function ajaxsearchAction() {
        error_reporting(0);
        $this->_helper->layout->disableLayout();
        //$this->_helper->viewRenderer->setNoRender();

        $request = $this->getRequest();
        $data = $request->getParams();
        $objProduct = new Admin_Model_Product();
         $tag1List  = $objProduct->fetchTag1List();   $this->view->tag1List  = $tag1List;
        $tag2List  = $objProduct->fetchTag2List();   $this->view->tag2List  = $tag2List;
        $tier1List = $objProduct->fetchTier1List();  $this->view->tier1List = $tier1List;
        $tier2List = $objProduct->fetchTier2List();  $this->view->tier2List = $tier2List;
        $productList = $objProduct->searchProduct($data); $this->view->productList = $productList;
        $productInfo = $objProduct->fetchProductDetailByPid($productList[0]['product_id']); $this->view->productInfo = $productInfo;
              
    }

    /* Thumbanail Creation For Batch Upload */
function thumbnailImg($image_path,$thumb_path,$image_name,$thumb_width,$thumb_hight,$ext)
{ 
    //$src_img = imagecreatefromjpeg("$image_path");

   /* if(!strcmp("jpg",$ext) || !strcmp("jpeg",$ext)){
    $src_img=imagecreatefromjpeg($image_path);
    }*/

    if(!strcmp("png",$ext)){
        $src_img=imagecreatefrompng($image_path);
    }else{
        $src_img=imagecreatefromjpeg($image_path);
    }

    $new_w = $thumb_width;
    $new_h = $thumb_hight;

    $dst_img = imagecreatetruecolor($new_w,$new_h);
    imagecopyresampled($dst_img,$src_img,0,0,0,0,$new_w,$new_h,imagesx($src_img),imagesy($src_img));
    //imagejpeg($dst_img, "$thumb_path/th_$image_name");
    if(!strcmp("png",$ext)){
        imagepng($dst_img, "$thumb_path/$image_name");
    }else{
         imagejpeg($dst_img, "$thumb_path/$image_name");
    }
   // RETURN TRUE;

}

/* Scale thumbnail Image */

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
                imagepng($thumb_p, $thumbs_dir . "/" . $file);
            } else {
                imagejpeg($thumb_p, $thumbs_dir . "/" . $file, $quality);
            }
        }
    }
    
/*
     Author: Irfan Shaikh
     Decription: categorySetupAction() - Enables users to ADD/EDIT Core Hierarchical Tiers as well as Additional Filter Tags
     Created Date: 2014-03-14
     Updated Date: 2014-03-14
     */
     public function categorySetupAction() { 
        $this->checklogin();
        Zend_Layout::startMvc(array(
            'layoutPath' => APPLICATION_PATH . '/layouts/scripts/',
            'layout' => 'adminLayout'
        ));
        $loginSession = new Zend_Session_Namespace("login");

        $data = $this->_request->getPost();
        $request = $this->getRequest(); //$param   = $request->getParams(); 
        $pid = $request->getParam('pid');
        $objProduct = new Admin_Model_Product();
        
        $tag1List  = $objProduct->fetchTag1List();   $this->view->tag1List  = $tag1List;
        $tag2List  = $objProduct->fetchTag2List();   $this->view->tag2List  = $tag2List;
        $tier1List = $objProduct->fetchTier1List();  $this->view->tier1List = $tier1List;
        $tier2List = $objProduct->fetchTier2List();  $this->view->tier2List = $tier2List;
        //Update Master Tiers/Tags Name << 
        $tierTagMasterName = $objProduct->fetchTierTagMasterName(); 
        $this->view->tierTagMasterName  = $tierTagMasterName;
        //Update Master Tiers/Tags Name >> 
 
       if(!empty($data)){ 
            $pid = $objProduct->insertProductDetail($data,$loginSession->userId);
            $productInfo = $objProduct->fetchProductDetailByPid($pid);
            $productImg = $objProduct->fetchProductImgDetailByPid($pid);
            $this->view->productInfo = $productInfo;
            $this->view->productImg = $productImg;
            header('Location: /admin/product-management/product-detail/pid/'.$productInfo['product_id']);
        }else if(!empty($pid)){
            $productInfo = $objProduct->fetchProductDetailByPid($pid);
            $productImg = $objProduct->fetchProductImgDetailByPid($pid);
            $this->view->productInfo = $productInfo;
            $this->view->productImg = $productImg;
        }
        
    }
    
    // Below actions are to handle all fours views for tiers and tags
    
     public function addtieroneAction() {
          $this->checklogin();
          $this->getHelper('layout')->disableLayout();
          $objTierTag  = new Admin_Model_Product();
          
          $request = $this->getRequest();
          $data    = $request->getParams();
          $tid    = $this->_request->getParam("tid");
          $category = $this->_request->getParam("category");
          
        
//             if ($this->getRequest()->isPost()) {
//                    if (isset($_POST['submit'])) {
//                          $tid =  $data["hdnattId"];
//                           $title =  $data["tiertag_title"];
//                            $category =  $data["category"];
//                              $file = $_FILES['imgfile']['name'];
//                               
//                                $this->addtiertag("", $tid,$title, $category, $file);
//                                $this->view->succMesg = 'Tier1 Updated succesfully';
//                                echo "Success";
//                    }
//              }
             
          
               if (!empty($tid)) {

                    $attInfo = $objTierTag->fetchTierTagByID($tid,"tier1");
                    $this->view->attInfo = $attInfo;
                }
     }
      public function savetieroneAction() {
       $this->checklogin();
          $this->getHelper('layout')->disableLayout();
          $objTierTag  = new Admin_Model_Product();
          
          $request = $this->getRequest();
          $data    = $request->getParams();
          $tid    = $this->_request->getParam("tid");
          $category = $this->_request->getParam("category");
          
        
             if ($this->getRequest()->isPost()) {
                    if (isset($_POST['submit'])) {
                          $tid =  $data["hdnattId"];
                           $title =  $data["tiertag_title"];
                            $category =  $data["category"];
                              $file = $_FILES['imgfile']['name'];
                               
                                $this->addtiertag("", $tid,$title, $category, $file);
                                $this->view->succMesg = 'Tier1 Updated succesfully';
                                echo "Success";
                    }
              }            
            
     }
     
     
      public function addtiertwoAction() {
          $this->checklogin();
          $this->getHelper('layout')->disableLayout();
          $objTierTag  = new Admin_Model_Product();
          
          //To have tier 
          $objProduct = new Admin_Model_Product();
          $tier1DropDownList  = $objProduct->fetchTier1List();   
          $this->view->tier1DropDownList  = $tier1DropDownList;
          
          $request = $this->getRequest();
          $data    = $request->getParams();
          $tid    = $this->_request->getParam("tid");
          $category = $this->_request->getParam("category");
          
          
               if (!empty($tid)) {
                    
                    $attInfo = $objTierTag->fetchTierTagByID($tid, "tier2");
                    $this->view->attInfotwo = $attInfo;
                }
     }
     
      public function savetiertwoAction() {
        $this->checklogin();
          $this->getHelper('layout')->disableLayout();
          $objTierTag  = new Admin_Model_Product();
          
          //To have tier 
          $objProduct = new Admin_Model_Product();
          $tier1DropDownList  = $objProduct->fetchTier1List();   
          $this->view->tier1DropDownList  = $tier1DropDownList;
          
          $request = $this->getRequest();
          $data    = $request->getParams();
          $tid    = $this->_request->getParam("tid");
          $category = $this->_request->getParam("category");
          
          
             if ($this->getRequest()->isPost()) {
                    if (isset($_POST['submit'])) {
                        
                        $tier1Id =  $data["selectProdline"];
                          $tid =  $data["hdnattId"];
                           $title =  $data["tiertag_title"];
                            $category =  $data["category"];
                              $file = "";//$_FILES['imgfile']['name'];

                                $this->addtiertag($tier1Id, $tid, $title, $category, $file);
                                 $this->view->succMesg = 'Tier2 Updated succesfully';
                    }
              }
          
                     
            
     }
      public function addtagoneAction() {
          $this->checklogin();
          $this->getHelper('layout')->disableLayout();
          $objTierTag  = new Admin_Model_Product();
          
          $request = $this->getRequest();
          $data    = $request->getParams();
          $tid     = $this->_request->getParam("tid");
          $category = $this->_request->getParam("category");
          
            
               if (!empty($tid)) {

                    $attInfo = $objTierTag->fetchTierTagByID($tid,"tag1");
                    $this->view->attInfo = $attInfo;
                }
     }
      public function savetagoneAction() {
         $this->checklogin();
          $this->getHelper('layout')->disableLayout();
          $objTierTag  = new Admin_Model_Product();
          
          $request = $this->getRequest();
          $data    = $request->getParams();
          $tid     = $this->_request->getParam("tid");
          $category = $this->_request->getParam("category");
          
             if ($this->getRequest()->isPost()) {
                    if (isset($_POST['submit'])) {
                          $tid =  $data["hdnattId"];
                           $title =  $data["tiertag_title"];
                            $category =  $data["category"];
                              $file = "";//$_FILES['imgfile']['name'];

                                $this->addtiertag("", $tid,$title, $category, $file);
                                 $this->view->succMesg = 'Tag1 Updated succesfully';
                    }
              }       
            
     }
      public function addtagtwoAction() {
          $this->checklogin();
          $this->getHelper('layout')->disableLayout();
          $objTierTag  = new Admin_Model_Product();
          
          $request = $this->getRequest();
          $data    = $request->getParams();
          $tid    = $this->_request->getParam("tid");
          $category = $this->_request->getParam("category");
          
               if (!empty($tid)) {

                    $attInfo = $objTierTag->fetchTierTagByID($tid,"tag2");
                    $this->view->attInfo = $attInfo;
                }
     }
    public function savetagtwoAction() {
         $this->checklogin();
          $this->getHelper('layout')->disableLayout();
          $objTierTag  = new Admin_Model_Product();
          
          $request = $this->getRequest();
          $data    = $request->getParams();
          $tid    = $this->_request->getParam("tid");
          $category = $this->_request->getParam("category");
          
             if ($this->getRequest()->isPost()) {
                    if (isset($_POST['submit'])) {
                          $tid =  $data["hdnattId"];
                           $title =  $data["tiertag_title"];
                            $category =  $data["category"];
                              $file = ""; //$_FILES['imgfile']['name'];

                                $this->addtiertag("", $tid,$title, $category, $file);
                                $this->view->succMesg = 'Tag2 Updated succesfully';
                    }
              }      
            
     }
     // This action would handle request from all tiers and tags sections
     public function addtiertag($tier1Id, $tid,$title, $category, $file) {
         
            $this->getHelper('layout')->disableLayout();
        //    $this->_helper->viewRenderer->setNoRender(true);

            //Creates Objects of Product Class
            $objTierTag  = new Admin_Model_Product();
           
            /*Saves file to specified folder*/   
            if ($file != "") {
                $checkExtension = explode(".", $file);
                
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/tier1Images/" . $_FILES["imgfile"]["name"])) {
                 
                 } else {
                    move_uploaded_file($_FILES["imgfile"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/tier1Images/" . $_FILES["imgfile"]["name"]);
               }
            }

            else {
                $image_name = "";
                $thumbImg = '';
            }
                    
          /*Checks and determines whether to add or edit teirs/tags*/   
            if($tid != ""){
                    //update
                    if($file != ''){
                        $objTierTag->addTierTag($tier1Id,$tid, $title, $category, $file);
                        $this->view->succMesg = 'Tier created succesfully';
                    }
                    else
                    {
                        $fileNameResult =  $objTierTag->getFilebyId($tid, $category);
                        $objTierTag->addTierTag($tier1Id, $tid, $title, $category, $fileNameResult[0][$category.'_image']);
                        $this->view->succMesg = 'Tier created succesfully';
                    }   
                }
                else{
                    //insert
                    $objTierTag->addTierTag($tier1Id,"", $title, $category, $file);
                    $this->view->succMesg = 'Tier created succesfully';
                }
                return true;
     }
     
     public function deltiertagAction(){
         
           $this->_helper->viewRenderer->setNoRender(TRUE);
         
            $tid    = $this->_request->getParam("tid");
            $cat    = $this->_request->getParam("cat");
            
            echo $cat;
            
            $objTierTag  = new Admin_Model_Product();
            $result = $objTierTag->delTierTag($cat,$tid);
          //  header('Location: /admin/product-management/category-setup');
     }
     
     public function ajaxUpdateMasterNamesAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $request = $this->getRequest();
        $data = $request->getParams();
        $objField  = new Admin_Model_Product();

        if (!empty($data)) {
            $objField->editMasterName($data);
        }
//        if (!empty($data['masterSubAttName'])) {
//            $objAtt->editMasterSubAttName($data['masterSubAttName']);
//        }
    }
   
     public function downloadAction() {
       
                $filename = 'Product Template.csv';
         

            $filename = "$filename";
            $filename = realpath($filename);
   
            $file_extension = strtolower(substr(strrchr($filename, "."), 1));

            switch ($file_extension) {
                case "pdf": $ctype = "application/pdf";
                    break;
                case "exe": $ctype = "application/octet-stream";
                    break;
                case "zip": $ctype = "application/zip";
                    break;
                case "doc": $ctype = "application/msword";
                    break;
                case "xls": $ctype = "application/vnd.ms-excel";
                    break;
                case "csv": $ctype = "text/csv";
                    break;
                case "ppt": $ctype = "application/vnd.ms-powerpoint";
                    break;
                case "gif": $ctype = "image/gif";
                    break;
                case "png": $ctype = "image/png";
                    break;
                case "jpe": case "jpeg":
                case "jpg": $ctype = "image/jpg";
                    break;
                default: $ctype = "application/force-download";
            }

            if (!file_exists($filename)) {
                die("NO FILE HERE");
            }

            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private", false);
            header("Content-Type: $ctype");
            header("Content-Disposition: attachment; filename=\"" . basename($filename) . "\";");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . @filesize($filename));
            set_time_limit(0);
            @readfile("$filename") or die("File not found.");
            ob_flush();exit;
        
    }
    
    public function ajaxTier2Action() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $request = $this->getRequest();
        $data = $request->getParams();
        $objField  = new Admin_Model_Product();
        
        if(isset($data['tier1Id'])){ //fetch tier2 records
            $html = '';
            $tier2List = $objField->fetchTierRecord("tier2","tier1_id",$data['tier1Id']);
           // $html = "<script type='text/javascript'>$(document).ready(function(){ $('.tier2_ajax').change(function() {  var tier2Id = $(this).attr('value'); var action = '/admin/product-management/ajax-tier3/';var data = 'tier2Id='+tier2Id; var response =  getajaxResponse(action,data); $('#tier3Data').html(response); }); }); </script>";
            if(!empty($tier2List)){
                $html .= "<label style='width: 55px;'>Tier 2</label><select class='styled' name='tier2' id='tier2' style='width:82%'><option value='none'>Select - Tier2</option>";
                foreach ($tier2List as $key => $value) {
                    $html .= "<option value='".$value['tier2_id']."'>".$value['tier2_name']."</option>";
                }
                $html .= " </select>";
                echo $html;die;
            }else{
                $html .= "<label style='width: 55px;'>Tier 2</label><select class='styled' name='tier2' id='tier2' style='width:82%'><option value='none' selected='selected'>Record not found</option></select>";
                echo $html;die;
            }
            
        }
      
    }
    
     public function ajaxTier3Action() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $request = $this->getRequest();
        $data = $request->getParams();
        $objField  = new Admin_Model_Product();
        
        if(isset($data['tier2Id'])){ //fetch tier3 records
            $tier3List = $objField->fetchTierRecord("tier3","tier2_id",$data['tier2Id']); 
            $html1 = "";
            if(!empty($tier3List)){
                $html1 .= "<select class='styled' name='tier3' id='tier3'><option value='none'>Select - Tier3</option>";
                foreach ($tier3List as $key => $value) {
                    $html1 .= "<option value='".$value['tier3_id']."'>".$value['tier3_name']."</option>";
                }
                $html1 .= " </select>";
                echo $html1;die;
            }else{
                $html1 .= "<select class='styled' name='tier3' id='tier3'><option value='none' selected='selected'>Record not found</option></select>";
                echo $html1;die;
            }
            
        }
      
    }
    
    public function ajaxTier3Tier1Action() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $request = $this->getRequest();
        $data = $request->getParams();
        $objField = new Admin_Model_Product();
        
        if (isset($data['tier1Id'])) { 
            $html1 = "";
            $tier2List = $objField->fetchTierRecord("tier2", "tier1_id", $data['tier1Id']);
            
            if (!empty($tier2List)) {
                $tier2Arr = array();
                foreach ($tier2List as $key => $value) {
                    array_push($tier2Arr, $value['tier2_id']);
                }
                $tier2Id_List = implode(",", $tier2Arr);
                $tier2Listing = $objField->fetchTier3inList($tier2Id_List);

                if (!empty($tier2Listing)) {
                    $html1 .= "<select class='styled' name='tier3' id='tier3'><option value='none'>Select - Tier3</option>";
                    foreach ($tier2Listing as $key => $value) {
                        $html1 .= "<option value='" . $value['tier3_id'] . "'>" . $value['tier3_name'] . "</option>";
                    }
                    $html1 .= " </select>";
                    echo $html1;
                    die;
                } else {
                    $html1 .= "<select class='styled' name='tier3' id='tier3'><option value='none' selected='selected'>Record not found</option></select>";
                    echo $html1;
                    die;
                }
            } else {
                $html1 .= "<select class='styled' name='tier3' id='tier3'><option value='none' selected='selected'>Record not found</option></select>";
                echo $html1;
                die;
            }
        }
    }

}

