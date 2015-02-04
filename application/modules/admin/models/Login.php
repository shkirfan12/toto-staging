<?php

class Admin_Model_Login extends Zend_Db_Table_Abstract {

    protected $_users    = 'users';

    public function getAdminLogin($username, $password) {
        if(empty($username) && empty($password)) {
            return false;
        }
        $sql = $this->_db->select()
                ->from($this->_users)
                ->where("email_id='" . $username . "' and password='" . md5($password) . "'");
     
        try {
            $result = $this->_db->fetchRow($sql); 
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }

}

?>
