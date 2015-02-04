<?php

class Product_Model_Product extends Zend_Db_Table_Abstract {

    
    protected $_tag1 = 'tag1';
    protected $_tag2 = 'tag2';
    protected $_tier1 = 'tier1';
    protected $_tier2 = 'tier2';
    protected $_product = 'product';
    protected $_collection = 'collection';
    protected $_uValue = 'unit_value';
    protected $_qtyUseswk = 'qty_useswk';
    protected $_productBaseline = 'product_baseline';
    
    public function fetchProductInfoByNumber($productNumber) { 
         if(empty($productNumber)){ return false;}
        $sql = $this->_db->select()
                ->from($this->_product)
                ->where('product_num = ?', $productNumber);
        $result = $this->_db->fetchRow($sql);
        return $result;
    }
    public function fetchProductInfoByName($productName) { 
         if(empty($productName)){ return false;}
        $sql = $this->_db->select()
                ->from($this->_product)
                ->where('product_name = ?', $productName);
        $result = $this->_db->fetchRow($sql);
        return $result;
    }
    public function fetchProductInfoById($productId) {
         if(empty($productId)){ return false;}
        $sql = $this->_db->select()
                ->from($this->_product)
                ->where('product_id = ?', $productId);
        $result = $this->_db->fetchRow($sql);
        return $result;
    }
     public function fetchProductInfoByTier1Id($tier1Id) {
         if(empty($tier1Id)){ return false;}
       
        /* $sql = $this->_db->select()
                ->from($this->_product)
                ->where('tier1_id = ?', $tier1Id)
                ->where('baseline = ?', 'n');
         $result = $this->_db->fetchAll($sql);  */
          $sql = $this->_db->select()
                ->from(array('p' => $this->_product), array('*'))
                ->joinLeft(array('u' => $this->_uValue), 'p.product_id = u.product_id', array('subatt_id','value','unit'))
                ->where('p.tier1_id = ?', $tier1Id)
                ->where('p.baseline = ?', 'n');
         
         
        $result = $this->_db->fetchAll($sql);  
//        print_r("<pre>");
//            print_r($result);
//            print_r("</pre>");
            
         foreach ($result as $key => $value) {
             if(!empty($value['subatt_id'])){
                  $u_subAttId = $value['subatt_id']; 
                  //$uSubAttId  = explode(',',  $u_subAttId);
                 // $value['subatt_id'] = $uSubAttId;
                 // $result[$key] = $value;
                  $s1 = "SELECT s.*, a.* FROM sub_attributes as s
                        LEFT JOIN attributes as a
                        ON s.attribute_id=a.attribute_id
                        WHERE 
                        s.subatt_id IN ($u_subAttId) AND s.display='y' GROUP BY a.attribute_id ORDER BY a.rank ASC;";
//                  print_r("<pre>");
//            print_r($s1);
//            print_r("</pre>");
            
              //    echo $s1;
                 $r1 = $this->_db->fetchAll($s1); 
                 $value['subatt_id'] = $r1;
                 $result[$key] = $value;
                   
             }
         }
//         die;
//          print_r("<pre>");
//            print_r($result);
//            print_r("</pre>");
//            die;
         
      
        return $result;
    }
    
    public function fetchCompareIds($productId) {
        if(empty($productId)){
            return false;
        }
        /*$sql = "SELECT * FROM product WHERE product_id IN ($productId)";
        $result = $this->_db->fetchAll($sql); 
        return $result;
        */
        $sql = "SELECT * FROM product WHERE product_id IN ($productId)";      $result = $this->_db->fetchAll($sql); 
        $sql1 = "SELECT * FROM unit_value WHERE product_id IN ($productId)";  $result1 = $this->_db->fetchAll($sql1); 
        
       // echo "<pre>"; echo "result";print_R($result); echo "result1";print_R($result1);  die;
        
        if(!empty($result1)){ //unit value table
            foreach ($result as $key => $value) { //product table
                foreach ($result1 as $k1 => $v1) {
                    if($value['product_id'] == $v1['product_id']){
                        $value['subatt_id'] = $v1['subatt_id'];
                       // $value['unit'] = $v1['unit'];
                        $value['value'] = $v1['value'];
                        $result[$key] = $value;
                    }
                }
            }
        }
       // echo "<pre>1234result";print_R($result);  die;
        
        foreach ($result as $k => $v) {
            if(!empty($v['subatt_id'])){
                
                $arrRes1 = array(); $arrRes2 = array(); $arrRes3 = array(); $pids = array(); $newArr = array();
                $arrRes1 [] = $v;  // arr1
               
                
                $u_subAttId = $v['subatt_id'];  $uSubAttId  = explode(',',  $u_subAttId); 
                $u_value    = $v['value'];      $uValue = explode(',', $u_value);   
               // $unit       = $v['unit'];       $unit   = explode(',', $unit); 
                
              //  echo "<pre>";print_R($uSubAttId);print_R($uValue);  $test = array_combine($uSubAttId, $uValue); print_R($test); die;
                foreach ($uSubAttId as $k1 => $v1) {
                  foreach ($uValue as $k2 => $v2) {
                     
                          if( ($k1 == $k2)){
                              $uSubAttId[$k1] = array('subatt'=>$v1,'value'=>$v2);
                          }
                     
                    }
                }
              // echo "<pre>u_subAttId";print_R($uSubAttId);  die;
                
                
                $s1 = "SELECT s.*, a.* FROM sub_attributes as s
                        LEFT JOIN attributes as a
                        ON s.attribute_id=a.attribute_id
                        WHERE s.calci_subatt ='y'
                        AND s.subatt_id IN ($u_subAttId) ORDER BY a.rank ASC,s.rank ASC;"; 
                $r1 = $this->_db->fetchAll($s1); // echo "r1<pre>"; print_R($r1); echo "u_subAttId<pre>"; print_R($uSubAttId); die;//echo "u_value<pre>"; print_R($uValue); die;
              // echo "r1<pre>"; print_R($r1); die;
                 foreach ($r1 as $key => $value) {
                     foreach ($uSubAttId as $ku1 => $vu1) {
                          if($value['subatt_id'] == $vu1['subatt']){
                                $value['value'] = $vu1['value'];
                               // $value['unit'] = $vu1['unit'];
                                $r1[$key] = $value;
                          }
                     }
                 }
             
                 //  echo "r2<pre>"; print_R($r1);die;
              /*  foreach ($r1 as $key => $value) {
                    if(array_key_exists($key, $uValue)){
                        $value['value'] = $uValue[$key];
                        $r1[$key] = $value;
                    }
                    if(array_key_exists($key, $unit)){
                        $value['unit'] = $unit[$key];
                        $r1[$key] = $value;
                    }
                }*/
               
                $arrRes2[] = $r1;  // arr2
              
                foreach ($r1 as $key => $value) { 
                    $pids[] = array($value['attribute_id'],$value['attribute_name'],$value['attribute_image']);
                }
                
                $unique = array_map(
                        'unserialize', array_unique(
                                array_map(
                                        'serialize', $pids
                                )
                        )
                );
                $unique = array_values($unique);  
                $arrRes3[] = $unique;// arr3
                 
                $resultt = array_merge($arrRes1, $arrRes2,$arrRes3);
                $result[$k] = $resultt;
            }
        } //echo "<pre>"; print_r($result); die;
       return $result;
    }
    
    Public function advanceSearch($param) {
        if (empty($param)) {
            return false;
        }
        //$sql = "SELECT * FROM `product` WHERE 1";
      //  $sql = "SELECT * FROM `product` WHERE baseline='n'";
         $sql = $this->_db->select()
                ->from(array('p' => $this->_product), array('*'))
                ->joinLeft(array('u' => $this->_uValue), 'p.product_id = u.product_id', array('subatt_id','value','unit'))
                ->where('p.baseline = ?', 'n');
        
        if (isset($param['tier1'])) {
            $t1 = implode(",", $param['tier1']);
            $sql .= " AND tier1_id IN ($t1)";
        }
        if (isset($param['tier2'])) {
            $t2 = implode(",", $param['tier2']);
            $sql .= " AND tier2_id IN ($t2)";
        }
        if (isset($param['tier3'])) {
            $t5 = implode(",", $param['tier3']);
            $sql .= " AND tier3_id IN ($t5)";
        }
        if (isset($param['tag1'])) {
            $t3 = implode(",", $param['tag1']);
            //$sql .= " AND tag1_id IN ($t3)";
            $sql .= " AND tag1_id LIKE '%$t3%'";
        }
        if (isset($param['tag2'])) {
            $t4 = implode(",", $param['tag2']);
            //$sql .= " AND tag2_id IN ($t4)";
            $sql .= " AND tag2_id LIKE '%$t4%'";
        }

        $sql = $sql; 
        $result = $this->_db->fetchAll($sql);
        
        foreach ($result as $key => $value) {
             if(!empty($value['subatt_id'])){
                  $u_subAttId = $value['subatt_id']; 
                  //$uSubAttId  = explode(',',  $u_subAttId);
                 // $value['subatt_id'] = $uSubAttId;
                 // $result[$key] = $value;
                  $s1 = "SELECT s.*, a.* FROM sub_attributes as s
                        LEFT JOIN attributes as a
                        ON s.attribute_id=a.attribute_id
                        WHERE s.subatt_id IN ($u_subAttId) GROUP BY a.attribute_id ";
                 $r1 = $this->_db->fetchAll($s1); 
                 $value['subatt_id'] = $r1;
                 $result[$key] = $value;
                   
             }
         }
     
        
        if (!empty($result)) {
            return $result;
        } else {
            return 'empty';
        }
    }
    public function fetchProductByCollectionId($collectionId) {
        if (empty($collectionId)) {
            return false;
        } 
        $sql = $this->_db->select()
                ->from($this->_collection)
                ->where('collection_id = ' . $collectionId);
        try {
            $result = $this->_db->fetchRow($sql); 
                if(!empty($result['product_id'])){
                   /* $product_id = substr($result['product_id'], 0, -1); 
                    $sql = "SELECT * FROM product WHERE product_id IN ($product_id)";
                    $result = $this->_db->fetchAll($sql); 
                    return $result;*/
                         $product_id = substr($result['product_id'], 0, -1); 
            
                    //$sql = "SELECT * FROM product WHERE product_id IN ($product_id)";

                    /* $sql = "SELECT s.*, `a`.`baseline_id`,`a`.`calcmod_id`,`a`.`uv_id` FROM product as s
                            LEFT JOIN mastergrid as a ON s.product_id=a.product_id
                            WHERE s.product_id IN ($product_id)";*/

                    $sql = "SELECT s.*, `a`.`baseline_id`,`a`.`calcmod_id`,`a`.`uv_id`,`u`.`subatt_id`,`u`.`unit`,`u`.`value` FROM product as s
                            LEFT JOIN mastergrid as a ON s.product_id=a.product_id
                            LEFT JOIN unit_value as u ON a.product_id=u.product_id
                            WHERE s.product_id IN ($product_id)"; 

                    $result = $this->_db->fetchAll($sql); 

                    foreach ($result as $key => $value) {
                        if (!empty($value['subatt_id'])) {
                            $u_subAttId = $value['subatt_id'];
                            //$uSubAttId  = explode(',',  $u_subAttId);
                            // $value['subatt_id'] = $uSubAttId;
                            // $result[$key] = $value;
                            $s1 = "SELECT s.*, a.* FROM sub_attributes as s
                            LEFT JOIN attributes as a
                            ON s.attribute_id=a.attribute_id
                            WHERE s.calci_subatt ='y'
                            AND s.subatt_id IN ($u_subAttId) GROUP BY a.attribute_id ORDER BY a.rank ASC;";
                            $r1 = $this->_db->fetchAll($s1);
                            $value['subatt_id'] = $r1;
                            $result[$key] = $value;
                        }
                    }
                    return $result;
                }else{
                   $result = ''; 
                   return $result;
                }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    // Product Baseline table << 
     public function fetchProductBaselineInfo($projectId,$productId,$baselineId) {
         if( empty($projectId) || empty($productId) || empty($baselineId) ){ return false;}
         
        $sql = $this->_db->select()
                ->from($this->_productBaseline)
                ->where('project_id = ?', $projectId)
                ->where('product_id = ?', $productId)
                ->where('baseline_id = ?', $baselineId);
        
        $result = $this->_db->fetchRow($sql);
        if(empty($result)){
            return 'empty';
        }else{
            return $result;
        }
    }
    public function enterProductBaselineInfo($hdnProjectId, $hdnProductId, $hdnBaselineId, $subatt_id, $value,$price) {
        // echo '1'.$hdnProjectId.'--'.$hdnProductId.'--'.$hdnBaselineId.'--'.$subatt_id.'--'.$unit; print_R($value);
        $comma_seprator = implode(',', $value);

        $details = array(
            'project_id' => $hdnProjectId,
            'product_id' => $hdnProductId,
            'baseline_id' => $hdnBaselineId,
            'subatt_id' => $subatt_id,
            'value' => $comma_seprator,
            'price' => $price
        );
        try {
            if ($this->_db->insert($this->_productBaseline, $details)) {
                return true;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    //Baseline popup price : inline edit with save
     public function updateSubAttValue($hdnProjectId,$hdnProductId,$hdnBaselineId,$value,$price){
        //echo '2'.$hdnProjectId.'--'.$hdnProductId.'--'.$hdnBaselineId; print_r($value);die;
       
        $comma_seprator = implode(',', $value); 
        
        $where['project_id=?'] = $hdnProjectId;
        $where['product_id=?'] = $hdnProductId;
        $where['baseline_id=?'] = $hdnBaselineId;
        
        $details = array(
            'value' => $comma_seprator,
            'price' => $price
        );
        try {
            $this->_db->update($this->_productBaseline, $details, $where);
            return true;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
     public function fetchProductBaselineInfoByIdWithDetail($projectId,$productId,$baselineId) {
        if( empty($projectId) || empty($productId) || empty($baselineId) ){ return false;}

        $sql = $this->_db->select()
                ->from(array('p' => $this->_product), array('*'))
                ->joinLeft(array('u' => $this->_productBaseline), 'p.product_id = u.baseline_id', array('subatt_id','value'))
                ->where('u.project_id = ?', $projectId)
                ->where('u.product_id = ?', $productId)
                ->where('u.baseline_id = ?', $baselineId);;

        try {
            $result = $this->_db->fetchRow($sql);   
            
            if(!empty($result['subatt_id'])){
                $arrRes1 = array(); $arrRes2 = array(); $arrRes3 = array(); $pids = array();
                $arrRes1 [] = $result;  // arr1
                
                $u_subAttId = $result['subatt_id'];
                $u_value = $result['value'];    $uValue = explode(',', $u_value); 
                $unit    = $result['unit'];     $unit = explode(',', $unit); 
                
                $sql1 = "SELECT s.*, a.* FROM sub_attributes as s
                        LEFT JOIN attributes as a
                        ON s.attribute_id=a.attribute_id
                        WHERE s.subatt_id IN ($u_subAttId)";
                $result1 = $this->_db->fetchAll($sql1); 
                
                foreach ($result1 as $key => $value) {
                    if(array_key_exists($key, $uValue)){
                        $value['value'] = $uValue[$key];
                        $result1[$key] = $value;
                    }
                    if(array_key_exists($key, $unit)){
                        $value['unit'] = $unit[$key];
                        $result1[$key] = $value;
                    }
                }
               
                $arrRes2[] = $result1;  // arr2
                
                foreach ($result1 as $key => $value) { 
                    $pids[] = array($value['attribute_id'],$value['attribute_name'],$value['attribute_image']);
                }
                $unique = array_map(
                        'unserialize', array_unique(
                                array_map(
                                        'serialize', $pids
                                )
                        )
                );
                $unique = array_values($unique);  
                $arrRes3[] = $unique;// arr3
                 
                $result = array_merge($arrRes1, $arrRes2,$arrRes3);
            }
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
     // Product Baseline table >>
    public function fetchProductInfoByIdWithDetail($productId) {
        if (empty($productId)) {
            return false;
        }

        $sql = $this->_db->select()
                ->from(array('p' => $this->_product), array('*'))
                ->joinLeft(array('u' => $this->_uValue), 'p.product_id = u.product_id', array('subatt_id','value'))
                ->where('p.product_id = ?', $productId);
        
        try {
            $result = $this->_db->fetchRow($sql);   
            
            if(!empty($result['subatt_id'])){
                $arrRes1 = array(); $arrRes2 = array(); $arrRes3 = array(); $pids = array();
                $arrRes1 [] = $result;  // arr1
                
                $u_subAttId = $result['subatt_id'];
                $u_value = $result['value'];    $uValue = explode(',', $u_value); 
               // $unit    = $result['unit'];     $unit = explode(',', $unit); 
             
                $sql1 = "SELECT s.*, a.* FROM sub_attributes as s
                        LEFT JOIN attributes as a
                        ON s.attribute_id=a.attribute_id
                        WHERE s.subatt_id IN ($u_subAttId) AND s.display='y' ORDER BY s.subatt_id ASC;"; 
                       // AND s.subatt_id IN ($u_subAttId) ORDER BY a.rank ASC,s.rank ASC;"; 
                $result1 = $this->_db->fetchAll($sql1);  
                
                foreach ($result1 as $key => $value) {
                    if(array_key_exists($key, $uValue)){
                        $value['value'] = $uValue[$key];
                        $result1[$key] = $value;
                    }
                    /*if(array_key_exists($key, $unit)){
                        $value['unit'] = $unit[$key];
                        $result1[$key] = $value;
                    }*/
                }
               
                $arrRes2[] = $result1;  // arr2
                
                foreach ($result1 as $key => $value) { 
                    $pids[] = array($value['attribute_id'],$value['attribute_name'],$value['attribute_image']);
                }
                $unique = array_map(
                        'unserialize', array_unique(
                                array_map(
                                        'serialize', $pids
                                )
                        )
                );
                $unique = array_values($unique);  
                $arrRes3[] = $unique;// arr3
                 
                $result = array_merge($arrRes1, $arrRes2,$arrRes3);
            }
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    
    
    //Update product Qty on keyup
    public function qtyTranscation($projectId,$productId,$qty) {
      
        if(!empty($projectId) || !empty($productId)){
            if(empty($qty)){
                $qty = '';
            }
            $sql = $this->_db->select()
                ->from($this->_qtyUseswk)
                ->where('project_id = ?', $projectId)
                ->where('product_id = ?', $productId);
            $result = $this->_db->fetchRow($sql); 
            
            if(!empty($result)){ // Update Qty
               $where['qty_usesWK_id=?'] = $result['qty_usesWK_id'];
               
               $details = array(
                    'qty_value' => $qty
               );
               try {
                    $this->_db->update($this->_qtyUseswk, $details, $where);
                    return true;
               } catch (Zend_Db_Adapter_Exception $e) {
                    return false;
               }
            }else{ // Insert QTY
                $details = array(
                    'project_id' => $projectId,
                    'product_id' => $productId,
                    'qty_value'  => $qty
               );
                try {
                    if ($this->_db->insert($this->_qtyUseswk, $details)) {
                    return true;    
                    }
                } catch (Zend_Db_Adapter_Exception $e) {
                    return false;
                }
            }
        }
    }
    
     //Update product usesWK on keyup
    public function usesWKTranscation($projectId,$productId,$usesWK) { 
      
        if(!empty($projectId) || !empty($productId)){
            if(empty($usesWK)){
                $usesWK = '';
            }
            $sql = $this->_db->select()
                ->from($this->_qtyUseswk)
                ->where('project_id = ?', $projectId)
                ->where('product_id = ?', $productId);
            $result = $this->_db->fetchRow($sql); 
            
            if(!empty($result)){ // Update Qty
               $where['qty_usesWK_id=?'] = $result['qty_usesWK_id'];
               
               $details = array(
                    'uses_wk_value' => $usesWK
               );
               try {
                    $this->_db->update($this->_qtyUseswk, $details, $where);
                    return true;
               } catch (Zend_Db_Adapter_Exception $e) {
                    return false;
               }
            }else{ // Insert QTY
                $details = array(
                    'project_id' => $projectId,
                    'product_id' => $productId,
                    'uses_wk_value'  => $usesWK
               );
                try {
                    if ($this->_db->insert($this->_qtyUseswk, $details)) {
                    return true;    
                    }
                } catch (Zend_Db_Adapter_Exception $e) {
                    return false;
                }
            }
        }
    }
    
    
    //Update product usesWK on keyup
    public function priceTranscation($projectId,$productId,$price) { 
      
        if(!empty($projectId) || !empty($productId)){
            if(empty($price)){
                $price = '';
            }
            $sql = $this->_db->select()
                ->from($this->_qtyUseswk)
                ->where('project_id = ?', $projectId)
                ->where('product_id = ?', $productId);
            $result = $this->_db->fetchRow($sql); 
            
            if(!empty($result)){ // Update Qty
               $where['qty_usesWK_id=?'] = $result['qty_usesWK_id'];
               
               $details = array(
                    'price' => $price
               );
               try {
                    $this->_db->update($this->_qtyUseswk, $details, $where);
                    return true;
               } catch (Zend_Db_Adapter_Exception $e) {
                    return false;
               }
            }else{ // Insert QTY
                $details = array(
                    'project_id' => $projectId,
                    'product_id' => $productId,
                    'price'  => $price
               );
                try {
                    if ($this->_db->insert($this->_qtyUseswk, $details)) {
                    return true;    
                    }
                } catch (Zend_Db_Adapter_Exception $e) {
                    return false;
                }
            }
        }
    }
}

?>
