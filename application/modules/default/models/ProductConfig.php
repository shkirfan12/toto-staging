<?php

class Default_Model_ProductConfig extends Zend_Db_Table_Abstract {

    protected $_project = 'project';
    protected $_product = 'product';
    protected $_unitValue = 'unit_value';
    protected $_subatt = 'sub_attributes';
 
    public function fetchProjectInfoById($projectId) {
        if (empty($projectId)) {
            return false;
        }
        $sql = $this->_db->select()
                ->from($this->_project)
                ->where('project_id = ' . $projectId);
        try {
            $result = $this->_db->fetchRow($sql); //echo "1-<pre>"; print_R($result);//..
            
            if(!empty($result['product_id'])){
                $product_id = substr($result['product_id'], 0, -1);
            
                //$sql = "SELECT * FROM product WHERE product_id IN ($product_id)";
                
                /* $sql = "SELECT s.*, `a`.`baseline_id`,`a`.`calcmod_id`,`a`.`uv_id` FROM product as s
                        LEFT JOIN mastergrid as a ON s.product_id=a.product_id
                        WHERE s.product_id IN ($product_id)";*/
                 
                $sql = "SELECT s.*, `a`.`baseline_id`,`a`.`calcmod_id`,`a`.`uv_id`,`u`.`subatt_id`,`u`.`unit`,`u`.`value` FROM product as s
                  LEFT JOIN unit_value as u ON s.product_id=u.product_id      
                  LEFT JOIN mastergrid as a ON u.product_id=a.product_id
                  WHERE s.product_id IN ($product_id) ORDER BY FIELD(s.product_id, $product_id)";
                 
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
                        WHERE 
                        -- s.calci_subatt ='y' AND
                        s.subatt_id IN ($u_subAttId) GROUP BY a.attribute_id ORDER BY a.rank ASC;";
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
    public function projInfoFetch($projectId) { 
        if (empty($projectId)) {
            return false;
        }
        $sql = $this->_db->select()
                ->from($this->_project)
                ->where('project_id = ' . $projectId);
        try {
            $result = $this->_db->fetchRow($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    
    public function deleteProductId4rmProject($productId,$projectId){ 
        if(empty($productId) || empty($projectId) ){
            return false;
        }

        /* Del Qty-UsesWK << */ 
        $qtyUsesInfo = $this->fetchQtyInfo($projectId,$productId);
        if(!empty($qtyUsesInfo)){
            $sql1 = "DELETE FROM qty_useswk WHERE project_id = '".$projectId."' AND product_id= '".$productId."'";
            $result1 = $this->_db->query($sql1);
        }
        /* Del Qty-UsesWK >> */
        
        $projInfo = $this->projInfoFetch($projectId); 
        $trimmed = str_replace($productId.',', '', $projInfo['product_id']);
        $sql = "UPDATE project SET product_id='$trimmed' WHERE project_id='$projectId'";
        $result = $this->_db->query($sql);
        return 'success';
    }
    
    //fetch Qty Info 
    public function fetchQtyInfo($projectId,$productId){
      
        if(!empty($projectId) || !empty($productId)){
             $sql = "SELECT * FROM qty_useswk WHERE product_id IN ($productId) AND project_id='".$projectId."'";
             $result = $this->_db->fetchAll($sql); 
             return $result;
        }else{
            return false;
        }
        
    }
    
    //get basline updated_purchase price
    public function getBaseUpdatedPrice($projectId,$productId){
        
        $sqlBasePrice = "SELECT u.product_id, u.price,  GROUP_CONCAT(s.short_code order by s.subatt_id) sub_name ,u.subatt_id, u.value FROM 
                    product_baseline u INNER JOIN sub_attributes s
                    ON FIND_IN_SET(s.subatt_id,u.subatt_id) WHERE u.product_id IN ($productId) AND project_id=$projectId group by u.product_id";
        
        $result = $this->_db->fetchAll($sqlBasePrice);
        return $result;
    }
    
    //Baseline popup price : update by ajax
    public function baselinePopupPrice($productId, $price) {
        $where['product_id=?'] = $productId;
        $details = array(
            'purchase_price' => $price
        );
        try {
            
            $this->_db->update($this->_product, $details, $where);
            return true;
            
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    
    public function getHumanPlanetCount(){
       $sqlHuman = "SELECT COUNT(S.subatt_name) humanCount FROM sub_attributes S 
                         JOIN attributes A ON S.attribute_id = A.attribute_id
                         WHERE A.attribute_name = 'Human Health'";
       
       $sqlPlanet = "SELECT COUNT(S.subatt_name) planetCount FROM sub_attributes S 
                          JOIN attributes A ON S.attribute_id = A.attribute_id
                          WHERE A.attribute_name = 'Planet Health'";
             try {
                    $hResult = $this->_db->fetchRow($sqlHuman);
                    $pResult = $this->_db->fetchRow($sqlPlanet);
                    //print_r($hResult);die;
                    $humanplanetCount[] = $hResult['humanCount'];
                    $humanplanetCount[] = $pResult['planetCount'];
                    
                    return $humanplanetCount;
                    
            } catch (Zend_Db_Adapter_Exception $e){
                    return false;
        }
    }
    
    public function getSubattValues($productId){
        
        if(empty($productId)){
            return false;
        }
       
        $prodSql = "SELECT u.product_id,GROUP_CONCAT(s.short_code order by s.subatt_id) sub_name ,u.subatt_id, u.value FROM 
                    unit_value u INNER JOIN sub_attributes s
                    ON FIND_IN_SET(s.subatt_id,u.subatt_id) WHERE u.product_id IN ($productId) group by u.product_id";
        
        $baseProdSql = "SELECT u.product_id,m.baseline_id from unit_value u join mastergrid m on 
                        m.product_id = u.product_id
                        where u.product_id IN ($productId) group by  u.product_id";
        
        
      
        try {
            $prodResult = $this->_db->fetchAll($prodSql);
            $baseProducts = $this->_db->fetchall($baseProdSql);
            $arrBaseResult =  array();
            $arrBasePriceResult = array();
          $i=0;
           foreach ($baseProducts as $b){
                $bid = $b['baseline_id'];
                $baseSql ="SELECT u.product_id,GROUP_CONCAT(s.short_code ORDER BY s.subatt_id) sub_name, u.value 
                            FROM  unit_value u
                            INNER JOIN sub_attributes s ON
                            FIND_IN_SET(s.subatt_id,u.subatt_id)
                            WHERE  u.product_id IN($bid) group by u.product_id";
                
                 $basePriceSql ="SELECT purchase_price from product where product_id IN($bid)";
                
                $baseResult = $this->_db->fetchall($baseSql);
                $baseProdPrice =  $this->_db->fetchAll($basePriceSql);
                
                array_push($arrBasePriceResult,$baseProdPrice);
                array_push($arrBaseResult,$baseResult);
           }
      
           //print_r($arrBaseResult);
              
               $combinedResult[0] = $prodResult;
               $combinedResult[1] = $arrBaseResult;
               $combinedResult[2] = $arrBasePriceResult;
            
                return $combinedResult;
           
        } 
        
         catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    

}

?>
