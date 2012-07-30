<?php
/**
 * Users's model
 *
 * @author Ionut Cristian Ciuperca <ionut.ciuperca@gmail.com>
 */
require_once SYSTEMFOLDER.'/model.php';
require_once SYSTEMFOLDER.'/password.php';

class User_Model extends Model {

    public function checkPass($username, $password) {
        if(strlen(trim($username)) == 0) {
            return false;
        }
        if(strlen(trim($password)) < 6) {
            return false;
        }
        $sql = "SELECT `user_id`, `username`, `is_admin`, `password`, `salt`, `first_name`, `last_name`
                FROM `users`
                WHERE `username`=?
                LIMIT 1
            ";
        $userdata = $this->getAssocRow($sql, $username);
        if(empty($userdata)) {
            return false;
        }

        $passwordCheck = new Password($password, $userdata['salt']);
        $salted_pass = $passwordCheck->getHashedPassword();
        if($userdata['password'] === $salted_pass) {
            unset($userdata['password']);
            unset($userdata['salt']);
            return $userdata;
        } else {
            return false;
        }
    }
}