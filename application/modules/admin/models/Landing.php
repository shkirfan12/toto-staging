<?php

class Admin_Model_Landing extends Zend_Db_Table_Abstract {

    protected $_preset = 'preset';
    protected $_users  = 'users';

    public function fetchLandingInfoById($id) { 
        if (empty($id)) {
            return false;
        }
        $sql = $this->_db->select()
                ->from($this->_preset)
                ->where('preset_id = ?', $id);

        try {
            $result = $this->_db->fetchRow($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function fetchLandingInfoByUserId($userId) {
        if (empty($userId)) {
            return false;
        }
        $sql = $this->_db->select()
                ->from($this->_preset)
                ->where('client_id = ?', $userId);

        try {
            $result = $this->_db->fetchRow($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function fetchLandingInfo() {
        
        $sql = $this->_db->select()
                ->from($this->_preset);
        try {
            $result = $this->_db->fetchRow($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function fetchSuperadminLandingInfo($userType) {
        if (empty($userType)) {
            return false;
        }
        $sql = $this->_db->select()
                ->from($this->_users)
                ->where('userType = ?', $userType);

        try {
            $result = $this->_db->fetchRow($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    
    public function fetchClientadminLandingInfo($userType){
        if (empty($userType)) {
            return false;
        }
        $sql = $this->_db->select()
                ->from($this->_users)
                ->where('userType = ?', $userType);

        try {
            $result = $this->_db->fetchRow($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    
    //Main Img <<
    public function inserMainImg($mainImage, $user_id) { 
        if (empty($mainImage) || empty($user_id)) {
            return false;
        }
        $details = array(
            'client_id' => $user_id,
            'main_image' => $mainImage
        );

        try {
            if ($this->_db->insert($this->_preset, $details)) {
                if(!empty($mainImage)){
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $mainImage)) {
                        $path = $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $mainImage;
                        unlink($path);
                    }
                }

                $lastInsertId = $this->_db->lastInsertId($this->_preset, 'preset_id');
                return $lastInsertId;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    public function editMainImg($image_name, $id) {
        if (empty($id) || empty($image_name)) {
            return false;
        }
        $info = $this->fetchLandingInfoByUserId($id);
        $mainImage = $info['main_image'];
        if(!empty($mainImage)){
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/main/" . $mainImage)) {
                $pathThumb = $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/main/" . $mainImage;
                unlink($pathThumb);
            }
        }

        $where['client_id =?'] = $id;
        $details = array(
            'main_image' => $image_name
        );

        try {
            $this->_db->update($this->_preset, $details, $where);
            if(!empty($image_name)){
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $image_name)) {
                    $path = $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $image_name;
                    unlink($path);
                }
            }
            return true;
           // return $id;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    //Main Img >>
    
    //Favicon Img <<
    public function inserFaviconImg($mainImage, $user_id) {
        if (empty($mainImage) || empty($user_id)) {
            return false;
        }
        $details = array(
            'client_id' => $user_id,
            'favicon_image' => $mainImage
        );

        try {
            if ($this->_db->insert($this->_preset, $details)) {
                if(!empty($mainImage)){
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $mainImage)) {
                        $path = $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $mainImage;
                        unlink($path);
                    }
                }

                $lastInsertId = $this->_db->lastInsertId($this->_preset, 'preset_id');
                return $lastInsertId;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    public function editFaviconImg($id,$image_name) {
        if (empty($id) || empty($image_name)) {
            return false;
        }
        $info = $this->fetchLandingInfoByUserId($id);
        $mainImage = $info['favicon_image'];
        if(!empty($mainImage)){
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/favicon/" . $mainImage)) {
                $pathThumb = $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/favicon/" . $mainImage;
                unlink($pathThumb);
            }
        }

        $where['client_id =?'] = $id;
        $details = array(
            'favicon_image' => $image_name
        );

        try {
            $this->_db->update($this->_preset, $details, $where);
            if(!empty($image_name)){
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $image_name)) {
                    $path = $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $image_name;
                    unlink($path);
                }
            }
            return true;
            //return $id;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    //Favicon Img >>
    
    //Logo Img <<
    public function inserLogoImg($mainImage, $user_id) {
        if (empty($mainImage) || empty($user_id)) {
            return false;
        }
        $details = array(
            'client_id' => $user_id,
            'logo_image' => $mainImage
        );

        try {
            if ($this->_db->insert($this->_preset, $details)) {
               if(!empty($mainImage)){
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $mainImage)) {
                        $path = $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $mainImage;
                        unlink($path);
                    }
               }
                $lastInsertId = $this->_db->lastInsertId($this->_preset, 'preset_id');
                return $lastInsertId;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    public function editLogoImg($image_name, $id) { 
        if (empty($id) || empty($image_name)) {
            return false;
        }
        $info = $this->fetchLandingInfoByUserId($id);
        $mainImage = $info['logo_image'];
        if(!empty($mainImage)){
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/logo/" . $mainImage)) {
                $pathThumb = $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/logo/" . $mainImage;
                unlink($pathThumb);
            }
        }

        $where['client_id =?'] = $id;
        $details = array(
            'logo_image' => $image_name
        );

        try {
            $this->_db->update($this->_preset, $details, $where);
            if(!empty($image_name)){
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $image_name)) {
                    $path = $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $image_name;
                    unlink($path);
                }
            }
            return true;
           // return $id;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    //Logo Img >>
     //Start Search Img <<
    public function inserStartSearchImg($mainImage, $user_id) {
        if (empty($mainImage) || empty($user_id)) {
            return false;
        }
        $details = array(
            'client_id' => $user_id,
            'start_search_image' => $mainImage
        );

        try {
            if ($this->_db->insert($this->_preset, $details)) {
                 if(!empty($mainImage)){
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $mainImage)) {
                        $path = $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $mainImage;
                        unlink($path);
                    }
                 }

                $lastInsertId = $this->_db->lastInsertId($this->_preset, 'preset_id');
                return $lastInsertId;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    public function editStartSearchImg($image_name, $id) {
        if (empty($id) || empty($image_name)) {
            return false;
        }
        $info = $this->fetchLandingInfoByUserId($id);
        $mainImage = $info['start_search_image'];
        if(!empty($mainImage)){
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/startSearch/" . $mainImage)) {
                $pathThumb = $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/startSearch/" . $mainImage;
                unlink($pathThumb);
            }
        }

        $where['client_id =?'] = $id;
        $details = array(
            'start_search_image'=>$image_name
        );
      
        try {
            $this->_db->update($this->_preset, $details, $where);
              if(!empty($image_name)){
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $image_name)) {
                    $path = $_SERVER['DOCUMENT_ROOT'] . "/landingPageImages/" . $image_name;
                    unlink($path);
                }
              }
            return true;
           // return $id;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    //Start Search Img >>
    public function fetchThemeByUserId($user_id) {  
        if (empty($user_id)){
            return false;
        }
         $sql = $this->_db->select()
                ->from($this->_preset)
                ->where('client_id = ?', $user_id); 
        try {
            $result = $this->_db->fetchRow($sql); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
     }
     public function updateTheme($themeArray,$where) {
        try {
            $id = $this->_db->update($this->_preset, $themeArray, $where);
            return $id;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
  
}

?>
