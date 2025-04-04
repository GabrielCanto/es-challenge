<?php

require_once( dirname(__FILE__) . DIRECTORY_SEPARATOR . 'DB.php');

class UserModel {

    public function getByUsername($username) {
        
        $db = DB::connect();
        $result = $db->query('SELECT * FROM employees WHERE username=\'' . $username . '\'');
        
        if ( $result ) {
            $row = $result->fetchObject();
            return $row;
        }
    }

}