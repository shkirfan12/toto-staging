<?php

class Default_Model_Collection extends Zend_Db_Table_Abstract {

    protected $_collection = 'collection';

    public function fetchCollectionList($userId) {
        $sql = $this->_db->select()
                ->from($this->_collection)
                ->where('user_id = ' . $userId);
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    public function delCollectionById($collectionId) {
        if (empty($collectionId)) {
            return false;
        }
        $table = $this->_collection;

        try {
            $where[] = "collection_id = $collectionId";

            if ($this->_db->delete($table, $where)) {
                return 'success';
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function enterCollectionInfo($userId, $collectionName) {
        if (empty($userId) || empty($collectionName)) {
            return false;
        }
        $details = array(
            'user_id' => $userId,
            'collection_name' => $collectionName
        );
        try {
            if ($this->_db->insert($this->_collection, $details)) {
                $lastInsertId = $this->_db->lastInsertId($this->_collection, 'collection_id');
                return $lastInsertId;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function fetchCollectionByID($collectionId) {
        if (empty($collectionId)) {
            return false;
        }
        $sql = $this->_db->select()
                ->from($this->_collection)
                ->where('collection_id = ?', $collectionId);
        try {
            $result = $this->_db->fetchRow($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    
    public function editCollection($userId,$param) {
        if(empty($userId) || empty($param)){
            return false;
        }
        $now = new Zend_Db_Expr('NOW()');
        $where['collection_id =?'] = $param['hdnCollectionId'];
        $details = array(
            'user_id' => $userId,
            'collection_name' => $param['collection_name'],
            'modified_date' => $now
        );

        try {
            $this->_db->update($this->_collection, $details, $where);
            return true;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function collectionTranscation1($productId,$cname,$userId) {
        if(empty($productId)  || empty($cname) || empty($userId)){
            return false;
        }
        //echo $productId.'--'.$cId.'--'.$cname.'--'.$userId; die;
         // new collection: insert
            $details = array(
                'user_id' => $userId,
                'collection_name' => $cname,
                'product_id'=> $productId.','
            );
            try {
                if ($this->_db->insert($this->_collection, $details)) {
                    $lastInsertId = $this->_db->lastInsertId($this->_collection, 'collection_id');
                    return $lastInsertId;
                }
            } catch (Zend_Db_Adapter_Exception $e) {
                return false;
           
        } 
        if($cname == 'none'){ // update collection
            $info = $this->fetchCollectionByID($cId);
            
            if(!empty($info['product_id'])){
                $id = $info['product_id'].$productId;
                $arr = explode(',', $id);
                $result = array_unique($arr);
                $productId = implode(',', $result);  
            }
            
            $where['collection_id =?'] = $cId;
            $now = new Zend_Db_Expr('NOW()');
            $details = array(
                'user_id' => $userId,
                'product_id'=> $productId.',',
                'modified_date' => $now
            );
            try {
                $this->_db->update($this->_collection, $details, $where);
                return true;
            } catch (Zend_Db_Adapter_Exception $e) {
                return false;
            }
        }
        
    }
    
    public function collectionTranscation($productId,$cId,$cname,$userId) {
        if(empty($productId) || empty($cId) || empty($cname) || empty($userId)){
            return false;
        }
        //echo $productId.'--'.$cId.'--'.$cname.'--'.$userId; die;
        if($cId == 'none' || $cId == 'undefined'){ // new collection: insert
            $details = array(
                'user_id' => $userId,
                'collection_name' => $cname,
                'product_id'=> $productId.','
            );
            try {
                if ($this->_db->insert($this->_collection, $details)) {
                    $lastInsertId = $this->_db->lastInsertId($this->_collection, 'collection_id');
                    return $lastInsertId;
                }
            } catch (Zend_Db_Adapter_Exception $e) {
                return false;
            }
        }else if($cname == 'none'){ // update collection
            $info = $this->fetchCollectionByID($cId);
            
            if(!empty($info['product_id'])){
                $id = $info['product_id'].$productId;
                $arr = explode(',', $id);
                $result = array_unique($arr);
                $productId = implode(',', $result);  
            }
            
            $where['collection_id =?'] = $cId;
            $now = new Zend_Db_Expr('NOW()');
            $details = array(
                'user_id' => $userId,
                'product_id'=> $productId.',',
                'modified_date' => $now
            );
            try {
                $this->_db->update($this->_collection, $details, $where);
                return true;
            } catch (Zend_Db_Adapter_Exception $e) {
                return false;
            }
        }
        
    }
    public function deleteProductId4rmCollection($productId,$collectionId){  
        if(empty($productId) || empty($collectionId) ){
            return false;
        }
        
        $collectionInfo = $this->fetchCollectionByID($collectionId); 
        $trimmed = str_replace($productId.',', '', $collectionInfo['product_id']); 
        $sql = "UPDATE collection SET product_id='$trimmed' WHERE collection_id='$collectionId'";
        $result = $this->_db->query($sql);
        return 'success';
    }


}

?>
