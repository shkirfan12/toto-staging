<?php

class Admin_Model_Mastergrid extends Zend_Db_Table_Abstract {

    protected $_mastergrid = 'mastergrid';
    protected $_unitvalue = 'unit_value';
    protected $_calc = "calc_mod";
    protected $_attributes = 'attributes';
    protected $_subAttributes = 'sub_attributes';
    protected $_importExcelsheetRecord = 'import_excelsheet_record';

    public function getUnitsValues() {

        $sql = $this->_db->select()
                ->from($this->_unitvalue);
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    public function getCalc() {

        $sql = $this->_db->select()
                ->from($this->_calc);

        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    public function getSelectedCalcBase($prodId) {

        $sql = "SELECT calcmod_id, baseline_id FROM mastergrid WHERE product_id=$prodId";
        try {
            $result = $this->_db->fetchRow($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    public function addupdateMasterGrid($mgData, $calcIds, $baselineIds) {

        // 1)Makes an entry to UnitValue table
//      
//      print_r($calcIds);
//      print_r($baselineIds); die;
        $prodId = $mgData['prodId'];
        $uvTable = $this->_unitvalue;
        $mgTable = $this->_mastergrid;
        $lastInsertId = "";

        $uvSql = "SELECT * FROM $uvTable where product_id = $prodId";
        $mgSql = "SELECT * FROM $mgTable where product_id = $prodId";

        try {
            // $result values determins whether it's new record or existing one. if New would INSERT else UPDATE.
            $uvResult = $this->_db->fetchAll($uvSql);
            $mgResult = $this->_db->fetchAll($mgSql);
        } catch (Zend_Db_Adapter_Exception $e) {
            throw $e;
        }

//            echo $mgData['subattIds']; die;

        $unitValue_dataTable = array('product_id' => $prodId,
            'subatt_id' => $mgData['subattIds'],
            'unit' => NULL,
            'value' => $mgData['values'],
            'created_date' => Zend_Date::now(),
            'modified_date' => Zend_Date::now(),
            'active' => 'y');
        // print_r($unitValue_dataTable);die;
        try {

            if (count($uvResult) != 0) {
                $where['product_id =?'] = $prodId;


                $this->_db->update($this->_unitvalue, $unitValue_dataTable, $where);
                $lastInsertId = $this->_db->fetchCol('SELECT id FROM unit_value WHERE product_id=' . $prodId);
            } else {
                $this->_db->insert($this->_unitvalue, $unitValue_dataTable);
                $lastInsertId = $this->_db->lastInsertId($this->_unitvalue, 'id');
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            throw $e;
        }


        // 2)Makes an entry to Mastergrid table                           


        $calcId = str_replace("calc_", "", $calcIds);
        $baseId = str_replace("base_", "", $baselineIds);


        $arrCalc = implode(",", $calcId);
        //print_r($arrCalc);

        $mastergrid_dataTable = array('product_id' => $prodId,
            'uv_id' => $lastInsertId[0],
            'calcmod_id' => $arrCalc,
            'baseline_id' => $baseId[0],
            'created_date' => date('Y-m-d'),
            'modified_date' => date('Y-m-d'),
            'active' => 'y');

        try {
            if (count($mgResult) != 0) {
                $where['product_id =?'] = $mgData['prodId'];
                $rOutput = $this->_db->update($this->_mastergrid, $mastergrid_dataTable, $where);
                return $rOutput;
            } else {
                $rOutput = $this->_db->insert($this->_mastergrid, $mastergrid_dataTable);
                return $rOutput;
            }
            //$lastInsertId = $this->_db->lastInsertId($this->_unitvalue, 'id');
        } catch (Zend_Db_Adapter_Exception $e) {
            throw $e;
        }
    }

    // Import -- Gurjyot Start

    public function insertimportdata($details, $table) {
     
        if ($table == 'product') {
            $idname = 'product_id';
        } else if ($table == 'calc_mod') {
            $idname = 'id';
        } else if ($table == 'unit_value') {
            $idname = 'id';
        } else if ($table == 'mastergrid') {
            $idname = 'id';
        }
        try {
            // $id = $this->getDbTable->newId($table, $idname);
            if ($this->_db->insert($table, $details)) {
                return $this->_db->lastInsertId($table, $idname);
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    public function updateimportdata($table, $details, $where) {
        try {
         $sql = "UPDATE $table SET $details WHERE product_id='$where'";
         $result = $this->_db->query($sql);
        if($table != 'unit_value')
                {
                    $sql = "SELECT product_id FROM $table ORDER BY modified_date desc LIMIT 1 ";
                    try {
                        $result = $this->getDefaultAdapter()->fetchOne($sql);
                    } catch (Zend_Db_Adapter_Exception $e) {
                        print_r($e);
                    }
                     return $result;
                }else
                {
                    $sql = "SELECT id FROM $table ORDER BY modified_date desc LIMIT 1 ";
                    try {
                        $result = $this->getDefaultAdapter()->fetchOne($sql);
                    } catch (Zend_Db_Adapter_Exception $e) {
                        print_r($e);
                    }
                     return $result;
                }
               
            // $id = $this->getDbTable->newId($table, $idname);
//            if ($this->_db->update($table, $details, $where)) {
//                if($table != 'product' && $table != 'unit_value')
//                {
//                    $sql = "SELECT product_id FROM $table ORDER BY modified_date desc LIMIT 1 ";
//                    try {
//                        $result = $this->getDefaultAdapter()->fetchOne($sql);
//                    } catch (Zend_Db_Adapter_Exception $e) {
//                        print_r($e);
//                    }
//                }if( $table == 'unit_value')
//                {
//                    $sql = "SELECT id FROM $table ORDER BY modified_date desc LIMIT 1 ";
//                    try {
//                        $result = $this->getDefaultAdapter()->fetchOne($sql);
//                    } catch (Zend_Db_Adapter_Exception $e) {
//                        print_r($e);
//                    }
//                }
//                return $result;
//            }else{echo '<pre>';print_R($details);print_R($where);
//                echo 'failed';
//            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    public function checkproductexists($productnum) {
      //  $sql = 'SELECT product_id FROM product WHERE product_name = "' . $baselinename . '" AND product_num = "' . $productnum . '" AND baseline="n" '; 
         // $sql = "SELECT product_id FROM product WHERE product_num = '" . $productnum . "' AND baseline='n' ";
           $sql = "SELECT product_id FROM product WHERE product_num = '" . $productnum . "'";
        try {
            $result = $this->getDefaultAdapter()->fetchOne($sql);
        } catch (Zend_Db_Adapter_Exception $e) {
            print_r($e);
        }
        if ($result == '') {
            return 0;
        } else {
            return $result;
        }
    }
    public function getcalcmod($productid) {
        $sql = "SELECT calcmod_id FROM mastergrid WHERE product_id = $productid";
       
        
        try {
            $result = $this->getDefaultAdapter()->fetchOne($sql);
        } catch (Zend_Db_Adapter_Exception $e) {
            print_r($e);
        }
        if ($result == '') {
            return 0;
        } else {
            return $result;
        }
    }
 public function calculatorname($calcid) {
        $sql = "SELECT cal_code FROM calc_mod WHERE id IN ($calcid)"; 
       
        try {
            $result = $this->getDefaultAdapter()->fetchAll($sql);
           //print_r($result);
        } catch (Zend_Db_Adapter_Exception $e) {
            print_r($e);
        }
        if ($result == '') {
            return 0;
        } else {
            return $result;
        }
    }
    public function checkcalculator($cal_code) {
        $sql = "SELECT id FROM calc_mod WHERE cal_code LIKE '%" . $cal_code . "%' AND active='y'";  

        try {
            $result = $this->getDefaultAdapter()->fetchOne($sql);
        } catch (Zend_Db_Adapter_Exception $e) {
            print_r($e);
        }
        if ($result == '') {
            return 0;
        } else {
            return $result;
        }
    }

     public function checkbaseline($baselinenum) {
        $sql = 'SELECT product_id FROM product WHERE product_num = "'.$baselinenum.'" AND baseline="y" '; 
        try {
            $result = $this->getDefaultAdapter()->fetchOne($sql);
        } catch (Zend_Db_Adapter_Exception $e) {
            print_r($e);
        }
        if ($result == '') {
            return 0;
        } else {
            return $result;
        }
    }
    public function getbaselineid($baselinename) {
        $sql = 'SELECT product_id FROM product WHERE product_num = "'.$baselinename.'" AND baseline="y" '; 
        try {
            $result = $this->getDefaultAdapter()->fetchOne($sql);
        } catch (Zend_Db_Adapter_Exception $e) {
            print_r($e);
        }
        if ($result == '') {
            return 0;
        } else {
            return $result;
        }
    }
 public function getbaselinename($product_id) { 
       // $sql = 'SELECT baseline_id FROM product_baseline WHERE product_id = "'.$product_id.'"  ';
       $sql = 'SELECT baseline_id FROM mastergrid WHERE product_id = "'.$product_id.'"  '; 
       $result = $this->getDefaultAdapter()->fetchOne($sql);
       if($result != ''){
            $sql = 'SELECT product_num FROM product WHERE product_id = "'.$result.'"  ';
           $result = $this->getDefaultAdapter()->fetchOne($sql);
       }else{
           return '';
       }
        if ($result == '') {
            return 0;
        } else {
            return $result;
        }
    }
    public function getattributeid($attributename) {
        $sql = 'SELECT subatt_id FROM sub_attributes WHERE subatt_name = "' . $attributename . '" AND display = "y"';
        try {
            $result = $this->getDefaultAdapter()->fetchOne($sql);
        } catch (Zend_Db_Adapter_Exception $e) {
            print_r($e);
        }
        if ($result == '') {
            return 0;
        } else {
            return $result;
        }
    }

     public function checkunitvalue($productid) {
                $sql = "SELECT product_id FROM unit_value WHERE product_id = $productid ";
                try {
                    $result = $this->getDefaultAdapter()->fetchOne($sql);
                } catch (Zend_Db_Adapter_Exception $e) {
                    print_r($e);
                }
                if ($result == '') {
                    return 0;
                } else {
                    return $result;
                }
    }
 public function getallunitvalues($productid) {
                $sql = "SELECT * FROM unit_value WHERE product_id = $productid ";
                try {
                    $result = $this->getDefaultAdapter()->fetchRow($sql);
                } catch (Zend_Db_Adapter_Exception $e) {
                    print_r($e);
                }
                if ($result == '') {
                    return 0;
                } else {
                    return $result;
                }
    }
    public function checkmastergrid($productid) {
        $sql = "SELECT product_id FROM mastergrid WHERE product_id='".$productid."' ";  
        try {
            $result = $this->getDefaultAdapter()->fetchOne($sql);
        } catch (Zend_Db_Adapter_Exception $e) {
            print_r($e);
        }
        if ($result == '') {
            return 0;
        } else {
            return $result;
        }
    }
 public function gettier1namebyid($tierid) {
        $sql = "SELECT tier1_name FROM tier1 WHERE tier1_id='".$tierid."' ";  
        try {
            $result = $this->getDefaultAdapter()->fetchOne($sql);
        } catch (Zend_Db_Adapter_Exception $e) {
            print_r($e);
        }
        if ($result == '') {
            return 0;
        } else {
            return $result;
        }
    }
 public function gettier2namebyid($tierid) {
        $sql = "SELECT tier2_name FROM tier2 WHERE tier2_id='".$tierid."' ";  
        try {
            $result = $this->getDefaultAdapter()->fetchOne($sql);
        } catch (Zend_Db_Adapter_Exception $e) {
            print_r($e);
        }
        if ($result == '') {
            return 0;
        } else {
            return $result;
        }
    }
     public function gettag1namebyid($tierid) {
        $sql = "SELECT tag1_name FROM tag1 WHERE tag1_id IN ($tierid) ";  
        try {
            $result = $this->getDefaultAdapter()->fetchAll($sql);
        } catch (Zend_Db_Adapter_Exception $e) {
            print_r($e);
        }
      
            return $result;
        
    }
    public function gettag2namebyid($tierid) {
        $sql = "SELECT tag2_name FROM tag2 WHERE tag2_id IN ($tierid) ";  
        try {
            $result = $this->getDefaultAdapter()->fetchAll($sql);
        } catch (Zend_Db_Adapter_Exception $e) {
            print_r($e);
        }
       
            return $result;
        
    }
    public function gettier1idbyname($tierid) { 
       $sql = "SELECT tier1_id FROM tier1 WHERE tier1_name LIKE '$tierid' "; 
        try {
            $result = $this->getDefaultAdapter()->fetchOne($sql);
        } catch (Zend_Db_Adapter_Exception $e) {
            print_r($e);
        }
        if ($result == '') {
            return 0;
        } else {
            return $result;
        }
    }
 public function gettier2idbyname($tierid) {
        $sql = "SELECT tier2_id FROM tier2 WHERE tier2_name= '$tierid' ";  
        try {
            $result = $this->getDefaultAdapter()->fetchOne($sql);
        } catch (Zend_Db_Adapter_Exception $e) {
            print_r($e);
        }
        if ($result == '') {
            return 0;
        } else {
            return $result;
        }
    }
     public function gettag1idbyname($tierid) {
        $sql = "SELECT tag1_id FROM tag1 WHERE tag1_name = '$tierid' ";  
        try {
            $result = $this->getDefaultAdapter()->fetchAll($sql);
        } catch (Zend_Db_Adapter_Exception $e) {
            print_r($e);
        }
      
            return $result;
        
    }
    public function gettag2idbyname($tierid) {
        $sql = "SELECT tag2_id FROM tag2 WHERE tag2_name = '$tierid' ";   
        try {
            $result = $this->getDefaultAdapter()->fetchAll($sql);
        } catch (Zend_Db_Adapter_Exception $e) {
            print_r($e);
        }
       
            return $result;
        
    }
    // end--gurjyot(import data)
    //vidya <<
    public function fetchSubAttList() {
        $sql = $this->_db->select()
                ->from(array('s' => $this->_subAttributes), array('*'))
                ->joinLeft(array('a' => $this->_attributes), 's.attribute_id = a.attribute_id', array('attribute_name', 'attribute_image'))
                ->order('s.attribute_id ASC');
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function insertImportFileName($filename) {
        if (empty($filename)) {
            return false;
        }
        $loginSession = new Zend_Session_Namespace("login");
        $details = array(
            'user_id' => $loginSession->userId,
            'file_name' => $filename
        );
        try {
            if ($this->_db->insert($this->_importExcelsheetRecord, $details)) {
                return true;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function fetchLastModifiedImportFile() {
        
        //$sql = "SELECT * FROM ".$this->_importExcelsheetRecord." ORDER BY modified_date desc LIMIT 1 "; 
         $sql = "SELECT ier.* , u.fname , u.lname
                FROM import_excelsheet_record ier
                INNER JOIN users u
                ON ier.user_id = u.user_id ORDER BY ier.modified_date desc LIMIT 1 ";
      
        try {
            $result = $this->_db->fetchRow($sql); 
            
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    // vidya  >>
}

?>
