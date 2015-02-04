<?php

class Admin_LandingPageController extends Zend_Controller_Action {

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
        $this->checklogin();
        Zend_Layout::startMvc(array(
            'layoutPath' => APPLICATION_PATH . '/layouts/scripts/',
            'layout' => 'adminLayout'
        ));
        $request = $this->getRequest();
        $data = $request->getParams();
        $id = $this->_request->getParam("id");
        $objLanding = new Admin_Model_Landing();
        
        //change coding <<
        $landingInfo = $objLanding->fetchLandingInfo(); 
        $this->view->landingInfo = $landingInfo; 
        $this->view->presetId = $landingInfo['preset_id'];
        //change coding >>
        
        /*if (!empty($id)) {
            $landingInfo = $objLanding->fetchLandingInfoByUserId($id); 
            if(!empty($landingInfo)){
                 $this->view->landingInfo = $landingInfo; 
            }else{
                 $landingInfo = $objLanding->fetchLandingInfoByUserId('1');
                 $this->view->landingInfo = $landingInfo; 
            }
            $this->view->presetId = $id;
        }*/
    }
     public function mainImgAction() { //https://code.google.com/p/upload-at-click/
        // error_reporting('0');
        $this->checklogin();
        $this->getHelper('layout')->disableLayout();
          $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $data = $request->getParams();
        $id = $this->_request->getParam("presetId"); 
        $objLanding = new Admin_Model_Landing();
        $loginSession = new Zend_Session_Namespace('login');
        $tmpFile = $_FILES['Filedata']['tmp_name'];
        $file = $_FILES['Filedata']['name'];

        if (!empty($file)) {
            $checkExtension = explode(".", $file);
            $image_name = $_FILES["Filedata"]["name"];
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $_FILES["Filedata"]["name"])) {
                
            } else {
                //move_uploaded_file($_FILES["Filedata"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $_FILES["Filedata"]["name"]);
                move_uploaded_file($_FILES["Filedata"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/main/" . $_FILES["Filedata"]["name"]);

                /* create thumb nail << */
               /* $targetfilepath = $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $_FILES["Filedata"]["name"];
                $mainThumbPath = $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/main/";
                $photos_dir = $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages";
                $main_thumb_w = '320';
                $main_thumb_h = '547';
                $this->thumbnailImg($targetfilepath, $mainThumbPath, $image_name, $main_thumb_w, $main_thumb_h, $checkExtension['1']); *///Main Image 
            }
            $objLanding = new Admin_Model_Landing();
            $info = $objLanding->fetchLandingInfoByUserId($id); 
             if (empty($info)) {//Insert DB 
            //if ($id == 'empty') {echo "A"; die;//Insert DB 
                $lastInsertId = $objLanding->inserMainImg($image_name, $loginSession->userId);
                echo '/landingPageImages/main/' . $image_name;die;
                //echo "/admin/landing-page/index/id/" . $lastInsertId;die;
            } else { //Edit DB
                $lastInsertId = $objLanding->editMainImg($image_name, $id);
                echo '/landingPageImages/main/' . $image_name;die;
            }
        }
    }
    
    public function faviconAction() { 
        $this->checklogin();
        $this->getHelper('layout')->disableLayout();
          $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $data = $request->getParams();
        $id = $this->_request->getParam("presetId");
        $objLanding = new Admin_Model_Landing();
        $loginSession = new Zend_Session_Namespace('login');
        $tmpFile = $_FILES['Filedata']['tmp_name'];
        $file = $_FILES['Filedata']['name'];

        if (!empty($file)) {
            $checkExtension = explode(".", $file);
            $image_name = $_FILES["Filedata"]["name"];
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $_FILES["Filedata"]["name"])) {
                
            } else { 
                //move_uploaded_file($_FILES["Filedata"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $_FILES["Filedata"]["name"]);
                move_uploaded_file($_FILES["Filedata"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/favicon/" . $_FILES["Filedata"]["name"]);
               
                /* create thumb nail << */
                /*$photos_dir = $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages";
                $thumbs_dir = $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/favicon";
                $this->set_thumb($image_name, $photos_dir, $thumbs_dir, '32', '100', $checkExtension['1']);*/ //Thumb Image
            }
            $objLanding = new Admin_Model_Landing();
            $info = $objLanding->fetchLandingInfoByUserId($id); 
             if (empty($info)) {
            //if ($id == 'empty') {//Insert DB 
                $lastInsertId = $objLanding->inserFaviconImg($image_name, $loginSession->userId);
                 echo '/landingPageImages/favicon/' . $image_name;die;
                //echo "/admin/landing-page/index/id/" . $lastInsertId;die;
            } else { //Edit DB
                $lastInsertId = $objLanding->editFaviconImg($id, $image_name);
                // echo "/admin/landing-page/index/id/".$lastInsertId; die;
                echo '/landingPageImages/favicon/' . $image_name;die;
            }
        }
    }
    
     public function logoAction() { 
        $this->checklogin();
        $this->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $data = $request->getParams();
        $id = $this->_request->getParam("presetId");
        $objLanding = new Admin_Model_Landing();
        $loginSession = new Zend_Session_Namespace('login');
        $tmpFile = $_FILES['Filedata']['tmp_name'];
        $file = $_FILES['Filedata']['name'];

        if (!empty($file)) {
            $checkExtension = explode(".", $file);
            $image_name = $_FILES["Filedata"]["name"];
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $_FILES["Filedata"]["name"])) {
                
            } else {
                move_uploaded_file($_FILES["Filedata"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $_FILES["Filedata"]["name"]);

                /* create thumb nail << */
                $targetfilepath = $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $_FILES["Filedata"]["name"];
                $mainThumbPath = $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/logo/";
                $photos_dir = $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages";
                $main_thumb_w = '144';
                $main_thumb_h = '29';
                $this->thumbnailImg($targetfilepath, $mainThumbPath, $image_name, $main_thumb_w, $main_thumb_h, $checkExtension['1']); //Main Image 
            }
            $objLanding = new Admin_Model_Landing();
            $info = $objLanding->fetchLandingInfoByUserId($id); 
             if (empty($info)) { 
            //if ($id == 'empty') {//Insert DB 
                $lastInsertId = $objLanding->inserLogoImg($image_name, $loginSession->userId);
                 echo '/landingPageImages/logo/' . $image_name;die;
                //echo "/admin/landing-page/index/id/" . $lastInsertId;die;
            } else { //Edit DB
                $lastInsertId = $objLanding->editLogoImg($image_name, $id);
                echo '/landingPageImages/logo/' . $image_name;die;
            }
        }
    }
     public function startSearchAction() { 
        $this->checklogin();
        $this->getHelper('layout')->disableLayout();
          $this->_helper->viewRenderer->setNoRender();
        $request = $this->getRequest();
        $data = $request->getParams();
        $id = $this->_request->getParam("presetId"); 
        $objLanding = new Admin_Model_Landing();
        $loginSession = new Zend_Session_Namespace('login');
        $tmpFile = $_FILES['Filedata']['tmp_name'];
        $file = $_FILES['Filedata']['name'];

        if (!empty($file)) {
            $checkExtension = explode(".", $file);
            $image_name = $_FILES["Filedata"]["name"];
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $_FILES["Filedata"]["name"])) {
                
            } else {
                move_uploaded_file($_FILES["Filedata"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $_FILES["Filedata"]["name"]);

                /* create thumb nail << */
                $targetfilepath = $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $_FILES["Filedata"]["name"];
                $mainThumbPath = $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/startSearch/";
                $photos_dir = $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages";
                $main_thumb_w = '314';
                $main_thumb_h = '72';
                $this->thumbnailImg($targetfilepath, $mainThumbPath, $image_name, $main_thumb_w, $main_thumb_h, $checkExtension['1']); //Main Image 
            }
            $objLanding = new Admin_Model_Landing();
            $info = $objLanding->fetchLandingInfoByUserId($id);
             if (empty($info)) {
            //if ($id == 'empty') {//Insert DB 
                $lastInsertId = $objLanding->inserStartSearchImg($image_name, $loginSession->userId);
                  echo '/landingPageImages/startSearch/' . $image_name;die;
            } else {  //Edit DB
                $lastInsertId = $objLanding->editStartSearchImg($image_name, $id);
                  echo '/landingPageImages/startSearch/' . $image_name;die;
            }
        }
    }
     public function themesaveAction() {
         
        $this->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        $theme_db = new Admin_Model_Landing();
        $request  = $this->getRequest();
     
        $Callbacksbodyfont = $request->getParam('Callbacksbodyfont');
        $Callbacksfooterfont = $request->getParam('Callbacksfooterfont');
        $CallbacksSearchfont = $request->getParam('CallbacksSearchfont');
        $copy1Text = $request->getParam('copy1Text');
        $copy2Text = $request->getParam('copy2Text');
        $startSearchContent = $request->getParam('startSearchContent');

        $loginSession = new Zend_Session_Namespace('login');
        
        //$themeInfo = $theme_db->fetchThemeByUserId($loginSession->userId); 
        
        $themeInfo = $theme_db->fetchLandingInfo(); 
     
        
        if(!empty($themeInfo)){ // Already exsist : Update
            $themeArray = array(
                'copy1_color' => $Callbacksbodyfont,
                'copy2_color' => $Callbacksfooterfont,
                'start_search_color' => $CallbacksSearchfont,
                'copy1_content' => $copy1Text,
                'copy2_content' => $copy2Text,
                'start_search_content' => $startSearchContent
            );
            /*$where = array(
                'client_id = ?' => $themeInfo['client_id']
             );*/
            $where = array(
                'preset_id = ?' => $themeInfo['preset_id']
             );
            
            $id = $theme_db->updateTheme($themeArray,$where);
        }/*else{ // Insert
             $themeArray = array(
                'created_by'      => $userId,
                'user_type'       => $userType,
                'header_color'    => $Callbacks,
                'body_color'      => $Callbacksbody,
                'footer_color'    => $Callbacksfooter,
                'headerFontColor' => $CallbacksFont,
                'bodyFontColor'   => $Callbacksbodyfont
               // 'footerFontColor' => $Callbacksfooterfont
            );
            $id = $theme_db->insertTheme($themeArray);
        }*/
        echo "success";
    }


    /* Thumbanail Creation For Batch Upload */
    function thumbnailImg($image_path, $thumb_path, $image_name, $thumb_width, $thumb_hight, $ext) {
        if (!strcmp("png", $ext)) {
            $src_img = imagecreatefrompng($image_path);
        } else {
            $src_img = imagecreatefromjpeg($image_path);
        }

        $new_w = $thumb_width;
        $new_h = $thumb_hight;

        $dst_img = imagecreatetruecolor($new_w, $new_h);
        imagealphablending($dst_img, false);
        imagesavealpha($dst_img, true);
        
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_w, $new_h, imagesx($src_img), imagesy($src_img));
        if (!strcmp("png", $ext)) {
            imagepng($dst_img, "$thumb_path/$image_name");
        } else {
            imagejpeg($dst_img, "$thumb_path/$image_name");
        }
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
            if (!strcmp("png", $ext)) {
                imagepng($thumb_p, $thumbs_dir . "/" . $file);
            } else {
                imagejpeg($thumb_p, $thumbs_dir . "/" . $file, $quality);
            }
        }
    }

}

