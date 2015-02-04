<?php

class Admin_Model_Attribute extends Zend_Db_Table_Abstract {

    protected $_attributes = 'attributes';
    protected $_subAttributes = 'sub_attributes';
    protected $_att_subatt_name = 'att_subatt_name';
    protected $_tiers_tags_name = 'tiers_tags_name';
    
      public function fetchTiersTagsNameList() {
        $sql = $this->_db->select()
                ->from($this->_tiers_tags_name);
        try {
            $result = $this->_db->fetchRow($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    public function fetchAttList() {
        $sql = $this->_db->select()
                ->from($this->_attributes)
                ->order('rank ASC');
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
         public function fetchShortCodeList() {
        
         $sql = $this->_db->select()
                ->from(array('s' => $this->_subAttributes), array('s.subatt_id', 's.short_code'))
                ->order('s.rank ASC');
           
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    public function fetchSubAttList() {
        
         $sql = $this->_db->select()
                ->from(array('s' => $this->_subAttributes), array('*'))
                ->joinLeft(array('a' => $this->_attributes), 's.attribute_id = a.attribute_id', array('attribute_name','attribute_image'))
                ->order('a.rank ASC') 
                ->order('s.rank ASC');
             
       /* $sql = $this->_db->select()
                ->from($this->_subAttributes);*/
        try {
            $result = $this->_db->fetchAll($sql); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function fetchActiveAttList() {
        $sql = $this->_db->select()
                ->from($this->_attributes)
                ->where('display = ?', 'y')
         ->order('rank ASC');
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
//////////////////////
  public function fetchAttListexport() {
        $sql = $this->_db->select()
                ->from($this->_attributes)
                ->where('display = ?', 'y')
                ->order('rank ASC');
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    public function fetchSubAttListexport() {
        
         $sql = $this->_db->select()
                ->from(array('s' => $this->_subAttributes), array('*'))
                ->joinLeft(array('a' => $this->_attributes), 's.attribute_id = a.attribute_id', array('attribute_name','attribute_image'))
               ->where('s.display = ?', 'y')
                 ->order('a.rank ASC') 
                ->order('s.rank ASC');
             
       /* $sql = $this->_db->select()
                ->from($this->_subAttributes);*/
        try {
            $result = $this->_db->fetchAll($sql); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function fetchSubattByAttIdexport($attId){
       if(empty($attId)){
           return false;
       } 
       $sql = $this->_db->select()
                ->from(array('s' => $this->_subAttributes), array('*'))
                ->joinLeft(array('a' => $this->_attributes), 's.attribute_id = a.attribute_id', array('attribute_name'))
                ->where('s.attribute_id = ?', $attId)
        ->where('s.display = ?', 'y')
       ->order('s.rank ASC');
      /* $sql = $this->_db->select()
                ->from($this->_subAttributes)
                ->where('attribute_id = ?', $attId);*/
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
        
    }
    /////////////////////////////
    public function fetchActiveSubAttList() {
        
         $sql = $this->_db->select()
                ->from(array('s' => $this->_subAttributes), array('*'))
                ->joinLeft(array('a' => $this->_attributes), 's.attribute_id = a.attribute_id', array('attribute_name'))
                ->where('s.display = ?', 'y')
              ->order('s.rank ASC');
       /* $sql = $this->_db->select()
                ->from($this->_subAttributes);*/
        try {
            $result = $this->_db->fetchAll($sql); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
   public function fetchSubattByAttId($attId){
       if(empty($attId)){
           return false;
       } 
       $sql = $this->_db->select()
                ->from(array('s' => $this->_subAttributes), array('*'))
                ->joinLeft(array('a' => $this->_attributes), 's.attribute_id = a.attribute_id', array('attribute_name'))
                ->where('s.attribute_id = ?', $attId)
        ->order('s.rank ASC');
      /* $sql = $this->_db->select()
                ->from($this->_subAttributes)
                ->where('attribute_id = ?', $attId);*/
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
        
    }
    public function fetchActiceSubattByAttId($attId){
       if(empty($attId)){
           return false;
       } 
       $sql = $this->_db->select()
                ->from(array('s' => $this->_subAttributes), array('*'))
                ->joinLeft(array('a' => $this->_attributes), 's.attribute_id = a.attribute_id', array('attribute_name'))
                ->where('s.attribute_id = ?', $attId)
                ->where('s.display = ?', 'y')
                ->order('s.rank ASC');
       
      /* $sql = $this->_db->select()
                ->from($this->_subAttributes)
                ->where('attribute_id = ?', $attId);*/
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
        
    }

    public function updateAttSubattChk($data) { 
        if (empty($data)) {
            return false;
        }

        if ($data['type'] == 'attClass') { // Chk Attribute
           $sql = "UPDATE attributes SET display='" . $data['display'] . "' WHERE attribute_id='" . $data['attId'] . "'";
            
            $sql1 = "UPDATE sub_attributes SET display='" . $data['display'] . "' WHERE attribute_id='" . $data['attId'] . "'";
            $result1 = $this->_db->query($sql1);
          }else if ($data['type'] == 'calciSubAtt') {  // Chk Claci SubAttribute
            $sql = "UPDATE sub_attributes SET calci_subatt='" . $data['display'] . "' WHERE subatt_id='" . $data['attId'] . "'";
        } else { // Chk SubAttribute
            $sql = "UPDATE sub_attributes SET display='" . $data['display'] . "' WHERE subatt_id='" . $data['attId'] . "'";
        }
        try {
            $result = $this->_db->query($sql);
            return true;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    public function addSubatt($attId,$subAttName){
        //$now = new Zend_Db_Expr('NOW()');
        $rank = $this->fetchMaxRankValue('sub_attributes');
        $details = array(
            'attribute_id' => $attId,
            'subatt_name' => $subAttName,
            'rank' => $rank
        );
       
        try {
            if ($this->_db->insert($this->_subAttributes, $details)) {
                $lastInsertId = $this->_db->lastInsertId($this->_subAttributes, 'subatt_id');
                return $lastInsertId;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
     public function editSubatt($attId, $subAttName, $id) {
        $where['subatt_id =?'] = $id;
        $details = array(
            'attribute_id' => $attId,
            'subatt_name' => $subAttName
        );

        try {
            $this->_db->update($this->_subAttributes, $details, $where);
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function editAtt($image_name, $thumbImg, $attName, $id) {
        $where['attribute_id =?'] = $id;
        $details = array(
            'attribute_name' => $attName,
            'attribute_image' => $thumbImg
        );

        try {
            $this->_db->update($this->_attributes, $details, $where);
            if (!empty($image_name)) {
                $path = $_SERVER['DOCUMENT_ROOT'] . "/attributeImages/" . $image_name;
                unlink($path);
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    public function fetchSubattByID($id) {
        if (empty($id)) {
            return false;
        }
        $sql = $this->_db->select()
                ->from($this->_subAttributes)
                ->where('subatt_id = ?', $id);
        try {
            $result = $this->_db->fetchRow($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function fetchAttByID($id) {
        if (empty($id)) {
            return false;
        }
        $sql = $this->_db->select()
                ->from($this->_attributes)
                ->where('attribute_id = ?', $id);
        try {
            $result = $this->_db->fetchRow($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    
    public function delAtt($id, $type) {
        if (empty($id) || empty($type)) {
            return false;
        }

        if ($type == 'subAtt') {
            $where['subatt_id=?'] = $id;
            $table = $this->_subAttributes;

            try {
                $this->_db->delete($table, $where);
                return 'Delete';
            } catch (Zend_Db_Adapter_Exception $e) {
                return false;
            }
        } elseif ($type == 'att') {
            $where['attribute_id=?'] = $id;
            $table = $this->_attributes;

            $sql = "DELETE a,b FROM attributes a
                    left JOIN sub_attributes b ON b.attribute_id = a.attribute_id
                    WHERE a.attribute_id = '$id';";
            try {
                $this->_db->query($sql);
                return 'Delete';
            } catch (Zend_Db_Adapter_Exception $e) {
                return false;
            }
        }
    }

    public function insertAttInfo($image_name,$thumb_img,$attName) { 
        if (empty($attName)){
            return false;
        }
        $rank = $this->fetchMaxRankValue('attributes');  
        $details = array(
            'attribute_name' => $attName,
            'attribute_image' => $thumb_img,
            'rank' =>  $rank
        );
        try {
            if ($this->_db->insert($this->_attributes, $details)) {
                if (!empty($image_name)) {
                    $path = $_SERVER['DOCUMENT_ROOT'] . "/attributeImages/" . $image_name;
                    unlink($path);
                }
                $lastInsertId = $this->_db->lastInsertId($this->_attributes, 'attribute_id'); //return $lastInsertId;
                return true;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    
    public function fetchAttSubAttMasterName() {
        $sql = $this->_db->select()
                ->from($this->_att_subatt_name);
        try {
            $result = $this->_db->fetchRow($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function editMasterAttName($masterAttName) {
        $where['id  =?'] = '1';
        $details = array(
            'att_name' => $masterAttName
        );

        try {
            if($this->_db->update($this->_att_subatt_name, $details, $where)){
                return true;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function editMasterSubAttName($masterSubAttName) {
        $where['id  =?'] = '1';
        $details = array(
            'subAtt_name' => $masterSubAttName
        );

        try {
            if($this->_db->update($this->_att_subatt_name, $details, $where)){
                return true;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    //Att Rank <<
    public function updateAttRank($jsonString_result) {     
        $output = array_slice($jsonString_result, 1); 
        
        foreach ($output as $data) {
            $details = array('rank' => $data['rank']);
            $where['attribute_id  =?'] = $data['typeid'];
            $this->_db->update($this->_attributes, $details, $where);
        }
    }
    public function updateSubAttRank($jsonString_result) {     
        $output = array_slice($jsonString_result, 1); 
        
        foreach ($output as $data) {
            $details = array('rank' => $data['rank']);
            $where['subatt_id  =?'] = $data['typeid'];
            $this->_db->update($this->_subAttributes, $details, $where);
        }
    }
    public function fetchMaxRankValue($param){
        if($param == 'attributes'){
            $sql= "SELECT MAX(rank) as rank FROM `attributes` ";
        }else{
            $sql= "SELECT MAX(rank) as rank FROM `sub_attributes` ";
        }
        $result = $this->_db->fetchRow($sql);
        return $result['rank']+1;
    }
    //Att Rank >>
    
}

?>
