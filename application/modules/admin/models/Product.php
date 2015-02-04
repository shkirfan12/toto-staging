<?php

class Admin_Model_Product extends Zend_Db_Table_Abstract {

    protected $_tag1 = 'tag1';
    protected $_tag2 = 'tag2';
    protected $_tier1 = 'tier1';
    protected $_tier2 = 'tier2';
    protected $_tier3 = 'tier3';
    
    protected $_productImg = 'product_img';
    protected $_product = 'product';
    protected $_tier_tag_name = 'tiers_tags_name';

    
    public function productList() {
        $sql = $this->_db->select()
                ->from($this->_product)
                ->order('baseline DESC')
                ->order('product_id ASC');
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
   
    public function fetchTag1List() {
        $sql = $this->_db->select()
                ->from($this->_tag1);
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    
    public function fetchTag2List() {
        $sql = $this->_db->select()
                ->from($this->_tag2);
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    public function fetchTier1List() {
        $sql = $this->_db->select()
                ->from($this->_tier1);
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    
    public function fetchTier2List() {
        $sql = $this->_db->select()
                ->from($this->_tier2);
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function fetchTier2ByTier1($tier1IdList) {
        $sql = $this->_db->select()
                ->from($this->_tier2)
                ->where("tier1_id IN ($tier1IdList)"); 
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    
    public function fetchTier3List() {
        $sql = $this->_db->select()
                ->from($this->_tier3);
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    
    public function getFilebyId($tid, $category){
        $sql = "SELECT ".$category."_image FROM ".$category." WHERE ".$category."_id=$tid";
        //echo $sql; die;
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    
    public function insertProductDetail($data,$user_id){
        if($data['tier2'] == 'none'){ $tier2 = ''; }else{ $tier2 = $data['tier2']; }
        if($data['tier3'] == 'none'){ $tier3 = ''; }else{ $tier3 = $data['tier3']; }
        
        if(isset($data['tag1'])){ $tag1 = implode(',',$data['tag1']); }else{ $tag1 = ''; }
        if(isset($data['tag2'])){ $tag2 = implode(',',$data['tag2']); }else{ $tag2 = ''; }
        /*if($data['tag1']== 'none'){ $data['tag1'] = ''; }
        if($data['tag2']== 'none'){ $data['tag2'] = ''; }*/
        
        if(!isset($data['installation_price'])){ $data['installation_price'] = '';}
        if(!isset($data['replacement_price'])){ $data['replacement_price'] = '';}
        if(!isset($data['product_life_span'])){ $data['product_life_span'] = '';}
                  
        if(isset($data['chkBaseline'])){ 
            if($data['chkBaseline']== 'on'){
                $data['chkBaseline']= 'y';
            }
        }else{ $data['chkBaseline']= 'n'; }
        
        if(isset($data['chk_uses_day_flag'])){ 
            if($data['chk_uses_day_flag']== 'on'){
                $data['chk_uses_day_flag']= 'y';
            }
        }else{ $data['chk_uses_day_flag']= 'n'; $data['uses_day_value']= '';}
        
        if(isset($data['chk_uses_wk_flag'])){ 
            if($data['chk_uses_wk_flag']== 'on'){
                $data['chk_uses_wk_flag']= 'y';
            }
        }else{ $data['chk_uses_wk_flag']= 'n'; $data['uses_wk_value']= '';}
        
         if(isset($data['chk_hrs_day_flag'])){ 
            if($data['chk_hrs_day_flag']== 'on'){
                $data['chk_hrs_day_flag']= 'y';
            }
        }else{ $data['chk_hrs_day_flag']= 'n'; $data['hrs_day_value']= '';}
        
         if(isset($data['chk_hrs_wk_flag'])){ 
            if($data['chk_hrs_wk_flag']== 'on'){
                $data['chk_hrs_wk_flag']= 'y';
            }
        }else{ $data['chk_hrs_wk_flag']= 'n'; $data['hrs_wk_value']= '';}
        
         $details = array(
            'user_id' => $user_id,
            'tier1_id' => $data['tier1'],
            'tier2_id' => $tier2,
            'tier3_id' => $tier3,
            'tag1_id' => $tag1,
            'tag2_id' => $tag2,
            'product_name' => $data['product_name'],
            'product_img'=> $data['hdnProductImg'],
            'product_num' => $data['product_number'],
            'product_brand' => $data['product_brand'],
            'purchase_price' => $data['purchase_price'],
            'installation_price' => $data['installation_price'],
            'replacement_price' => $data['replacement_price'],
            'product_life_span' => $data['product_life_span'],
            'baseline' => $data['chkBaseline'],
            'uses_day_flag' => $data['chk_uses_day_flag'],
            'uses_day_value' => $data['uses_day_value'],
            'uses_wk_flag' => $data['chk_uses_wk_flag'],
            'uses_wk_value' => $data['uses_wk_value'],
            'hrs_day_flag' => $data['chk_hrs_day_flag'],
            'hrs_day_value' => $data['hrs_day_value'],
            'hrs_wk_flag' => $data['chk_hrs_wk_flag'],
            'hrs_wk_value' => $data['hrs_wk_value']
        );
        try {
            if ($this->_db->insert($this->_product, $details)) {
                $lastInsertId = $this->_db->lastInsertId($this->_product, 'project_id');
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/batchUpload/" . $data['hdnProductImg'])) {
                      $path_mainImg = $_SERVER['DOCUMENT_ROOT'] . "/batchUpload/" . $data['hdnProductImg'];
                      unlink($path_mainImg);
                }
                return $lastInsertId;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function editProductDetail($data) { 
    
    if($data['tier2'] == 'none'){ $tier2 = ''; }else{ $tier2 = $data['tier2']; }
    if($data['tier3'] == 'none'){ $tier3 = ''; }else{ $tier3 = $data['tier3']; }
    
    if(isset($data['tag1'])){ $tag1 = implode(',',$data['tag1']); }else{ $tag1 = ''; }
    if(isset($data['tag2'])){ $tag2 = implode(',',$data['tag2']); }else{ $tag2 = ''; }
    
        /*if($data['tag1']== 'none'){ $data['tag1'] = ''; }
        if($data['tag2']== 'none'){ $data['tag2'] = ''; }*/
        
        if(!isset($data['installation_price'])){ $data['installation_price'] = '';}
        if(!isset($data['replacement_price'])){ $data['replacement_price'] = '';}
        if(!isset($data['product_life_span'])){ $data['product_life_span'] = '';}
        
        if(isset($data['chkBaseline'])){ 
            if($data['chkBaseline']== 'on'){
                $data['chkBaseline']= 'y';
            }
        }else{ $data['chkBaseline']= 'n'; }
        
         if(isset($data['chk_uses_day_flag'])){ 
            if($data['chk_uses_day_flag']== 'on'){
                $data['chk_uses_day_flag']= 'y';
            }
        }else{ $data['chk_uses_day_flag']= 'n'; $data['uses_day_value']= '';}
        
        if(isset($data['chk_uses_wk_flag'])){ 
            if($data['chk_uses_wk_flag']== 'on'){
                $data['chk_uses_wk_flag']= 'y';
            }
        }else{ $data['chk_uses_wk_flag']= 'n'; $data['uses_wk_value']= '';}
        
         if(isset($data['chk_hrs_day_flag'])){ 
            if($data['chk_hrs_day_flag']== 'on'){
                $data['chk_hrs_day_flag']= 'y';
            }
        }else{ $data['chk_hrs_day_flag']= 'n'; $data['hrs_day_value']= '';}
        
         if(isset($data['chk_hrs_wk_flag'])){ 
            if($data['chk_hrs_wk_flag']== 'on'){
                $data['chk_hrs_wk_flag']= 'y';
            }
        }else{ $data['chk_hrs_wk_flag']= 'n'; $data['hrs_wk_value']= '';}
        
        $where['product_id =?'] = $data['hdnProductId'];
        
        $details = array(
            'tier1_id' => $data['tier1'],
            'tier2_id' => $tier2,
            'tier3_id' => $tier3,
            'tag1_id' => $tag1,
            'tag2_id' => $tag2,
            'product_name' => $data['product_name'],
            'product_img'=> $data['hdnProductImg'],
            'product_num' => $data['product_number'],
            'product_brand' => $data['product_brand'],
            'purchase_price' => $data['purchase_price'],
            'installation_price' => $data['installation_price'],
            'replacement_price' => $data['replacement_price'],
            'product_life_span' => $data['product_life_span'],
            'baseline' => $data['chkBaseline'],
            'uses_day_flag' => $data['chk_uses_day_flag'],
            'uses_day_value' => $data['uses_day_value'],
            'uses_wk_flag' => $data['chk_uses_wk_flag'],
            'uses_wk_value' => $data['uses_wk_value'],
            'hrs_day_flag' => $data['chk_hrs_day_flag'],
            'hrs_day_value' => $data['hrs_day_value'],
            'hrs_wk_flag' => $data['chk_hrs_wk_flag'],
            'hrs_wk_value' => $data['hrs_wk_value']
        );

        try {
            $this->_db->update($this->_product, $details, $where);
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/batchUpload/" . $data['hdnProductImg'])) {
                      $path_mainImg = $_SERVER['DOCUMENT_ROOT'] . "/batchUpload/" . $data['hdnProductImg'];
                      unlink($path_mainImg);
            }
            return $data['hdnProductId'];
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    
    public function fetchProductDetailByPid($productId){
        if(empty($productId)){
            return false;
        }
       $sql = $this->_db->select()
                ->from($this->_product)
                ->where('product_id = ?', $productId);
     
        try {
            $result = $this->_db->fetchRow($sql); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    
    public function fetchProductImgDetailByPid($productId){
        if(empty($productId)){
            return false;
        }
       $sql = $this->_db->select()
                ->from($this->_productImg)
                ->where('product_id = ?', $productId);
     
        try {
            $result = $this->_db->fetchAll($sql); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function fetchProductImgById($productImgId){
        if(empty($productImgId)){
            return false;
        }
       $sql = $this->_db->select()
                ->from($this->_productImg)
                ->where('product_img_id = ?', $productImgId);
     
        try {
            $result = $this->_db->fetchRow($sql); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function editProductImg($image_name, $id, $hdnImgName){
         $where['product_img_id =?'] = $id;
        $details = array(
            'product_img_name' => $image_name
        );

        try {
            $this->_db->update($this->_productImg, $details, $where);
            if (!empty($image_name)) {
                $path = $_SERVER['DOCUMENT_ROOT'] . "/batchUpload/" . $image_name;
                unlink($path);
            }
            if(!empty($hdnImgName)){
                  if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/batchUpload/main/" . $hdnImgName)) {
                      $path_mainImg = $_SERVER['DOCUMENT_ROOT'] . "/batchUpload/main/" . $hdnImgName;
                      unlink($path_mainImg);
                  }
                  if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/batchUpload/thumb/" . $hdnImgName)) {
                      $path_thumbImg = $_SERVER['DOCUMENT_ROOT'] . "/batchUpload/thumb/" . $hdnImgName;
                      unlink($path_thumbImg);
                  }
                  
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function delProductInfo($pid,$img) {
        if (empty($pid) || empty($img)) {
            return false;
        }
        $where['product_id=?'] = $pid;
        $table = $this->_product;
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/batchUpload/main/" . $img)) {
            $path_mainImg = $_SERVER['DOCUMENT_ROOT'] . "/batchUpload/main/" . $img;
            unlink($path_mainImg);
        }
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/batchUpload/thumb/" . $img)) {
            $path_thumbImg = $_SERVER['DOCUMENT_ROOT'] . "/batchUpload/thumb/" . $img;
            unlink($path_thumbImg);
        }
        try {
            if ($this->_db->delete($table, $where)) {
                return 'Delete';
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    function cleanCoding($string)
    {
        $string = addslashes($string); // Single quote
        $string = str_replace('“', '"', $string); // �
        $string = str_replace('�?', '"', $string); // �
        $string = str_replace('‘', addslashes("'"), $string); // � Single Quote
        $string = str_replace('’', addslashes("'"), $string); // � Single Quote
        $string = str_replace('–', '-', $string); // *** � *** This dash is different
        $string = str_replace('—', '-', $string); // *** � *** This dash is also different

        $string = str_replace('&ldquo;', '"', $string); // �
        $string = str_replace('&rdquo;', '"', $string); // �
        $string = str_replace('&lsquo;', addslashes("'"), $string); // �
        $string = str_replace('&rsquo;', addslashes("'"), $string); // �

        $string = str_replace('�', '"', $string); // �
        $string = str_replace('�', '"', $string); // �
        $string = str_replace('�', addslashes("'"), $string); // �
        $string = str_replace('�', addslashes("'"), $string); // �
        $string = str_replace('�', '-', $string); // �
        $string = str_replace('�', '-', $string); // �
        return $string;
    }
    public function fetchTier1IdByName($tier1Name) {
        $sql = $this->_db->select()
                ->from($this->_tier1)
                ->where('tier1_name = ?', $tier1Name);
        $result = $this->_db->fetchRow($sql);
        return $result['tier1_id'];
    }

    public function fetchTier2IdByName($tier2Name) {
        $sql = $this->_db->select()
                ->from($this->_tier2)
                ->where('tier2_name = ?', $tier2Name);
        $result = $this->_db->fetchRow($sql);
        return $result['tier2_id'];
    }
     public function fetchTag1IdByName($tag1Name) {
        $sql = $this->_db->select()
                ->from($this->_tag1)
                ->where('tag1_name = ?', $tag1Name);
        $result = $this->_db->fetchRow($sql);
        return $result['tag1_id'];
    }

    public function fetchTag2IdByName($tag2Name) {
        $sql = $this->_db->select()
                ->from($this->_tag2)
                ->where('tag2_name = ?', $tag2Name);
        $result = $this->_db->fetchRow($sql);
        return $result['tag2_id'];
    }
     Public function import($fileExtension,$userId) {  
       if (empty($fileExtension)) {
            return false;
        }
        $subAttArr = array();
        $blankArr  = array("", "");
        $dataInfo  = array();
        $idArr     = array();
       
       
        
        if (strtolower($fileExtension) == "csv") {
            $filename = $_FILES['csvfile']['tmp_name']; 
            $handle   = fopen($filename, "r");
           
            $row = 1; 
            while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
             
                for ($i = 1; $i < count($data); $i++) {
                  $data[$i] = $this->cleanCoding($data[$i]); 
                }
           
                if (($row != 1)) {   $tag1List = array();  $tag2List = array();
                    for ($i = 0; $i < count($data); $i++) {
                        if ($i == 0) { 
                            if(!empty($data[0])){ //Tier1 
                                $tier1Id = $this->fetchTier1IdByName($data[0]);
                                $data[$i] = $tier1Id;
                            }else{ 
                                $data[$i] = '';
                            }
                        } 
                        if ($i == 1) { //Tier2 
                            if(!empty($data[1])){
                                $tier2Id = $this->fetchTier2IdByName($data[1]);
                                $data[$i] = $tier2Id;
                            }else{
                                $data[$i] = '';
                            }
                        }
                        if ($i == 2) { //Tag 1
                            if(!empty($data[2])){ 
                                $tag1Arr = explode(',', $data[2]);
                                foreach ($tag1Arr as $key => $value) {
                                      $tag1Id = $this->fetchTag1IdByName($value);
                                      array_push($tag1List, $tag1Id);
                                }
                                if(!empty($tag1List)){
                                      $data[$i] = implode(',', $tag1List);  
                                }else{
                                    $data[$i] = '';
                                }
                            }else{
                                $data[$i] = '';
                            }
                         }
                        if ($i == 3) { //Tag 2
                            if(!empty($data[3])){ 
                                $tag2Arr = explode(',', $data[3]);
                                foreach ($tag2Arr as $key => $value) {
                                      $tag2Id = $this->fetchTag2IdByName($value);
                                      array_push($tag2List, $tag2Id);
                                }
                                if(!empty($tag2List)){
                                      $data[$i] = implode(',', $tag2List);  
                                }else{
                                    $data[$i] = '';
                                }
                            }else{
                                $data[$i] = '';
                            }
                        }
                        /*if ($i == 3) { //Tag 2
                            if(!empty($data[3])){
                                $tag2Id = $this->fetchTag2IdByName($data[3]);
                                $data[$i] = $tag2Id;
                            }else{
                                $data[$i] = '';
                            }
                        }*/
                    } 
                    if (!empty($data)) {
                        $dataInfo[] = $data;
                    }
                }
                $row++; 
            } 
            foreach ($dataInfo as $key => $value) { 
                $isProductExist = $this->checkProductNumExists($value[5]);  
              
                if($isProductExist == 'empty'){ 
                    $this->insertImportProductDetail($value,$userId);
                }else{ 
                    $this->updateImportProductDetail($value,$userId,$isProductExist['product_id']);
                }
                
            }
             fclose($handle);
        }
    }
    Public function insertImportProductDetail($data,$userId){ 
        if(empty($data) || empty($userId)){
            return false;
        }
        if(empty($data[2])){ $data[2] = ''; } 
        if(empty($data[3])){ $data[3] = ''; }
        
        if(empty($data[10])){ $data[10] = ''; $uses_day_flag='n';}else{$uses_day_flag='y';}
        if(empty($data[11])){ $data[11] = ''; $uses_wk_flag='n';}else{$uses_wk_flag='y';}
        if(empty($data[12])){ $data[12] = ''; $hrs_day_flag='n';}else{$hrs_day_flag='y';}
        if(empty($data[13])){ $data[13] = ''; $hrs_wk_flag='n';}else{$hrs_wk_flag='y';}
        
        
        $details = array(
        'tier1_id' => $data[0],
        'tier2_id' => $data[1],
        'tag1_id' => $data[2],
        'tag2_id' => $data[3],
        'product_name' => $data[4],
        'product_num' => $data[5],
        'product_brand' => $data[6],
        'purchase_price' => $data[7],
        'product_img' => $data[8],
        'baseline' => $data[9],
        'uses_day_flag' => $uses_day_flag,
        'uses_day_value' => $data[10],
        'uses_wk_flag' => $uses_wk_flag,
        'uses_wk_value' => $data[11],
        'hrs_day_flag' => $hrs_day_flag,
        'hrs_day_value' => $data[12],
        'hrs_wk_flag' => $hrs_wk_flag,
        'hrs_wk_value' => $data[13],    
        'user_id' => $userId
       );
   
        try {
            if ($this->_db->insert($this->_product, $details)) {
                return true;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    Public function searchProduct($param){ 
        
        if($param['tier1'] == 'none'){
         $tier1 = '';
        }else{
            $tier1 = $param['tier1'];
        }
        if($param['tier2'] == 'none'){
         $tier2 = '';
        }else{
            $tier2 = $param['tier2'];
        }
        
        if(isset($param['tag1'])){
            $tag1 = implode(",",$param['tag1']);  
        }else{
             $tag1 = '';
        }
        if(isset($param['tag2'])){
             $tag2 = implode(",",$param['tag2']); 
        }else{
             $tag2 = '';
        }
       /* if($param['tag1'] == 'none'){
         $tag1 = '';
        }else{
            $tag1 = $param['tag1'];
        }
        if($param['tag2'] == 'none'){
         $tag2 = '';
        }else{
            $tag2 = $param['tag2'];
        }*/
        
        
        
        $productName = $param['product_name'];
        $productNo   = $param['product_number'];
        $cost        = $param['purchase_price'];
        $brand       = $param['product_brand'];
        
     //   $sql = "SELECT product_id,tier1_id,tier2_id,tag1_id,tag2_id,product_name,product_img,product_num,purchase_price FROM `product` WHERE 1";
         $sql = "SELECT * FROM `product` WHERE 1";
        if (!empty($tier1)) {
            $sql .= " AND tier1_id LIKE '" . mysql_escape_string($tier1)."'";
        }
        if (!empty($tier2)) {
            $sql .= " AND tier2_id LIKE '" . mysql_escape_string($tier2)."'";
        }
        if (!empty($tag1)) {
            //$sql .= " AND tag1_id LIKE '" . mysql_escape_string($tag1)."'";
              $sql .= " AND tag1_id IN (".$tag1.")";
        }
        if (!empty($tag2)) {
           // $sql .= " AND tag2_id LIKE '" . mysql_escape_string($tag2)."'";
            $sql .= " AND tag2_id IN (".$tag2.")";
        }
        if (!empty($productName)) {
            $sql .= " AND product_name LIKE '" . mysql_escape_string($productName) . "' ";
        }
        if (!empty($productNo)) {
            $sql .= " AND product_num LIKE '" . mysql_escape_string($productNo) . "' ";
        }
        if (!empty($cost)) {
            $sql .= " AND purchase_price LIKE '" . mysql_escape_string($cost) . "' ";
        }
        if (!empty($brand)) {
            $sql .= " AND product_brand LIKE '" . mysql_escape_string($brand) . "' ";
        }
      
        $sql = $sql;  
        $result = $this->_db->fetchAll($sql);
        return $result;
    }
    
    Public function getAutoSearchList($searchData) { 
        if (empty($searchData)) {
            return false;
        }
       // $sql = " SELECT Distinct `product_name` FROM `product` WHERE product_name LIKE '%" . $searchData . "%' AND baseline='n'";
        /*$sql = " SELECT Distinct `product_name` FROM `product` WHERE product_name LIKE '%" . $searchData . "%' OR product_num LIKE '%$searchData%' OR product_brand LIKE '%$searchData%' OR purchase_price LIKE '%$searchData%' AND baseline='n'";
        $result = $this->_db->fetchAll($sql);
        return $result;*/
        
         $sql = $this->_db->select()
                ->from(array('p' => $this->_product), array('*'))
                ->joinLeft(array('t1' => $this->_tier1), 'p.tier1_id = t1.tier1_id', array('tier1_name'))
                ->joinLeft(array('t2' => $this->_tier2), 'p.tier2_id = t2.tier2_id', array('tier2_name'))
                ->joinLeft(array('t3' => $this->_tag1), 'p.tag1_id = t3.tag1_id', array('tag1_name'))
                ->joinLeft(array('t4' => $this->_tag2), 'p.tag2_id = t4.tag2_id', array('tag2_name'))
                 ->where("p.product_name LIKE '%$searchData%' or 
                          p.product_num LIKE '%$searchData%' or 
                          p.product_brand LIKE '%$searchData%' or 
                          p.purchase_price LIKE '%$searchData%' or
                          p.product_life_span LIKE '%$searchData%' or 
                          p.uses_day_value LIKE '%$searchData%' or 
                          p.uses_wk_value LIKE '%$searchData%' or 
                          p.hrs_day_value LIKE '%$searchData%' or 
                          p.hrs_wk_value LIKE '%$searchData%' or 
                          p.product_configuration LIKE '%$searchData%' or
                          p.product_details LIKE '%$searchData%' or
                          p.cost LIKE '%$searchData%' or
                          p.energy LIKE '%$searchData%' or
                          p.water LIKE '%$searchData%' or
                          p.lifecycle LIKE '%$searchData%' or
                          t1.tier1_name LIKE '%$searchData%' or
                          t2.tier2_name LIKE '%$searchData%' or
                          t3.tag1_name LIKE '%$searchData%' or
                          t4.tag2_name LIKE '%$searchData%' 
                       ") 
               ->where("p.baseline = 'n'");
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
        
        
        
    }
//     public function fetchProductInfoByName($productName) {
//         if(empty($productName)){ return false;}
//        $sql = $this->_db->select()
//                ->from($this->_product)
//                ->where('product_name = ?', $productName);
//        $result = $this->_db->fetchRow($sql);
//        return $result;
//    }

    
     /* Author: Irfan Shaikh ( Updated the code)
     * Desctiption: addTierTag($category,$data, $imgName,$tid) adds/edits the Tiers/Tags. "$category" is determines the table name supplied from front end.
     * Date Created: -
     * Date Modified: 8th April'14
     */
    
    public function fetchTierTagByID($tid, $category){
        
        $sql = "SELECT * from $category where ".$category."_id = ".$tid;
       
        try {
            $result = $this->_db->fetchAll($sql); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    
    
    /* Author: Irfan Shaik
     * Desctiption: addTierTag($category,$data, $imgName,$tid) adds/edits the Tiers/Tags. "$category" is determines the table name supplied from front end.
     * Date Created: 01/04/2014
     * Date Modified: 01/04/2014
     */
    public function addTierTag($tier1Id, $tid, $title, $category, $file){
       
         $table = $category;
         if($tier1Id != ""){
             $datatable = array(
                'tier1_id' => $tier1Id,
                $category.'_name' =>  $title,
                $category.'_image' => $file,
           );
         }
         else{
         
         $datatable = array(
                $category.'_name' =>  $title,
                $category.'_image' => $file,
           );
         }
         
        if($tid == ""){
         
                try {
                    if ($this->_db->insert($table, $datatable)) {
                        /*$lastInsertClientId = $this->_db->lastInsertId($this->_client, 'id');
                        return $lastInsertClientId;*/
                        return 'success'; 
                    }
                } catch (Zend_Db_Adapter_Exception $e) {
                    return false;
                }
        }
        else{
                
                $where[$category.'_id=?'] = $tid;
                try {
                    if ($this->_db->update($table, $datatable, $where)) {
                        /*$lastInsertClientId = $this->_db->lastInsertId($this->_client, 'id');
                        return $lastInsertClientId;*/
                        return 'success'; 
                    }
                } catch (Zend_Db_Adapter_Exception $e) {
                    return false;
                }
            
        }
    }
    
    /* Author: Irfan Shaik
     * Desctiption: addTierTag($category,$data, $imgName,$tid) adds/edits the Tiers/Tags. "$category" is determines the table name supplied from front end.
     * Date Created: 01/04/2014
     * Date Modified: 01/04/2014
     */
    public function delTierTag($category, $tid){
        
            $table = $category;
        
            $where[$category.'_id=?'] = $tid;
                try {
                    if ($this->_db->delete($table, $where)) {
                        return 'success'; 
                    }
                } catch (Zend_Db_Adapter_Exception $e) {
                    return false;
                }
    }
    public function fetchFirstProductId(){
        $sql = "SELECT product_id FROM product ORDER BY product_id ASC LIMIT 1";
        $result = $this->_db->fetchRow($sql); 
        return $result['product_id'];
    }
    
    // Vidya << //
    
    public function fetchTierTagMasterName(){
        $sql = $this->_db->select()
                ->from($this->_tier_tag_name);
        try {
            $result = $this->_db->fetchRow($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    
    public function editMasterName($data){ 
       $where['id  =?'] = '1';
       
       if(isset($data['masterTier1Name'])){ 
           $details = array(
                'tier1_name' => $data['masterTier1Name']
            );
       }else if(isset($data['masterTier2Name'])){
           $details = array(
                'tier2_name' => $data['masterTier2Name']
            );
       }else if(isset($data['masterTier3Name'])){
           $details = array(
                'tier3_name' => $data['masterTier3Name']
            );
       }else if(isset($data['masterTag1Name'])){
           $details = array(
                'tag1_name' => $data['masterTag1Name']
            );
       }else if(isset($data['masterTag2Name'])){
           $details = array(
                'tag2_name' => $data['masterTag2Name']
            );
       }else if(isset($data['masterTag3Name'])){
           $details = array(
                'tag3_name' => $data['masterTag3Name']
            );
       }
       
       try {
            if($this->_db->update($this->_tier_tag_name, $details, $where)){
                return true;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    // Vidya >> //
    
    // Product Template Import : Update <<
     public function checkProductNumExists($productnum) {
       $sql = $this->_db->select()
                ->from($this->_product)
                ->where('product_num = ?', $productnum);
     
        try {
            $result = $this->_db->fetchRow($sql); 
            if(empty($result)){
                return 'empty';
            }else{
                return $result;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
     public function updateImportProductDetail($data,$userId,$pid){ 
          if(empty($data) || empty($userId)  || empty($pid)){
            return false;
        }
        $where['product_id  =?'] = $pid;
          
        if(empty($data[2])){ $data[2] = ''; } 
        if(empty($data[3])){ $data[3] = ''; }
        
        if(empty($data[10])){ $data[10] = ''; $uses_day_flag='n';}else{$uses_day_flag='y';}
        if(empty($data[11])){ $data[11] = ''; $uses_wk_flag='n';}else{$uses_wk_flag='y';}
        if(empty($data[12])){ $data[12] = ''; $hrs_day_flag='n';}else{$hrs_day_flag='y';}
        if(empty($data[13])){ $data[13] = ''; $hrs_wk_flag='n';}else{$hrs_wk_flag='y';}
        
        $details = array(
            'tier1_id' => $data[0],
            'tier2_id' => $data[1],
            'tag1_id' => $data[2],
            'tag2_id' => $data[3],
            'product_name' => $data[4],
            'product_num' => $data[5],
            'product_brand' => $data[6],
            'purchase_price' => $data[7],
            'product_img' => $data[8],
            'baseline' => $data[9],
            'uses_day_flag' => $uses_day_flag,
            'uses_day_value' => $data[10],
            'uses_wk_flag' => $uses_wk_flag,
            'uses_wk_value' => $data[11],
            'hrs_day_flag' => $hrs_day_flag,
            'hrs_day_value' => $data[12],
            'hrs_wk_flag' => $hrs_wk_flag,
            'hrs_wk_value' => $data[13],    
            'user_id' => $userId
       );
        try {
            if ($this->_db->update($this->_product, $details, $where)) {
                return true;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
     }
    // Product Template Import : Update >>
     
     //Nested tier <<
     public function fetchTierRecord($table,$wh,$id){
         if(empty($table) || empty($wh)  || empty($id)){
            return false;
        }
        $sql = $this->_db->select()
                ->from($table)
                ->where($wh.' = ?', $id); 
        try {
            $result = $this->_db->fetchAll($sql); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
     }
      public function fetchTier3inList($tier2inList) {
        $sql = "SELECT * FROM `tier3` WHERE `tier2_id` IN ($tier2inList)";
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    
    public function fetchTier2IdList() {
        $sql = $this->_db->select()
                ->from(array('t2' => $this->_tier2), array('*'))
                ->joinLeft(array('t1' => $this->_tier1), 't2.tier1_id = t1.tier1_id', array('tier1_name'));
        try {
            $result = $this->_db->fetchAll($sql); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function fetchTier3IdList() {
        $sql = $this->_db->select()
                ->from(array('t3' => $this->_tier3), array('*'))
                ->joinLeft(array('t2' => $this->_tier2), 't3.tier2_id = t2.tier2_id', array('tier2_name'));
        try {
            $result = $this->_db->fetchAll($sql); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    //Nested tier >>
}

?>
