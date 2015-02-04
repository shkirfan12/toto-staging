<?php

class Login_Model_Users extends Zend_Db_Table_Abstract {

    protected $_users     = 'users';
    protected $_userRole  = 'user_type';

    public function fetchUserDetail($userEmail,$type) {
        if (empty($userEmail)) {
            return false;
        }
        $sql = $this->_db->select()
                ->from(array('u' => $this->_users), array('user_id', 'user_type_id', 'first_name', 'last_name', 'email_id', 'password'))
                ->joinLeft(array('r' => $this->_userRole), 'u.user_type_id = r.user_type_id', array('user_type'))
                ->where('u.email_id = ?', $userEmail)
                ->where('u.user_type_id = ?', $type)
                ->where('u.submit = ?', 'y')
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
                ->where('email_id =' . "'" . $email . "'");
        try {
            $result = $this->_db->fetchRow($qry);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    public function changePassword($newPassword, $userId) {
        if ((empty($newPassword)) || (empty($userId))) {
            return false;
        }

        $now = new Zend_Db_Expr('NOW()');

        $category = array(
            'password' => md5($newPassword),
            'updated_on' => $now
        );
        $where['user_id=?'] = $userId;

        try {
            $result = $this->_db->update($this->_users, $category, $where);
            return true;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

    public function fetchUserType() {
        $sql = $this->_db->select()
                ->from($this->_userRole)
                ->where('user_type_id != 4' );

        try {
            $result = $this->_db->fetchAll($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
   
    /* ====================================================== */

    /* random key function */

    public function str_rand($length = 8, $seeds = 'alphanum') {
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

}

?>
