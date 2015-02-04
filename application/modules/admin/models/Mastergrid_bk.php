<?php

class Admin_Model_Mastergrid extends Zend_Db_Table_Abstract {

    protected $_mastergrid = 'mastergrid';
    protected $_unitvalue = 'unit_value';
    protected $_calc = "calc_mod";
    protected $_attributes = 'attributes';
    protected $_subAttributes = 'sub_attributes';

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
            // $id = $this->getDbTable->newId($table, $idname);
            if ($this->_db->update($table, $details, $where)) {
                $sql = "SELECT product_id FROM $table ORDER BY modified_date desc LIMIT 1 ";
                try {
                    $result = $this->getDefaultAdapter()->fetchOne($sql);
                } catch (Zend_Db_Adapter_Exception $e) {
                    print_r($e);
                }

                return $result;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    public function checkproductexists($productnum) {
      //  $sql = 'SELECT product_id FROM product WHERE product_name = "' . $baselinename . '" AND product_num = "' . $productnum . '" AND baseline="n" '; 
          $sql = "SELECT product_id FROM product WHERE product_num = '" . $productnum . "' AND baseline='n' ";
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

    public function checkcalculator($productid) {
        $sql = "SELECT id FROM calc_mod WHERE name LIKE '%" . $productid . "%' AND active='y'"; 
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
        $sql = 'SELECT product_id FROM product WHERE product_name = "'.$baselinename.'" AND baseline="y" ';
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

    // vidya  >>
}

?>
