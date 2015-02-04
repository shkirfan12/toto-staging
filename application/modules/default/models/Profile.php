<?php

class Default_Model_Profile extends Zend_Db_Table_Abstract {

    protected $_users = 'users';

     public function fetchUserProfileDetails($userId) {
        $sql = $this->_db->select("email_id,profileType")
                ->from($this->_users)
                ->where('user_id = '.$userId);
        try {
            $result = $this->_db->fetchRow($sql);
            return $result;
        } catch (Zend_Db_Adapter_Exception $e) {
            return false;
        }
    }
    public function updateProfile($profile_email, $userType, $userId) {
        if ((empty($profile_email)) || (empty($userType)) || (empty($userId))) {
            return false;
        }

        $now = new Zend_Db_Expr('NOW()');

        $category = array(
            'email_id' => $profile_email,
            'profileType' => $userType,
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
    
     public function getuserinfo($userId) { 
        $sql = "SELECT users.* , (select count(project.project_id) from project where users.user_id = project.user_id) as project_count , (select count(collection.collection_id) from collection where users.user_id = collection.user_id) as collection_count
                FROM users WHERE `user_id`='$userId'";  

        $result = $this->getDefaultAdapter()->fetchAll($sql); 
        if ($result) {
            foreach ($result as $key => $val) {

                $sql_new = "";
                $sql_new = " select product_id from collection where user_id = '" . $val['user_id'] . "'";

                $result_new = $this->getDefaultAdapter()->fetchAll($sql_new);
                if ($result_new) {
                    $prdct_string = "";
                    foreach ($result_new as $keynew => $valnew) {
                        //  substr($val['product_id'], 0, -1);
                        //$new_array = explode(',' , $val['product_id']);

                        $prdct_string .= trim($valnew['product_id']);

                        if (strlen($prdct_string) > 1) {
                            $prdct_string_n = substr($prdct_string, 0, -1);
                        } else {
                            $prdct_string_n = $prdct_string;
                        }

                        $product_array = explode(',', $prdct_string_n);

                        //print_r($product_array); echo "-----";

                        $product_array_new = array_unique($product_array);

                        $product_count = count($product_array_new);

                        $result[$key]['product_count'] = $product_count;
                    }
                } else {
                    $result[$key]['product_count'] = 0;
                }
            }
        }
        return $result;
    }

}

?>
