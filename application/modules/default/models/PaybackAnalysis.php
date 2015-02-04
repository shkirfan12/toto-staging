<?php

class Default_Model_PaybackAnalysis extends Zend_Db_Table_Abstract {

    protected $_project = 'project';
    protected $_payback = 'payback';

    public function fetchProjectInfoById($projectId) {
        if (empty($projectId)) {
            return false;
        }
        $sql = $this->_db->select()
                ->from($this->_project)
                ->where('project_id = ' . $projectId);
        try {
            $result = $this->_db->fetchRow($sql); //echo "1-<pre>"; print_R($result); die;//..
            if(!empty($result['product_id'])){
                $arrRes1 = array(); $arrRes2 = array(); 
                
                $arrRes1 [] = $result; //echo "arrRes1<pre>"; print_r($arrRes1); // arr1  
                $product_id = substr($result['product_id'], 0, -1); //echo $product_id; die;
                
                $sql = "SELECT s.*,`a`.`baseline_id`,`a`.`calcmod_id`,`a`.`uv_id` FROM product as s
                        LEFT JOIN mastergrid as a ON s.product_id=a.product_id
                        WHERE s.product_id IN ($product_id) ORDER BY FIELD(s.product_id, $product_id)";
                
                $result1 = $this->_db->fetchAll($sql); //echo "result1<pre>"; print_r($result1); die;
                
                foreach($result1 as $k => $v){
                    if(!empty($v['calcmod_id'])){
                        $calcmod_id = $v['calcmod_id'];
                        $sql3 = "SELECT `c`.`id`,`c`.`name`,`c`.`calc`,`c`.`type`,`c`.`cal_code` FROM calc_mod as c
                                WHERE c.id IN ($calcmod_id)";
                        $result3 = $this->_db->fetchAll($sql3); 
                        $v['calcmod_id'] = $result3;
                        $result1[$k] = $v;
                    }else{
                        $result1[$k] = $v;
                    }
                }//echo "result1+3<pre>"; print_r($result1); die;
                /*main $sql = "SELECT s.*,`a`.`baseline_id`,`a`.`calcmod_id`,`a`.`uv_id`,`c`.`name`,`c`.`calc`,`c`.`type`,`c`.`cal_code` FROM product as s
                        LEFT JOIN mastergrid as a ON s.product_id=a.product_id
                        LEFT JOIN calc_mod as c ON a.calcmod_id=c.id
                        WHERE s.product_id IN ($product_id)";
                $result1 = $this->_db->fetchAll($sql); */
                
                $arrRes2 [] = $result1;   
                
               // echo "arrRes1<pre>"; print_R($arrRes1);  echo "arrRes2<pre>"; print_R($arrRes2); die;
                
                
                $result2 = array_merge($arrRes1, $arrRes2);  //echo "result2<pre>"; print_R($result2);
                return $result2; 
            }else{
                $result = '';
                return $result;
            }
           
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    
    public function getHumanPlanet($projectId){
        
        if (empty($projectId)) {
            return false;
        }
        
        $sql = $this->_db->select()
                ->from($this->_project)
                ->where('project_id = ' . $projectId);
        try {
            $result = $this->_db->fetchRow($sql); //echo "1-<pre>"; print_R($result); //..
            if(!empty($result['product_id'])){
                $arrRes1 = array(); 
                $arrRes2 = array(); 
                
                $arrRes1 [] = $result;  // arr1 
             
                $product_id = substr($result['product_id'], 0, -1); 
                $sql = "SELECT u.subatt_id FROM unit_value u WHERE u.product_id IN ($product_id)";
                //echo $sql;
                $resultSubAtt = $this->_db->fetchAll($sql); 
                $resultSubAtt = explode(',', $resultSubAtt[0]['subatt_id']);
                
               
                $aSql = "SELECT u.subatt_id, s.subatt_id, s.subatt_name FROM unit_value u LEFT JOIN sub_attributes s ON u.subatt_id = s.subatt_id WHERE s.subatt_id IN ($resultSubAtt)";
                $iResult = $this->_db->fetchAll($aSql); 
                print_r($iResult); die;
                
                
                $arrRes2 [] = $result1;  // arr1
                $result2 = array_merge($arrRes1, $arrRes2);
                return $result2;
            }else{
                $result = '';
                return $result;
            }
            
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
   
    
    /* Author: Irfan Shaikh
     * Created Date : 24-06-2014
     * Desc :  Saves payback analysis
     */
    public function savePaybackDetails($uid,$pid, $savings, $energy, $water){
        
         $table = $this->_payback;
         $sResult = array();
         $datatable = array(
                        'uid'     => $uid,
                        'pid'     => $pid,
                        'savings' => floatval($savings),
                        'energy'  => floatval($energy),
                        'water'   => floatval($water));
          
         $sql = "SELECT * FROM $table where uid = $uid and pid = $pid";
            try {
                   $sResult =  $this->_db->fetchAll($sql);
               }
            catch (Zend_Db_Adapter_Exception $e){
                    throw $e;
              }
              
            if(count($sResult)>0){

                  try {
                        $where['pid=?'] = $pid;
                        $where['uid=?'] = $uid;

                        $uResult =  $this->_db->update($table, $datatable, $where);
                        return 'success';
                      }
                     catch (Zend_Db_Adapter_Exception $e){
                            throw $e;
                      }

               }// IF Ends
               //
                // esle if project id doesn not exist insert
             else{

                  try {
                         $iResult =  $this->_db->insert($this->_payback, $datatable);
                         return 'success';
                      }
                  catch (Zend_Db_Adapter_Exception $e){
                        throw $e;
                      }
                  }// ELSE Ends
             
       }// FUNCTION Ends
    
}

?>
