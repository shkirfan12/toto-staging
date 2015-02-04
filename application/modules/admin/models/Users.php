<?php

class Admin_Model_Users extends Zend_Db_Table_Abstract {

    protected $_userType = 'user_type';
    protected $_users    = 'users';
    protected $_appstatus = 'application_status';
    protected $_substitute =  'substitute_contact_misc_info';
    protected $_provider_acct_info =  'provider_acct_info';
    protected $_db = '';
    
    public function getUsersList($uid) { 
       
        if($uid != ''){
        $sql = "SELECT U.user_id,U.user_type_id,U.first_name,U.last_name,U.email_id,U.country_id,U.graduate,U.graduationdate,U.applicationdate,U.address,U.city, ST.state_id, ST.state, U.phone, U.phone_1, U.phone_2, U.zip,U.active AS user_active,S.ssn_no, 
                A.electronic_app_complete,A.class_acceptance_confirm, A.email_add_verify, A.class_attendance_confirm, A.bg_chk_complete,A.final_approval,A.bk_form_recieve, A.signed_app_receive, A.active
                FROM users U 
                LEFT JOIN states ST ON ST.state_id = U.state_id
                LEFT JOIN application_status A ON U.user_id = A.user_id
                LEFT JOIN substitute_contact_misc_info S ON U.user_id = S.user_id  
                WHERE U.user_id = $uid";
        }else{
        $sql = "SELECT T.user_type,U.user_id, U.first_name, U.last_name, U.email_id, U.country_id,U.address,U.city, U.zip, U.applicant, U.graduate,U.phone, U.phone_1, U.phone_2,S.ssn_no,       
                U.applicationdate, U.graduationdate, U.active,U.isPrefGrad,P.prog_name FROM users U 
                INNER JOIN user_type T ON U.user_type_id = T.user_type_id 
                LEFT JOIN provider_acct_info P ON U.user_id = P.user_id  
                LEFT JOIN substitute_contact_misc_info S ON U.user_id = S.user_id  
                WHERE T.user_type_id !=4"; //LIMIT 0,100
        }
        try { 
            $result = $this->_db->fetchAll($sql); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    /* vid-modify << */
     /****************** Applicant << ***************************/
    public function getApplicantList(){
        $sql = "SELECT T.user_type,U.user_id, U.first_name, U.last_name, U.email_id, U.country_id,U.address,U.city, U.zip, U.applicant, U.graduate,U.phone, U.phone_1, U.phone_2,S.ssn_no,       
                U.applicationdate, U.graduationdate, U.active,U.isPrefGrad FROM users U 
                INNER JOIN user_type T ON U.user_type_id = T.user_type_id 
                LEFT JOIN substitute_contact_misc_info S ON U.user_id = S.user_id  
                WHERE T.user_type_id !=4
                AND U.applicant = 'y'
                AND T.user_type != 'Provider' ";
        try { 
            $result = $this->_db->fetchAll($sql); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
       public function getApplicantPaginationList($offset, $rowsperpage){
        $sql = "SELECT T.user_type,U.user_id, U.first_name, U.last_name, U.email_id, U.country_id,U.address,U.city, U.zip, U.applicant, U.graduate,U.phone, U.phone_1, U.phone_2,S.ssn_no,       
                U.applicationdate, U.graduationdate, U.active,U.isPrefGrad FROM users U 
                INNER JOIN user_type T ON U.user_type_id = T.user_type_id 
                LEFT JOIN substitute_contact_misc_info S ON U.user_id = S.user_id  
                WHERE T.user_type_id !=4
                AND U.applicant = 'y'
                AND T.user_type != 'Provider' LIMIT $offset, $rowsperpage";
       
        try { 
            $result = $this->_db->fetchAll($sql); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    } public function getApplicantPaginationChkList($offset, $rowsperpage, $active) { 
        $sql = "SELECT T.user_type,U.user_id, U.first_name, U.last_name, U.email_id, U.country_id,U.address,U.city, U.zip, U.applicant, U.graduate,U.phone, U.phone_1, U.phone_2,S.ssn_no,       
                U.applicationdate, U.graduationdate, U.active,U.isPrefGrad FROM users U 
                INNER JOIN user_type T ON U.user_type_id = T.user_type_id 
                LEFT JOIN substitute_contact_misc_info S ON U.user_id = S.user_id  
                WHERE T.user_type_id !=4
                AND U.applicant = 'y'
                AND T.user_type != 'Provider' 
                AND U.active ='$active' LIMIT $offset, $rowsperpage";
        try { 
            $result = $this->_db->fetchAll($sql); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
     }
      public function getApplicantChkList($active) { 
        $sql = "SELECT T.user_type,U.user_id, U.first_name, U.last_name, U.email_id, U.country_id,U.address,U.city, U.zip, U.applicant, U.graduate,U.phone, U.phone_1, U.phone_2,S.ssn_no,       
                U.applicationdate, U.graduationdate, U.active,U.isPrefGrad FROM users U 
                INNER JOIN user_type T ON U.user_type_id = T.user_type_id 
                LEFT JOIN substitute_contact_misc_info S ON U.user_id = S.user_id  
                WHERE T.user_type_id !=4
                AND U.applicant = 'y'
                AND T.user_type != 'Provider' 
                AND U.active ='$active'";
        try { 
            $result = $this->_db->fetchAll($sql); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
     }
         public function getApplicantPaginationSearchList($offset, $rowsperpage,$param, $status) {
        if (empty($param)) {
            return false;
        }
          $sql = $this->_db->select()
                ->from(array('u' => $this->_users), array('*'))
                ->joinLeft(array('r' => $this->_userType), 'u.user_type_id = r.user_type_id', array('user_type'))
                ->joinLeft(array('c' => $this->_substitute), 'u.user_id = c.user_id', array('*'))
                 ->where(" u.first_name LIKE '%$param%' or 
                          u.last_name LIKE '%$param%' or 
                          u.email_id LIKE '%$param%' or 
                          u.address LIKE '%$param%' or
                          u.city LIKE '%$param%' or 
                          u.zip LIKE '%$param%' or 
                          u.phone LIKE '%$param%' or 
                          u.phone_1 LIKE '%$param%' or
                          u.phone_2 LIKE '%$param%' or
                          c.ssn_no LIKE '%$param%'  or
                          CONCAT(u.first_name, ' ', u.last_name) LIKE  '%$param%' or
                          CONCAT(u.phone, ' ', u.phone_1, ' ', u.phone_2) LIKE  '%$param%' 
                       ") 
                ->where("u.user_type_id != '2'")
                ->where("u.user_type_id != '4'")
                ->where("u.applicant = 'y'");
               // ->orWhere('CONCAT(u.first_name, " ", u.last_name) LIKE ?', '%'.strip_tags($param).'%')
               // ->orWhere('CONCAT(u.phone, " ", u.phone_1, " ", u.phone_2) LIKE ?', '%'.strip_tags($param).'%');
      
        if(!empty($status)){
            $sql .= " AND u.active='".$status."'";
        }
        $sql .= " LIMIT $offset, $rowsperpage"; //echo $sql; die;
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function getApplicantSearchList($param, $status){
        if (empty($param)) {
            return false;
        }
          $sql = $this->_db->select()
                ->from(array('u' => $this->_users), array('*'))
                ->joinLeft(array('r' => $this->_userType), 'u.user_type_id = r.user_type_id', array('user_type'))
                ->joinLeft(array('c' => $this->_substitute), 'u.user_id = c.user_id', array('*'))
                 ->where(" u.first_name LIKE '%$param%' or 
                          u.last_name LIKE '%$param%' or 
                          u.email_id LIKE '%$param%' or 
                          u.address LIKE '%$param%' or
                          u.city LIKE '%$param%' or 
                          u.zip LIKE '%$param%' or 
                          u.phone LIKE '%$param%' or 
                          u.phone_1 LIKE '%$param%' or
                          u.phone_2 LIKE '%$param%' or
                          c.ssn_no LIKE '%$param%'  or
                          CONCAT(u.first_name, ' ', u.last_name) LIKE  '%$param%' or
                          CONCAT(u.phone, ' ', u.phone_1, ' ', u.phone_2) LIKE  '%$param%' 
                       ") 
                ->where("u.user_type_id != '2'")
                ->where("u.user_type_id != '4'")
                ->where("u.applicant = 'y'");
               // ->orWhere('CONCAT(u.first_name, " ", u.last_name) LIKE ?', '%'.strip_tags($param).'%')
               // ->orWhere('CONCAT(u.phone, " ", u.phone_1, " ", u.phone_2) LIKE ?', '%'.strip_tags($param).'%');
      
        if(!empty($status)){
            $sql .= " AND u.active='".$status."'";
        }
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
     /****************** Applicant >> ***************************/
     /****************** Graduation << ***************************/
       public function getGraduatePaginationSearchList($offset, $rowsperpage,$param, $status) {
        if (empty($param)) {
            return false;
        }
          $sql = $this->_db->select()
                ->from(array('u' => $this->_users), array('*'))
                ->joinLeft(array('r' => $this->_userType), 'u.user_type_id = r.user_type_id', array('user_type'))
                ->joinLeft(array('c' => $this->_substitute), 'u.user_id = c.user_id', array('*'))
                 ->where(" u.first_name LIKE '%$param%' or 
                          u.last_name LIKE '%$param%' or 
                          u.email_id LIKE '%$param%' or 
                          u.address LIKE '%$param%' or
                          u.city LIKE '%$param%' or 
                          u.zip LIKE '%$param%' or 
                          u.phone LIKE '%$param%' or 
                          u.phone_1 LIKE '%$param%' or
                          u.phone_2 LIKE '%$param%' or
                          c.ssn_no LIKE '%$param%'  or
                          CONCAT(u.first_name, ' ', u.last_name) LIKE  '%$param%' or
                          CONCAT(u.phone, ' ', u.phone_1, ' ', u.phone_2) LIKE  '%$param%' 
                       ") 
                ->where("u.user_type_id != '2'")
                ->where("u.user_type_id != '4'")
                ->where("u.graduate = 'y'");
               // ->orWhere('CONCAT(u.first_name, " ", u.last_name) LIKE ?', '%'.strip_tags($param).'%')
               // ->orWhere('CONCAT(u.phone, " ", u.phone_1, " ", u.phone_2) LIKE ?', '%'.strip_tags($param).'%');
      
        if(!empty($status)){
            $sql .= " AND u.active='".$status."'";
        }
        $sql .= " LIMIT $offset, $rowsperpage"; //echo $sql; die;
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function getGraduateSearchList($param, $status){
        if (empty($param)) {
            return false;
        }
          $sql = $this->_db->select()
                ->from(array('u' => $this->_users), array('*'))
                ->joinLeft(array('r' => $this->_userType), 'u.user_type_id = r.user_type_id', array('user_type'))
                ->joinLeft(array('c' => $this->_substitute), 'u.user_id = c.user_id', array('*'))
                 ->where(" u.first_name LIKE '%$param%' or 
                          u.last_name LIKE '%$param%' or 
                          u.email_id LIKE '%$param%' or 
                          u.address LIKE '%$param%' or
                          u.city LIKE '%$param%' or 
                          u.zip LIKE '%$param%' or 
                          u.phone LIKE '%$param%' or 
                          u.phone_1 LIKE '%$param%' or
                          u.phone_2 LIKE '%$param%' or
                          c.ssn_no LIKE '%$param%'  or
                          CONCAT(u.first_name, ' ', u.last_name) LIKE  '%$param%' or
                          CONCAT(u.phone, ' ', u.phone_1, ' ', u.phone_2) LIKE  '%$param%' 
                       ") 
                ->where("u.user_type_id != '2'")
                ->where("u.user_type_id != '4'")
                ->where("u.graduate = 'y'");
               // ->orWhere('CONCAT(u.first_name, " ", u.last_name) LIKE ?', '%'.strip_tags($param).'%')
               // ->orWhere('CONCAT(u.phone, " ", u.phone_1, " ", u.phone_2) LIKE ?', '%'.strip_tags($param).'%');
      
        if(!empty($status)){
            $sql .= " AND u.active='".$status."'";
        }
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
     public function getGraduatePaginationChkList($offset, $rowsperpage, $active) { 
        $sql = "SELECT T.user_type,U.user_id, U.first_name, U.last_name, U.email_id, U.country_id,U.address,U.city, U.zip, U.applicant, U.graduate,U.phone, U.phone_1, U.phone_2,S.ssn_no,       
                U.applicationdate, U.graduationdate, U.active,U.isPrefGrad FROM users U 
                INNER JOIN user_type T ON U.user_type_id = T.user_type_id 
                LEFT JOIN substitute_contact_misc_info S ON U.user_id = S.user_id  
                WHERE T.user_type_id !=4
                AND U.graduate = 'y'
                AND T.user_type != 'Provider' 
                AND U.active ='$active' LIMIT $offset, $rowsperpage";
        try { 
            $result = $this->_db->fetchAll($sql); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
     }
      public function getGraduateChkList($active) { 
        $sql = "SELECT T.user_type,U.user_id, U.first_name, U.last_name, U.email_id, U.country_id,U.address,U.city, U.zip, U.applicant, U.graduate,U.phone, U.phone_1, U.phone_2,S.ssn_no,       
                U.applicationdate, U.graduationdate, U.active,U.isPrefGrad FROM users U 
                INNER JOIN user_type T ON U.user_type_id = T.user_type_id 
                LEFT JOIN substitute_contact_misc_info S ON U.user_id = S.user_id  
                WHERE T.user_type_id !=4
                AND U.graduate = 'y'
                AND T.user_type != 'Provider' 
                AND U.active ='$active'";
        try { 
            $result = $this->_db->fetchAll($sql); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
     }
     public function getGraduatePaginationList($offset, $rowsperpage) { 
        $sql = "SELECT T.user_type,U.user_id, U.first_name, U.last_name, U.email_id, U.country_id,U.address,U.city, U.zip, U.applicant, U.graduate,U.phone, U.phone_1, U.phone_2,S.ssn_no,       
                U.applicationdate, U.graduationdate, U.active,U.isPrefGrad FROM users U 
                INNER JOIN user_type T ON U.user_type_id = T.user_type_id 
                LEFT JOIN substitute_contact_misc_info S ON U.user_id = S.user_id  
                WHERE T.user_type_id !=4
                AND U.graduate = 'y'
                AND T.user_type != 'Provider' LIMIT $offset, $rowsperpage";
        try { 
            $result = $this->_db->fetchAll($sql); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
     }
    
    public function getGraduateList() { 
        $sql = "SELECT T.user_type,U.user_id, U.first_name, U.last_name, U.email_id, U.country_id,U.address,U.city, U.zip, U.applicant, U.graduate,U.phone, U.phone_1, U.phone_2,S.ssn_no,       
                U.applicationdate, U.graduationdate, U.active,U.isPrefGrad FROM users U 
                INNER JOIN user_type T ON U.user_type_id = T.user_type_id 
                LEFT JOIN substitute_contact_misc_info S ON U.user_id = S.user_id  
                WHERE T.user_type_id !=4
                AND U.graduate = 'y'
                AND T.user_type != 'Provider' ";
        try { 
            $result = $this->_db->fetchAll($sql); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
     }
     /****************** Graduation >> ***************************/
     /****************** Provider << ***************************/
     public function getProviderList() {
      $sql = "SELECT T.user_type,U.user_id, U.first_name, U.last_name, U.email_id, U.country_id,U.address,U.city, U.zip, U.applicant, U.graduate,U.phone, U.phone_1, U.phone_2,S.ssn_no,       
            U.applicationdate, U.graduationdate, U.active,U.isPrefGrad,P.prog_name FROM users U 
            INNER JOIN user_type T ON U.user_type_id = T.user_type_id 
            LEFT JOIN provider_acct_info P ON U.user_id = P.user_id  
            LEFT JOIN substitute_contact_misc_info S ON U.user_id = S.user_id  
            WHERE T.user_type_id !=4
            AND T.user_type = 'Provider'"; 
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
     public function getProviderPaginationList($offset, $rowsperpage) {
      $sql = "SELECT T.user_type,U.user_id, U.first_name, U.last_name, U.email_id, U.country_id,U.address,U.city, U.zip, U.applicant, U.graduate,U.phone, U.phone_1, U.phone_2,S.ssn_no,       
            U.applicationdate, U.graduationdate, U.active,U.isPrefGrad,P.prog_name FROM users U 
            INNER JOIN user_type T ON U.user_type_id = T.user_type_id 
            LEFT JOIN provider_acct_info P ON U.user_id = P.user_id  
            LEFT JOIN substitute_contact_misc_info S ON U.user_id = S.user_id  
            WHERE T.user_type_id !=4
            AND T.user_type = 'Provider' LIMIT $offset, $rowsperpage";
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function getProviderPaginationChkList($offset, $rowsperpage, $active) { 
        $sql = "SELECT T.user_type,U.user_id, U.first_name, U.last_name, U.email_id, U.country_id,U.address,U.city, U.zip, U.applicant, U.graduate,U.phone, U.phone_1, U.phone_2,S.ssn_no,       
                U.applicationdate, U.graduationdate, U.active,U.isPrefGrad,P.prog_name FROM users U 
                INNER JOIN user_type T ON U.user_type_id = T.user_type_id 
                LEFT JOIN provider_acct_info P ON U.user_id = P.user_id  
                LEFT JOIN substitute_contact_misc_info S ON U.user_id = S.user_id  
                WHERE T.user_type_id !=4
                AND T.user_type = 'Provider' 
                AND U.active ='$active' LIMIT $offset, $rowsperpage";
        try { 
            $result = $this->_db->fetchAll($sql); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
     }
      public function getProviderChkList($active) { 
        $sql = "SELECT T.user_type,U.user_id, U.first_name, U.last_name, U.email_id, U.country_id,U.address,U.city, U.zip, U.applicant, U.graduate,U.phone, U.phone_1, U.phone_2,S.ssn_no,       
                U.applicationdate, U.graduationdate, U.active,U.isPrefGrad,P.prog_name FROM users U 
                INNER JOIN user_type T ON U.user_type_id = T.user_type_id 
                LEFT JOIN provider_acct_info P ON U.user_id = P.user_id  
                LEFT JOIN substitute_contact_misc_info S ON U.user_id = S.user_id  
                WHERE T.user_type_id !=4
                AND T.user_type = 'Provider' 
                AND U.active ='$active'";
        try { 
            $result = $this->_db->fetchAll($sql); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
     }
       public function getProviderSearchList($param, $status) {
        if (empty($param)) {
            return false;
        }
          $sql = $this->_db->select()
                ->from(array('u' => $this->_users), array('*'))
                ->joinLeft(array('r' => $this->_userType), 'u.user_type_id = r.user_type_id', array('user_type'))
                ->joinLeft(array('c' => $this->_provider_acct_info), 'u.user_id = c.user_id', array('*'))
                ->where(" c.prog_name LIKE '%$param%' or  
                          u.first_name LIKE '%$param%' or 
                          u.last_name LIKE '%$param%' or 
                          u.email_id LIKE '%$param%' or 
                          u.address LIKE '%$param%' or
                          u.city LIKE '%$param%' or 
                          u.zip LIKE '%$param%' or 
                          u.phone LIKE '%$param%' or 
                          u.phone_1 LIKE '%$param%' or
                          u.phone_2 LIKE '%$param%' or
                          CONCAT(u.first_name, ' ', u.last_name) LIKE  '%$param%' or
                          CONCAT(u.phone, ' ', u.phone_1, ' ', u.phone_2) LIKE  '%$param%' 
                       ")
               /*->orWhere('CONCAT(u.first_name, " ", u.last_name) LIKE ?', '%'.strip_tags($param).'%')
                  ->orWhere('CONCAT(u.phone, " ", u.phone_1, " ", u.phone_2) LIKE ?', '%'.strip_tags($param).'%')*/
               // ->orWhere('CONCAT(u.first_name, " ", u.last_name) LIKE ?', '%' . $param )
               // ->orWhere('CONCAT(u.phone, " ", u.phone_1, " ", u.phone_2) LIKE ?', '%' . $param)
                ->where("u.user_type_id = '2'");
                //->where("u.user_type_id != '4'");
      
        if(!empty($status)){
            $sql .= " AND u.active='".$status."'";
        }//echo $sql;die;
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
      public function getProviderPaginationSearchList($offset, $rowsperpage,$param, $status) {
        if (empty($param)) {
            return false;
        }
          $sql = $this->_db->select()
                ->from(array('u' => $this->_users), array('*'))
                ->joinLeft(array('r' => $this->_userType), 'u.user_type_id = r.user_type_id', array('user_type'))
                ->joinLeft(array('c' => $this->_provider_acct_info), 'u.user_id = c.user_id', array('*'))
                ->where(" c.prog_name LIKE '%$param%' or  
                          u.first_name LIKE '%$param%' or 
                          u.last_name LIKE '%$param%' or 
                          u.email_id LIKE '%$param%' or 
                          u.address LIKE '%$param%' or
                          u.city LIKE '%$param%' or 
                          u.zip LIKE '%$param%' or 
                          u.phone LIKE '%$param%' or 
                          u.phone_1 LIKE '%$param%' or
                          u.phone_2 LIKE '%$param%' or
                          CONCAT(u.first_name, ' ', u.last_name) LIKE  '%$param%' or
                          CONCAT(u.phone, ' ', u.phone_1, ' ', u.phone_2) LIKE  '%$param%' 
                       ")
               /*->orWhere('CONCAT(u.first_name, " ", u.last_name) LIKE ?', '%'.strip_tags($param).'%')
                  ->orWhere('CONCAT(u.phone, " ", u.phone_1, " ", u.phone_2) LIKE ?', '%'.strip_tags($param).'%')*/
               // ->orWhere('CONCAT(u.first_name, " ", u.last_name) LIKE ?', '%' . $param )
               // ->orWhere('CONCAT(u.phone, " ", u.phone_1, " ", u.phone_2) LIKE ?', '%' . $param)
                ->where("u.user_type_id = '2'");
                //->where("u.user_type_id != '4'");
      
        if(!empty($status)){
            $sql .= " AND u.active='".$status."'";
        }//echo $sql;die;
        $sql .= " LIMIT $offset, $rowsperpage"; 
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    /****************** Provider >> ***************************/
    /* vid-modify >> */
    
    public function getClassesbyUserId($uid){
        
        $sql = "SELECT C.progname, C.clanguage, C.city, C.csdate, C.cedate FROM classes C
                JOIN classes_status CS ON C.cid=CS.class_id WHERE CS.user_id = $uid";
        
        try {
            $result = $this->_db->fetchAll($sql); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
        
    }
    
    public function updatePrefGrad($uid,$st){
        $userTable = $this->_users;
        $sql = "UPDATE $userTable SET isPrefGrad='$st' WHERE user_id=$uid";
        
        try {
                $result = $this->_db->query($sql);
                return $result;
        }
          catch (Zend_Db_Adapter_Exception $e){
                 
                return false;
            }
    }
    
    public function updatePassword($uid,$pass){
        
        $userTable = $this->_users;
        $sql = "UPDATE $userTable SET password='$pass' WHERE user_id=$uid";
        
        try {
                $result = $this->_db->query($sql);
                return $result;
        }
          catch (Zend_Db_Adapter_Exception $e){
                 
                return false;
            }
        
    }
    
    public function updateAppStatus($uid, $status, $field,$uType) {   
        $now = new Zend_Db_Expr('NOW()'); 
        $tbl = $this->_appstatus;
        if ($field == 'all') {
            /* Update User Table << */
            $this->makeUserActiveInactive($uid, $status);
            /* Update User Table >> */
            $sql = "UPDATE $tbl SET electronic_app_complete ='$status', 
                                  email_add_verify ='$status', 
                                  class_acceptance_confirm ='$status',
                                  class_attendance_confirm ='$status',
                                  bg_chk_complete ='$status',
                                  final_approval  ='$status',
                                  bk_form_recieve ='$status',
                                  signed_app_receive ='$status',
                                  active ='$status'
                                 WHERE user_id=$uid";
            if($uType !='2'){
                if($status == 'y'){
                    $applicant = 'n';
                    
                    $sql1 = "UPDATE users SET graduate='$status', graduationdate=$now, applicant='$applicant'  WHERE user_id=$uid";
                    $this->_db->query($sql1);
                }else{
                     $applicant = 'y';
                     $status    = 'n';
                     
                    $sql1 = "UPDATE users SET graduate='$status',graduationdate='', applicant='$applicant'  WHERE user_id=$uid";
                    $this->_db->query($sql1);
                }
                
            }
        }else if($field == "graduate"){ 
            if($status == 'y'){
                $applicant = 'n';
                
                $sql1 = "UPDATE users SET graduate='$status', graduationdate=$now, applicant='$applicant'  WHERE user_id=$uid";
                $this->_db->query($sql1);
            }else{
                 $applicant = 'y';
                 $status    = 'n';
                 
                 $sql1 = "UPDATE users SET graduate='$status',graduationdate='', applicant='$applicant'  WHERE user_id=$uid"; 
                 $this->_db->query($sql1);
            }
            //$sql = "UPDATE users SET $field='$status', applicant='$applicant'  WHERE user_id=$uid";
        }else if($field == "user_active"){ 
                $sql1 = "UPDATE users SET active='$status' WHERE user_id=$uid";
                $this->_db->query($sql1);
        } else {
            $sql = "UPDATE $tbl SET $field='$status' WHERE user_id=$uid";
        }
        //  echo $sql; die;
        try {
            $result = $this->_db->query($sql); 
           // return $result;
             return true;
        } catch (Zend_Db_Adapter_Exception $e) {

            return false;
        }
    }
    public function makeUserActiveInactive($uid,$status){
        if(empty($uid)){ return false; }
        /* if($status == 'y'){
             $applicant_status = 'n';
         }else if($status == 'n'){
             $applicant_status = 'y';
         }*/
         $tbl = $this->_users;
        // $sql = "UPDATE $tbl SET active='$status',graduate='$status',applicant='$applicant_status' WHERE user_id=$uid"; 
         $sql = "UPDATE $tbl SET active='$status' WHERE user_id=$uid"; 
          try {
                $result = $this->_db->query($sql);
                return true;
          }catch (Zend_Db_Adapter_Exception $e){
                return false;
          }
    }
    public function saveUserInfo($data,$uid){
        
        $table = $this->_users;
        
        list($fname,$lname) = explode(' ', $data["txtfname"]);
        
        $phn1 = substr($data["txtphone"], 0, 3);
        $phn2 = substr($data["txtphone"], 3, 5);
        $phn3 = substr($data["txtphone"], 6, 9);
        
        if(!empty($data["txtgradate"])){
            $graduationdate = date("Y-m-d", strtotime($data["txtgradate"]));
        }else{
            $graduationdate = '';
        }
         
        $subData =  array('address'=>$data['txtaddress'], 'ssn_no'=>$data["txtssn"]);
        
        $this->updateAddress($subData, $uid);
        
        //echo "<pre>"; print_r($data); exit;
        $datatable = array(
            'first_name' => $fname,
            'last_name' => $lname,
            'email_id' => $data["txtemail"],
           // 'ssn' => $data["txtssn"],
            //'graduationdate' => $data["txtaddress"];
            'address' => $data["txtaddress"],
            'city' => $data["txtcity"],
            'state_id' => $data["txtstate"],
            'country_id' => $data['country_id'],
            'zip' => $data["txtzip"],
            'phone' => $phn1,
            'phone_1'=> $phn2,
            'phone_2'=> $phn3,
            'graduationdate' => $graduationdate
          );
               
        $where[] = "user_id = $uid"; 
         
        try {
                if ( $this->_db->update($table, $datatable, $where) ) {
                    
                   //$result = $this->_db->query($updateQuery);
                   
                   return 'success'; 
                
            }
        }
          catch (Zend_Db_Adapter_Exception $e){
                 
                return false;
            }
        
    }
    
     public function updateAddress($subData, $uid){
        
           $substitute = $this->_substitute;
           $where[] = "user_id = $uid"; 
           
           
           try {
                if ( $this->_db->update($substitute, $subData,$where )) {
                    
                   return 'success'; 
            }
          }
          catch (Zend_Db_Adapter_Exception $e){
                 
                return false;
            }
    }
    
    public function exportUsers(){
        
        $result = $this->_db->fetchAll('SELECT * FROM users U
                                        LEFT JOIN user_type T 
                                        ON U.user_type_id = T.user_type_id

                                        LEFT JOIN substitute_contact_misc_info SC
                                        ON U.user_id=SC.user_id

                                        LEFT JOIN substitute_edu_info SE
                                        ON U.user_id=SE.user_id

                                        LEFT JOIN substitute_employment_history SH
                                        ON U.user_id=SH.user_id

                                        LEFT JOIN substitute_pgm_interest SP
                                        ON U.user_id=SP.user_id');
        
        return $result;
//         $xlsTbl = "<tr>
//                     <th>Type</th>
//                     <th>First Name</th>
//                     <th>Last Name</th>
//                     <th>Email</th>
//                     <th>city</th>
//                     <
//                     <th>Telephone</th>
//                    </tr>";
//          foreach($result as $key=>$val){
//           $xlsTbl .= "<tr>";
//           $xlsTbl .= "<td>" . $val["user_type_id"] . "</td>";
//           $xlsTbl .= "<td>" . $val["first_name"] . "</td>";
//           $xlsTbl .= "<td>" . $val["last_name"] . "</td>";
//           $xlsTbl .= "<td>" . $val["email_id"] . "</td>";
//           $xlsTbl .= "<td>" . $val["city"] . "</td>";
//           $xlsTbl .= "<td>" . $val["phone"] . "</td>";
//          
//          
//           $xlsTbl .= "</tr>";
//          }
//
//          return $xlsTbl;
    }
    
     public function updateApplicationStatusActive($uid) { 
         if(empty($uid)){
             return false;
         }
         /* Update User Table << */
         $this->makeUserActiveInactive($uid, 'y');
         /* Update User Table >> */
         $where['user_id=?'] = $uid;
         $details = array(
             'electronic_app_complete' => 'y',
             'email_add_verify'        => 'y',
             'class_acceptance_confirm'=> 'y',
             'class_attendance_confirm'=> 'y',
             'bg_chk_complete'         => 'y',
             'final_approval'          => 'y',
             'bk_form_recieve'         => 'y',
             'signed_app_receive'      => 'y',
             'active'                  => 'y'
      );
        try {
            if ($this->_db->update($this->_appstatus, $details, $where)) {
                return true;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
      
     }
      public function fetchGraduationDate($uid){
         if(empty($uid)){
             return false;
         } 
           $sql = "SELECT graduationdate FROM users WHERE user_id=$uid";

        try {
            $result = $this->_db->fetchRow($sql); 
            if($result['graduationdate']!= '0000-00-00' || $result['graduationdate']!='') {
                $graduationdate = date("m/d/Y", strtotime($result['graduationdate']));
                return $graduationdate; 
            }
       } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
     }
      public function delUserStatusInactive($uid) { 
         if(empty($uid)){
             return false;
         }
         $where['user_id=?'] = $uid;
         $details = array(
             'active' => 'n'
      );
        try {
            if ($this->_db->update($this->_users, $details, $where)) {
                return true;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
      
     }
     /* Search : Applicants << */
       public function search_applicant($param, $status) {
        if (empty($param)) {
            return false;
        }
        /* $sql = "SELECT T.user_type,U.user_id, U.first_name, U.last_name, U.email_id, U.country_id,U.address,U.city, U.zip, U.applicant, U.graduate,U.phone, U.phone_1, U.phone_2,S.ssn_no,       
                U.applicationdate, U.graduationdate, U.active,U.isPrefGrad FROM users U 
                INNER JOIN user_type T ON U.user_type_id = T.user_type_id 
                LEFT JOIN substitute_contact_misc_info S ON U.user_id = S.user_id  
                WHERE T.user_type_id !=4 AND T.user_type_id != '2' AND U.applicant='y' AND  
                  U.first_name LIKE '%$param%' or 
                  U.last_name LIKE '%$param%' or 
                  U.email_id LIKE '%$param%' or 
                  U.address LIKE '%$param%' or
                  U.city LIKE '%$param%' or 
                  U.zip LIKE '%$param%' or 
                  U.phone LIKE '%$param%' or 
                  U.phone_1 LIKE '%$param%' or
                  U.phone_2 LIKE '%$param%' or
                  S.ssn_no LIKE '%$param%'";*/
        //http://stackoverflow.com/questions/5896471/zend-db-concatenate-two-columns
          $sql = $this->_db->select()
                ->from(array('u' => $this->_users), array('*'))
                ->joinLeft(array('r' => $this->_userType), 'u.user_type_id = r.user_type_id', array('user_type'))
                ->joinLeft(array('c' => $this->_substitute), 'u.user_id = c.user_id', array('*'))
                 ->where(" u.first_name LIKE '%$param%' or 
                          u.last_name LIKE '%$param%' or 
                          u.email_id LIKE '%$param%' or 
                          u.address LIKE '%$param%' or
                          u.city LIKE '%$param%' or 
                          u.zip LIKE '%$param%' or 
                          u.phone LIKE '%$param%' or 
                          u.phone_1 LIKE '%$param%' or
                          u.phone_2 LIKE '%$param%' or
                          c.ssn_no LIKE '%$param%' or
                          CONCAT(u.first_name, ' ', u.last_name) LIKE  '%$param%' or
                          CONCAT(u.phone, ' ', u.phone_1, ' ', u.phone_2) LIKE  '%$param%' 
                       ") 
                ->where("u.user_type_id != '2'")
                ->where("u.user_type_id != '4'")
                ->where("u.applicant = 'y'");
              //  ->orWhere('CONCAT(u.first_name, " ", u.last_name) LIKE ?', '%'.strip_tags($param).'%')
               // ->orWhere('CONCAT(u.phone, " ", u.phone_1, " ", u.phone_2) LIKE ?', '%'.strip_tags($param).'%');
      
        if(!empty($status)){
            $sql .= " AND u.active='".$status."'";
        }
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
     /* Search : Applicants >> */
    /* Search : Graduates << */
       public function search_graduates($param, $status) {
        if (empty($param)) {
            return false;
        }
          $sql = $this->_db->select()
                ->from(array('u' => $this->_users), array('*'))
                ->joinLeft(array('r' => $this->_userType), 'u.user_type_id = r.user_type_id', array('user_type'))
                ->joinLeft(array('c' => $this->_substitute), 'u.user_id = c.user_id', array('*'))
                 ->where(" u.first_name LIKE '%$param%' or 
                          u.last_name LIKE '%$param%' or 
                          u.email_id LIKE '%$param%' or 
                          u.address LIKE '%$param%' or
                          u.city LIKE '%$param%' or 
                          u.zip LIKE '%$param%' or 
                          u.phone LIKE '%$param%' or 
                          u.phone_1 LIKE '%$param%' or
                          u.phone_2 LIKE '%$param%' or
                          c.ssn_no LIKE '%$param%'  or
                          CONCAT(u.first_name, ' ', u.last_name) LIKE  '%$param%' or
                          CONCAT(u.phone, ' ', u.phone_1, ' ', u.phone_2) LIKE  '%$param%' 
                       ") 
                ->where("u.user_type_id != '2'")
                ->where("u.user_type_id != '4'")
                ->where("u.graduate = 'y'");
               // ->orWhere('CONCAT(u.first_name, " ", u.last_name) LIKE ?', '%'.strip_tags($param).'%')
               // ->orWhere('CONCAT(u.phone, " ", u.phone_1, " ", u.phone_2) LIKE ?', '%'.strip_tags($param).'%');
      
        if(!empty($status)){
            $sql .= " AND u.active='".$status."'";
        }
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
     /* Search : Graduates >> */
     /* Search : Provider << */
       public function search_provider($param, $status) {
        if (empty($param)) {
            return false;
        }
          $sql = $this->_db->select()
                ->from(array('u' => $this->_users), array('*'))
                ->joinLeft(array('r' => $this->_userType), 'u.user_type_id = r.user_type_id', array('user_type'))
                ->joinLeft(array('c' => $this->_provider_acct_info), 'u.user_id = c.user_id', array('*'))
                ->where(" c.prog_name LIKE '%$param%' or  
                          u.first_name LIKE '%$param%' or 
                          u.last_name LIKE '%$param%' or 
                          u.email_id LIKE '%$param%' or 
                          u.address LIKE '%$param%' or
                          u.city LIKE '%$param%' or 
                          u.zip LIKE '%$param%' or 
                          u.phone LIKE '%$param%' or 
                          u.phone_1 LIKE '%$param%' or
                          u.phone_2 LIKE '%$param%' or
                          CONCAT(u.first_name, ' ', u.last_name) LIKE  '%$param%' or
                          CONCAT(u.phone, ' ', u.phone_1, ' ', u.phone_2) LIKE  '%$param%' 
                       ")
               /*->orWhere('CONCAT(u.first_name, " ", u.last_name) LIKE ?', '%'.strip_tags($param).'%')
                  ->orWhere('CONCAT(u.phone, " ", u.phone_1, " ", u.phone_2) LIKE ?', '%'.strip_tags($param).'%')*/
               // ->orWhere('CONCAT(u.first_name, " ", u.last_name) LIKE ?', '%' . $param )
               // ->orWhere('CONCAT(u.phone, " ", u.phone_1, " ", u.phone_2) LIKE ?', '%' . $param)
                ->where("u.user_type_id = '2'");
                //->where("u.user_type_id != '4'");
      
        if(!empty($status)){
            $sql .= " AND u.active='".$status."'";
        }//echo $sql;die;
        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
     /* Search : Provider >> */
    
    public function getuserinfo($page=0)
    {
        $sql = "SELECT users.*,states.*,
            (select count(project.project_id) from project where users.user_id = project.user_id) as project_count , 
            (select count(collection.collection_id) from collection where users.user_id = collection.user_id) as collection_count
FROM users  left join states on states.state_id=users.state_id";
        $result = $this->getDefaultAdapter()->fetchAll($sql);
        if($result)
        {
        foreach($result as $key=>$val)
        {
           
            $sql_new = "";
    $sql_new=" select product_id from collection where user_id = '
            
            ".$val['user_id']."'";
    
        $result_new = $this->getDefaultAdapter()->fetchAll($sql_new);
        if($result_new)
        {
            $prdct_string = "";
            foreach($result_new as $keynew=>$valnew)
            {
              //  substr($val['product_id'], 0, -1);
                //$new_array = explode(',' , $val['product_id']);
                
                $prdct_string .= trim($valnew['product_id']);
                
               if(strlen($prdct_string)>1){
                $prdct_string_n =    substr($prdct_string, 0, -1);
               }else{
                   $prdct_string_n = $prdct_string;
               }
                 
                $product_array = explode(',' , $prdct_string_n);
               
                //print_r($product_array); echo "-----";
                
               $product_array_new = array_unique($product_array);
              
               $product_count = count($product_array_new);
              
               $result[$key]['product_count'] = $product_count;
            }
        }else{
            $result[$key]['product_count'] = 0;
        }
        }
        }
        return $result;
    }
    
    public function getclientinfo()
    {
        $sql= "select * from client";
        $result = $this->getDefaultAdapter()->fetchAll($sql);
        return $result;
    }
    
    
    public function insertuserinfo($data) {

        $table = 'users';
        $datatable = array(
            'fname' => $data['new_fname'],
            'lname' => $data['new_lname'],
            'password' => md5($data['new_password']),
            'email_id' => $data['email'],
            'profileType' => $data['utype'],
            'state_id' => $data['state']);

        try {
            $result = $this->_db->insert($table, $datatable);
//                        print_r($result); die;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    
    public function insertclientinfo($data)
    {
        $table ='client';
                        $datatable = array(
                            'client_name' => $data['new_cname'],
                'payment_status' => $data['payment'],
                'launch_status' =>$data['launch'],
                'site_address' => $data['new_address']);
                         try 
                    {
                        $result = $this->_db->insert($table, $datatable);
//                        print_r($result); die;
                    }
                catch (Zend_Db_Adapter_Exception $e) 
                    {
                        return false;
                    }
                            

    }
    
    public function edituser($editid)
     {
         $sql="select * from users where user_id='$editid'";
              $result = $this->getDefaultAdapter()->fetchRow($sql);
              return $result;
     }
     
     public function editclient($editid)
     {
         $sql="select * from client where client_id='$editid'";
              $result = $this->getDefaultAdapter()->fetchRow($sql);
              return $result;
     }
     
     public function updateuser($firstname, $lastname, $email, $usertype,$state, $editid) {
        $table = 'users';
        $data = array(
            'fname' => $firstname,
            'lname' => $lastname,
            'email_id' => $email,
            'profileType' => $usertype,
            'state_id' => $state
        ); 
        $where['user_id =?'] = $editid;
        try {
            $this->_db->update($table, $data, $where);
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    
    public function updateclient($cname, $payment, $status, $address, $editid)
    {
        $table = 'client';
        $data = array(
            'client_name' => $cname,
            'payment_status' => $payment,
            'launch_status' => $status,
            'site_address' => $address
        ); 
        $where['client_id =?'] = $editid;
        try {
            $this->_db->update($table, $data, $where);
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    
    public function getstate()

    {
        $sql= "select * from states";
        $result = $this->getDefaultAdapter()->fetchAll($sql);
        return $result;
    }
        public function checkmail($email)
    {
        $sql ="select email_id from users where email_id='$email' ";
        $result = $this->getDefaultAdapter()->fetchRow($sql);
        
        if ($result != "")
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }
    
    public function sendmail($ans)
    {
      
        $sql ="select fname,lname,email_id from users where user_id in ($ans) ";
    
        $result = $this->getDefaultAdapter()->fetchAll($sql);
        return $result;
    }
    
    public function newpassword ($ans,$password)
    {
        
        
         $table = 'users';
                $data = array(
                'password' =>$password  
                );
                $where['email_id =?'] = $ans;
                try {
                    $this->_db->update($table, $data, $where);
                    } 
                catch (Zend_Db_Adapter_Exception $e) {
                    return false;
                    }
    }
    
}

?>
