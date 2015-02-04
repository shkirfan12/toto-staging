<?php

class Login_Model_Account extends Zend_Db_Table_Abstract {

    protected $_users     = 'users';
     protected $_project = 'project';
     protected $_preset = 'preset';
    
    public function fetchUserInfo($userEmail,$pwd) { //..
        if (empty($userEmail)) {
            return false;
        }
        $sql = $this->_db->select()
                ->from(array('u' => $this->_users), array('user_id', 'fname', 'lname', 'email_id', 'password'))
                ->where('u.email_id = ?', $userEmail)
                ->where('u.password = ?', md5($pwd))
                ->where('u.active = ?', 'y');
      
        $row = $this->_db->fetchRow($sql);  

        if ($row) {
            return $row;
        } else {
            return false;
        }
    }
     public function fetchUserInfoByCookie($userEmail,$pwd) { //..
        if (empty($userEmail)) {
            return false;
        }
        $sql = $this->_db->select()
                ->from(array('u' => $this->_users), array('user_id', 'fname', 'lname', 'email_id', 'password'))
                ->where('u.email_id = ?', $userEmail)
                ->where('u.password = ?', $pwd)
                ->where('u.active = ?', 'y');
      
        $row = $this->_db->fetchRow($sql);  

        if ($row) {
            return $row;
        } else {
            return false;
        }
    }
    /* ====================================================== */
    /* change password implementation */

    public function checkOldPassword($pwd, $userId) {
        if ((empty($pwd)) || (empty($userId)))
            return false;

        $qry = $this->_db->select()
                ->from(array('u' => $this->_users))
                ->where('user_id = ?', $userId)
                ->where('password  = ?', $pwd);

        try {
            $result = $this->_db->fetchRow($qry);
            return ($result) ? true : false;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    public function getUserId($email) {
        if (empty($email)) {
            return false;
        }

        $qry = $this->_db->select()
                ->from(array('u' => $this->_users))
               // ->where('email_id =' . "'" . $email . "'");
                ->where('email_id =?',$email);
        try {
            $result = $this->_db->fetchRow($qry); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function getUserInfoByEmailnType($email) { //..
        if (empty($email)) {
            return false;
        }

        $qry = $this->_db->select()
                ->from(array('u' => $this->_users))
                ->where('email_id =' . "'" . $email . "'");
        try {
            $result = $this->_db->fetchRow($qry); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    public function changePassword($newPassword, $userId) { //..
        if ((empty($newPassword)) || (empty($userId))) {
            return false;
        }
        $now = new Zend_Db_Expr('NOW()');

        $category = array(
            'password' => md5($newPassword),
          //  'updated_on' => $now
            'datemodified' => $now
        ); 
        $where['user_id=?'] = $userId;

        try {
            $result = $this->_db->update($this->_users, $category, $where);
            return true;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

   
    /* ====================================================== */

    /* random key function */

    public function str_rand($length = 8, $seeds = 'alphanum') { //..
        $seedings['alphanum'] = 'abcdefghijklmnopqrstuvwqyz0123456789';

        // Choose seed
        if (isset($seedings[$seeds])) {
            $seeds = $seedings[$seeds];
        }

        // Generate random number
        $str = '';
        $seeds_count = strlen($seeds);

        for ($i = 0; $length > $i; $i++) {
            $str .= $seeds{mt_rand(0, $seeds_count - 1)};
        }

        return $str;
    }
    
    /* MGP : Register Section << */
    public function checkUserEmailExists($userEmail) {
        if (empty($userEmail))
            return false;
        $sql = $this->_db->select()
                ->from($this->_users)
                ->where('email_id = ?', $userEmail);
        try {
            $result = $this->_db->fetchRow($sql);
            return ($result) ? true : false;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
      public function checkPasscodeExists($passcode) {
        if (empty($passcode))
            return false;
        $sql = $this->_db->select()
                ->from($this->_preset)
                ->where('passcode = ?', $passcode);
        try {
            $result = $this->_db->fetchRow($sql);
            return ($result) ? true : false;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function checkSessionUserEmailExists($userEmail,$sessionUserId) {
        if (empty($userEmail))
            return false;
        $sql = $this->_db->select()
                ->from($this->_users)
                ->where('email_id = ?', $userEmail)
                ->where('user_id != ?', $sessionUserId);
        try {
            $result = $this->_db->fetchRow($sql);
            return ($result) ? true : false;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function enterUserDetails($fname,$lname,$email, $pwd) {
        if (empty($email) || empty($pwd)) {
            return false;
        }
        $now = new Zend_Db_Expr('NOW()');
        $details = array(
            'fname'        => $fname,
            'lname'        => $lname,
            'email_id'     => $email,
            'password'     => md5($pwd),
            'created_date' => $now
        );
        try {
            if ($this->_db->insert($this->_users, $details)) {
                $lastInsertId = $this->_db->lastInsertId($this->_users, 'user_id'); //return $lastInsertId;
                $userRecord = $this->fetchUserDetailsByUserId($lastInsertId);
                return $userRecord;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function fetchUserDetailsByUserId($userId) { 
        if (empty($userId)) {
            return false;
        }
        $sql = $this->_db->select()
                ->from($this->_users)
                ->where('user_id = ?', $userId); 
        $row = $this->_db->fetchRow($sql);
        if ($row) {
            return $row;
        } else {
            return false;
        }
    }
    public function updateDummyUserDetails($fname,$lname,$email, $pwd,$userId) {
        if (empty($email) || empty($pwd)) {
            return false;
        }
        $now = new Zend_Db_Expr('NOW()');
        $where['user_id=?'] = $userId;
        $details = array(
            'fname'        => $fname,
            'lname'        => $lname,
            'email_id'     => $email,
            'password'     => md5($pwd),
            'datemodified' => $now
        );
        try {
            if ($this->_db->update($this->_users, $details, $where)) {
                $userRecord = $this->fetchUserDetailsByUserId($userId);
                return $userRecord;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    /* MGP :  Register Section >> */

    public function fetchProjectList($userId) {
        $sql = $this->_db->select()
                ->from($this->_project)
                ->where('user_id = ' . $userId);
        try {
            $result = $this->_db->fetchRow($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
   public function updateDummyUserIdByOriginalUser($dummyUserId, $originalUserId){
       if (empty($dummyUserId) || empty($originalUserId)) {
            return false;
        }
        $dummyUserDetails = $this->fetchProjectList($dummyUserId); 
        
        $where['project_id=?'] = $dummyUserDetails['project_id'];
        $details = array(
            'user_id' => $originalUserId
        );
        try {
            if ($this->_db->update($this->_project, $details, $where)) {
                $this->delDummyUserId($dummyUserId);
                return true;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
   }
   public function delDummyUserId($dummyUserId) {
        if (empty($dummyUserId)) {
            return false;
        }
        $table = $this->_users;

        try {
            $where[] = "user_id = $dummyUserId";
            if ($this->_db->delete($table, $where)) {
                return true;
            }
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

}

?>
