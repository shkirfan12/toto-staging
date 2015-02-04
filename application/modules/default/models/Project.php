<?php

class Default_Model_Project extends Zend_Db_Table_Abstract {

    protected $_project = 'project';
    protected $_country = 'countries';
    protected $_state   = 'states';
     protected $_payback = 'payback';

    public function fetchProjectList($userId) {
        $sql = $this->_db->select()
                ->from($this->_project)
                ->where('user_id = ' . $userId);
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    public function fetchState() {
        $sql = $this->_db->select()
                ->from($this->_state);
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    public function fetchCountry() {
        $sql = $this->_db->select()
                ->from($this->_country);
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    
    public function fetchProjectInfoById($projectId){
        if(empty($projectId)){
            return false;
        }
         $sql = $this->_db->select('*')
                ->from($this->_project)
                ->where('project_id = ' . $projectId);
        try {
            $result = $this->_db->fetchRow($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    public function enterProjectDetails($user_Id,$data){ 
        if(empty($user_Id) || empty($data)){
            return false;
        }
        
        $projCalculatePid = new Zend_Session_Namespace('calculatePid');
        $calculatePid = $projCalculatePid->pid;
        if(!empty($calculatePid)){
            $product_id = $calculatePid.',';
        }else{
            $product_id = '';
        }
        if(isset($data['leed_list']) && count($data['leed_list']) > 0){
            $leed_list = implode(',' ,$data['leed_list']);
        }else{
            $leed_list = "";
        }
       
       
        if($data['hdnProjectType'] == 'commercial'){
            $details = array(
                'user_id' => $user_Id,
                'product_id' => $product_id,
                'project_name' => $data['project_name'],
                'project_type' => $data['hdnProjectType'],
                'city' => $data['city'],
                'state' => $data['state'],
                'zip' => $data['zip'],
                'country'=> $data['country'],
                'scope' => $data['scope'],
                'leed_list' => $leed_list,
                'commertial_male_occupants' => $data['commertial_male_occupants'],
                'commertial_female_occupants' => $data['commertial_female_occupants'],
                                'Bike_occupants'=>$data['bike_occupants'],

                'commertial_size' => $data['commertial_size'],
                
                'project_category' => $data['project_category'],
                
                'hrs_a_day' => $data['hrs_a_day'],
                'days_a_week' => $data['days_a_week'],
                'days_a_year' => $data['days_a_year'],
                'length_analysis' => $data['length_analysis'],
                'electricity_rate' => $data['electricity_rate'],
                'electricity_inflation' => $data['electricity_inflation'],
                'water_rate' => $data['water_rate'],
                'water_inflation' => $data['water_inflation']
            );
        }else{ //residential
             $details = array(
                'user_id' => $user_Id,
                'product_id' => $product_id,
                'project_name' => $data['project_name'],
                'project_type' => $data['hdnProjectType'],
                'city' => $data['city'],
                'state' => $data['state'],
                'zip' => $data['zip'],
                'country'=> $data['country'],
                'scope' => $data['scope'],
                 'leed_list' => $leed_list,
                'residential_household_member' => $data['residential_household_member'],
                'residential_sqft_space' => $data['residential_sqft_space'],
                'residential_no_bedroom' => $data['residential_no_bedroom'],
                'residential_no_bathroom' => $data['residential_no_bathroom'],
                 
                'project_category' => $data['project_category'],
                
                'hrs_a_day' => $data['hrs_a_day'],
                'days_a_year' => $data['days_a_year'],
                'length_analysis' => $data['length_analysis'],
                'electricity_rate' => $data['electricity_rate'],
                'electricity_inflation' => $data['electricity_inflation'],
                'water_rate' => $data['water_rate'],
                'water_inflation' => $data['water_inflation']
            );
        }
        
        try {
            if ($this->_db->insert($this->_project, $details)) {
                $lastInsertId = $this->_db->lastInsertId($this->_project, 'project_id');
                return $lastInsertId;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    
    public function updateProjectDetails($data){
        if (empty($data)) {
            return false;
        }
        $where['project_id=?'] = $data['hdnProjectId'];
        if(isset($data['leed_list']) && count($data['leed_list']) > 0){
            $leed_list = implode(',' ,$data['leed_list']);
        }else{
            $leed_list = "";
        }
        if($data['hdnProjectType'] == 'commercial'){
            $details = array(
                'project_name' => $data['project_name'],
                'project_type' => $data['hdnProjectType'],
                'city' => $data['city'],
                'state' => $data['state'],
                'zip' => $data['zip'],
                'country'=> $data['country'],
                'scope' => $data['scope'],
                 'leed_list' => $leed_list,
                'commertial_male_occupants' => $data['commertial_male_occupants'],
                'commertial_female_occupants' => $data['commertial_female_occupants'],
                'Bike_occupants'=>$data['bike_occupants'],
                'commertial_size' => $data['commertial_size'],
                
                'project_category' => $data['project_category'],
                
                'hrs_a_day' => $data['hrs_a_day'],
                'days_a_week' => $data['days_a_week'],
                'days_a_year' => $data['days_a_year'],
                'length_analysis' => $data['length_analysis'],
                'electricity_rate' => $data['electricity_rate'],
                'electricity_inflation' => $data['electricity_inflation'],
                'water_rate' => $data['water_rate'],
                'water_inflation' => $data['water_inflation']
            );
        }else{ //residential
             $details = array(
                'project_name' => $data['project_name'],
                'project_type' => $data['hdnProjectType'],
                'city' => $data['city'],
                'state' => $data['state'],
                'zip' => $data['zip'],
                'country'=> $data['country'],
                'scope' => $data['scope'],
                   'leed_list' => $leed_list,
                'residential_household_member' => $data['residential_household_member'],
                'residential_sqft_space' => $data['residential_sqft_space'],
                'residential_no_bedroom' => $data['residential_no_bedroom'],
                'residential_no_bathroom' => $data['residential_no_bathroom'],
                 
                'project_category' => $data['project_category'],
                
                'hrs_a_day' => $data['hrs_a_day'],
                'days_a_year' => $data['days_a_year'],
                'length_analysis' => $data['length_analysis'],
                'electricity_rate' => $data['electricity_rate'],
                'electricity_inflation' => $data['electricity_inflation'],
                'water_rate' => $data['water_rate'],
                'water_inflation' => $data['water_inflation']
            );
        }
        
        try {
            $this->_db->update($this->_project, $details, $where);
            return true;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
     public function delProjectById($projectId) {
        if (empty($projectId)) {
            return false;
        }
        $table = $this->_project;

        try {
            $where[] = "project_id = $projectId";

            if ($this->_db->delete($table, $where)) {
                return 'success';
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function updateProjectByProductIds($projectId, $product_value) {
        $where['project_id=?'] = $projectId;
        $details = array(
            'product_id' => $product_value
        );
        try {
            $this->_db->update($this->_project, $details, $where);
            return true;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    
     /*Author: Irfan Shaikh
     * Created Date : 20-06-2014
     * Desc :  Retrieves payback analysis
     */
    public function getPaybackDetails($uid){
        
         $table =  $this->_payback;
         $sql = "SELECT * FROM $table WHERE uid = $uid";
         try {
                // Get record if project id already exists and update
                $result = $this->_db->fetchAll($sql);
                return $result;
         }
         catch (Zend_Db_Adapter_Exception $e) {
               return false;
         }  
    }
    
}

?>
