<?php

class Default_Model_GlobalFunctions_CommonFunctions extends Zend_Db_Table_Abstract {

    /* Function to send an email
     * pass the required parameters and return True or False depending on the status
     */
    public function sendEmail($mailFromEmailId, $mailFromName, $mailToEmailId, $mailSubject, $mailBody) { 
     // echo $mailFromEmailId."--<br/>".$mailFromName."--<br/>".$mailToEmailId."--<br/>".$mailSubject."--<br/>".$mailBody.'--'; die;
     
     
        require_once APPLICATION_PATH . '/../library/Zend/Mail.php';

        $config = Zend_Registry::get('config');
        /*$username = $config->email->config->username;
        $password = $config->email->config->password;
        $auth = $config->email->config->auth;
        $smtp = $config->mail->transport->smtp;
        $admin = $config->mail->transport->admin;
        $mailFromEmailId = $admin;
        $mailFromName    = $username;*/
        
        //$config = array('auth' =>$auth ,'username' => $username,'password' =>$password );
        //$transport = new Zend_Mail_Transport_Smtp($smtp, $config);

        $mail = new Zend_Mail();
        $mail->setBodyHtml($mailBody);

        if (!empty($mailFromName)) {    //echo "<br>1"; echo $mailFromEmailId.'--'.$mailFromName; die;
            $mail->setFrom($mailFromEmailId, $mailFromName); //  $mail->setFrom('vidya.patankar@azularc.com', 'United Way Admin');
        } else { //echo "<br>2"; die;
            $mail->setFrom($mailFromEmailId);
        }
        
        $mail->addTo($mailToEmailId);
        $mail->setSubject($mailSubject);
        try {
            if ($mail->send()) { //echo 'send'; die;
                return true;
            } else { //echo "error on mail send"; die;
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    
    /* multiple recipients << */
     public function sendEmailToMultipleRecipients($mailFromEmailId, $mailFromName, $mailToEmailId, $mailSubject, $mailBody) {
       //echo $mailFromEmailId."<br/>".$mailFromName."<br/>".$mailToEmailId."<br/>".$mailSubject."<br/>".$mailBody;  
       //echo "<pre>"; print_r($mailToEmailId);
       //die;
     
     
        require_once APPLICATION_PATH . '/../library/Zend/Mail.php';

        $config = Zend_Registry::get('config');
        /*$username = $config->email->config->username;
        $password = $config->email->config->password;
        $auth = $config->email->config->auth;
        $smtp = $config->mail->transport->smtp;
        $admin = $config->mail->transport->admin;
        $mailFromEmailId = $admin;
        $mailFromName    = $username;*/
        
        //$config = array('auth' =>$auth ,'username' => $username,'password' =>$password );
        //$transport = new Zend_Mail_Transport_Smtp($smtp, $config);
       
        foreach ($mailToEmailId as $to) {
            $mail = new Zend_Mail();
            $mail->setBodyHtml($mailBody);

            if (!empty($mailFromName)) {
                $mail->setFrom("$mailFromEmailId", $mailFromName); //$mail->setFrom('amit.chauhan@azularc.com', 'Amit Kumar');
            } else {
                $mail->setFrom("$mailFromEmailId");
            }

            $mail->addTo("$to"); 
            $mail->setSubject($mailSubject);
            $mail->send();
        }
        /*try {
            if ($mail->send()) { //echo 'send'; die;
                return true;
            } else { //echo "error on mail send"; die;
                return false;
            }
        } catch (Exception $e) {
            return false;
        }*/
        
        
    }
    /* multiple recipients >> */
    

  


   

}